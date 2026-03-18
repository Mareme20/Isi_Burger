<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCommandeStatutRequest;
use App\Models\Burger;
use App\Models\Commande;
use App\Models\User;
use App\Notifications\NouvelleCommandeNotification;
use App\Support\Panier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CommandeController extends Controller
{
    public function addToCart(Request $request, Burger $burger)
    {
        $data = $request->validate([
            'quantite' => ['required', 'integer', 'min:1'],
        ]);

        if ($burger->is_archived || $burger->stock <= 0) {
            return $this->panierResponse($request, 'Ce burger n\'est plus disponible.', 'error', 422);
        }

        $nouvelleQuantite = Panier::quantity($burger->id) + (int) $data['quantite'];

        if ($nouvelleQuantite > $burger->stock) {
            return $this->panierResponse($request, "Stock insuffisant pour {$burger->nom}.", 'error', 422);
        }

        Panier::update($burger, $nouvelleQuantite);

        return $this->panierResponse($request, "{$burger->nom} a ete ajoute au panier.");
    }

    public function updateCart(Request $request, Burger $burger)
    {
        $data = $request->validate([
            'quantite' => ['required', 'integer', 'min:0'],
        ]);

        $quantite = (int) $data['quantite'];

        if ($quantite === 0) {
            Panier::remove($burger->id);

            return $this->panierResponse($request, "{$burger->nom} a ete retire du panier.");
        }

        if ($burger->is_archived || $burger->stock <= 0) {
            Panier::remove($burger->id);

            return $this->panierResponse($request, 'Ce burger n\'est plus disponible et a ete retire du panier.', 'error', 422);
        }

        if ($quantite > $burger->stock) {
            return $this->panierResponse($request, "Stock insuffisant pour {$burger->nom}.", 'error', 422);
        }

        Panier::update($burger, $quantite);

        return $this->panierResponse($request, "Quantite mise a jour pour {$burger->nom}.");
    }

    public function removeFromCart(Request $request, Burger $burger)
    {
        Panier::remove($burger->id);

        return $this->panierResponse($request, "{$burger->nom} a ete supprime du panier.");
    }

    public function clearCart(Request $request)
    {
        Panier::clear();

        return $this->panierResponse($request, 'Le panier a ete vide.');
    }

    public function store(Request $request)
    {
        $panier = Panier::summary();
        $items = collect($panier['items']);

        if ($items->isEmpty()) {
            return back()->withErrors(['items' => 'Votre panier est vide.']);
        }

        $lignes = [];

        foreach ($items as $ligne) {
            $burger = $ligne['burger'];
            $quantite = (int) $ligne['quantite'];

            if ($burger->stock < $quantite) {
                return back()->withErrors(['items' => "Stock insuffisant pour {$burger->nom}."]);
            }

            $lignes[$burger->id] = [
                'quantite' => $quantite,
                'prix_unitaire' => $burger->prix,
            ];
        }

        $commande = DB::transaction(function () use ($items, $lignes, $panier) {
            $commande = Commande::create([
                'user_id' => auth()->id(),
                'statut' => 'en_attente',
                'total' => $panier['total'],
            ]);

            $commande->burgers()->attach($lignes);

            foreach ($items as $ligne) {
                $ligne['burger']->decrement('stock', $ligne['quantite']);
            }

            $commande->historiques()->create([
                'user_id' => auth()->id(),
                'action' => 'commande_creee',
                'description' => 'Commande creee par le client.',
                'metadata' => [
                    'statut' => 'en_attente',
                    'total' => $panier['total'],
                ],
            ]);

            return $commande->fresh(['user', 'burgers']);
        });

        Panier::clear();

        $this->envoyerConfirmationCommande($commande);
        $this->notifierGestionnairesNouvelleCommande($commande);

        return redirect()
            ->route('commandes.mes')
            ->with('success', 'Commande effectuee.');
    }

    public function index(Request $request)
    {
        $statsBaseQuery = $this->applyAdminFilters(Commande::query(), $request);

        $stats = [
            'total' => (clone $statsBaseQuery)->count(),
            'paid' => (clone $statsBaseQuery)->where('is_paid', true)->count(),
            'pending' => (clone $statsBaseQuery)->where('is_paid', false)->count(),
            'unassigned' => (clone $statsBaseQuery)->whereNull('gestionnaire_id')->count(),
            'urgent' => (clone $statsBaseQuery)
                ->whereIn('statut', ['en_attente', 'en_preparation'])
                ->where('created_at', '<=', now()->subMinutes(15))
                ->count(),
        ];

        $commandes = $this->applyAdminFilters(
            Commande::with(['user', 'gestionnaire', 'burgers', 'latestHistorique.user']),
            $request
        )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $gestionnaires = User::role('gestionnaire')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $liveSummary = $this->buildLiveSummary();

        return view('admin.commandes.index', compact('commandes', 'gestionnaires', 'stats', 'liveSummary'));
    }

    public function show(Commande $commande)
    {
        $commande->load(['user', 'gestionnaire', 'burgers', 'paiement', 'historiques.user']);

        $gestionnaires = User::role('gestionnaire')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.commandes.show', compact('commande', 'gestionnaires'));
    }

    public function assignToMe(Request $request, Commande $commande)
    {
        if (in_array($commande->statut, ['annulee', 'payee'], true)) {
            return back()->with('error', 'Une commande finalisee ne peut plus etre reassignee.');
        }

        $data = $request->validate([
            'gestionnaire_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $targetGestionnaireId = (int) ($data['gestionnaire_id'] ?? auth()->id());

        $gestionnaire = User::role('gestionnaire')
            ->whereKey($targetGestionnaireId)
            ->first();

        if (! $gestionnaire) {
            return back()->with('error', 'Gestionnaire invalide.');
        }

        if ($commande->gestionnaire_id === $gestionnaire->id) {
            return back()->with('success', 'Cette commande est deja attribuee a ce gestionnaire.');
        }

        $ancienGestionnaire = $commande->gestionnaire;

        $commande->update([
            'gestionnaire_id' => $gestionnaire->id,
        ]);

        $description = $ancienGestionnaire
            ? "Commande reaffectee de {$ancienGestionnaire->name} vers {$gestionnaire->name}."
            : "Commande attribuee a {$gestionnaire->name}.";

        $commande->historiques()->create([
            'user_id' => auth()->id(),
            'action' => $ancienGestionnaire ? 'commande_reaffectee' : 'commande_assignee',
            'description' => $description,
            'metadata' => [
                'ancien_gestionnaire_id' => $ancienGestionnaire?->id,
                'nouveau_gestionnaire_id' => $gestionnaire->id,
            ],
        ]);

        return back()->with('success', 'Attribution mise a jour.');
    }

    public function updateStatut(UpdateCommandeStatutRequest $request, Commande $commande)
    {
        $nouveauStatut = $request->validated('statut');

        if ($commande->statut === 'annulee') {
            return back()->with('error', 'Une commande annulee ne peut plus etre modifiee.');
        }

        $transitionsAutorisees = [
            'en_attente' => ['en_preparation', 'annulee'],
            'en_preparation' => ['prete', 'annulee'],
            'prete' => [],
            'annulee' => [],
            'payee' => [],
        ];

        $statutActuel = $commande->statut;

        if (! in_array($nouveauStatut, $transitionsAutorisees[$statutActuel] ?? [], true)) {
            return back()->with('error', 'Transition de statut non autorisee pour cette commande.');
        }

        if ($commande->gestionnaire_id && $commande->gestionnaire_id !== auth()->id()) {
            return back()->with('error', 'Cette commande est geree par un autre gestionnaire.');
        }

        if (! $commande->gestionnaire_id) {
            $commande->gestionnaire_id = auth()->id();
        }

        $commande->update([
            'statut' => $nouveauStatut,
            'gestionnaire_id' => $commande->gestionnaire_id,
        ]);

        $commande->historiques()->create([
            'user_id' => auth()->id(),
            'action' => 'statut_modifie',
            'description' => "Statut passe de {$statutActuel} a {$nouveauStatut}.",
            'metadata' => [
                'avant' => $statutActuel,
                'apres' => $nouveauStatut,
            ],
        ]);

        if ($nouveauStatut === 'prete') {
            $this->envoyerFacture($commande->fresh(['user']));
        }

        return back()->with('success', 'Statut mis a jour.');
    }

    public function enregistrerPaiement(Request $request, Commande $commande)
    {
        if ($commande->is_paid) {
            return back()->with('error', 'Commande deja payee.');
        }

        if ($commande->statut === 'annulee') {
            return back()->with('error', 'Une commande annulee ne peut pas etre encaissee.');
        }

        if ($commande->gestionnaire_id && $commande->gestionnaire_id !== auth()->id()) {
            return back()->with('error', 'Cette commande est geree par un autre gestionnaire.');
        }

        $data = $request->validate([
            'montant' => ['required', 'numeric', 'min:0'],
        ], [
            'montant.required' => 'Le montant est obligatoire.',
            'montant.numeric' => 'Le montant doit etre numerique.',
            'montant.min' => 'Le montant doit etre positif.',
        ]);

        $montant = round((float) $data['montant'], 2);
        $montantAttendu = round((float) $commande->total, 2);

        if ($montant !== $montantAttendu) {
            return back()
                ->withErrors([
                    'montant' => 'Le montant saisi doit correspondre exactement au total de la commande.',
                ])
                ->withInput();
        }

        if (! $commande->gestionnaire_id) {
            $commande->gestionnaire_id = auth()->id();
            $commande->save();
        }

        $commande->paiement()->create([
            'montant' => $montant,
            'date_paiement' => now(),
        ]);

        $commande->update([
            'is_paid' => true,
            'paid_at' => now(),
            'statut' => 'payee',
        ]);

        $commande->historiques()->create([
            'user_id' => auth()->id(),
            'action' => 'paiement_enregistre',
            'description' => 'Paiement enregistre par le gestionnaire.',
            'metadata' => [
                'montant' => $montant,
            ],
        ]);

        return back()->with('success', 'Paiement enregistre.');
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $commandes = $this->applyAdminFilters(
            Commande::with(['user', 'gestionnaire', 'burgers', 'latestHistorique.user']),
            $request
        )
            ->latest()
            ->get();

        $filename = 'commandes-gestion-' . now()->format('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($commandes) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'ID',
                'Date',
                'Client',
                'Email client',
                'Gestionnaire',
                'Statut',
                'Paiement',
                'Total FCFA',
                'Produits',
                'Derniere action',
                'Derniere action le',
            ], ';');

            foreach ($commandes as $commande) {
                $produits = $commande->burgers
                    ->map(fn ($burger) => $burger->nom . ' x' . $burger->pivot->quantite)
                    ->implode(' | ');

                fputcsv($handle, [
                    $commande->id,
                    $commande->created_at->format('d/m/Y H:i'),
                    $commande->user?->name,
                    $commande->user?->email,
                    $commande->gestionnaire?->name ?? 'Non attribuee',
                    $commande->statut,
                    $commande->is_paid ? 'Payee' : 'Non payee',
                    number_format((float) $commande->total, 0, ',', ' '),
                    $produits,
                    $commande->latestHistorique?->description ?? '',
                    $commande->latestHistorique?->created_at?->format('d/m/Y H:i') ?? '',
                ], ';');
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function liveSummary()
    {
        return response()->json($this->buildLiveSummary());
    }

    public function mesCommandes()
    {
        $commandes = auth()->user()
            ->commandes()
            ->with('burgers')
            ->latest()
            ->get();

        return view('commandes.mes', compact('commandes'));
    }

    private function panierResponse(Request $request, string $message, string $type = 'success', int $status = 200)
    {
        if ($request->expectsJson()) {
            $panier = Panier::summary();

            return response()->json([
                'message' => $message,
                'type' => $type,
                'count' => $panier['count'],
                'html' => view('catalogue._panier', compact('panier'))->render(),
            ], $status);
        }

        return back()->with($type, $message);
    }

    private function applyAdminFilters(Builder $query, Request $request): Builder
    {
        if ($request->filled('q')) {
            $search = trim((string) $request->q);

            $query->where(function (Builder $inner) use ($search) {
                if (is_numeric($search)) {
                    $inner->where('id', (int) $search)
                        ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                        });
                } else {
                    $inner->whereHas('user', function (Builder $userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            });
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('paiement')) {
            $query->where('is_paid', $request->paiement === 'payee');
        }

        if ($request->filled('attribution')) {
            if ($request->attribution === 'moi') {
                $query->where('gestionnaire_id', auth()->id());
            } elseif ($request->attribution === 'non_attribuee') {
                $query->whereNull('gestionnaire_id');
            } elseif ($request->attribution === 'autres') {
                $query->whereNotNull('gestionnaire_id')
                    ->where('gestionnaire_id', '!=', auth()->id());
            }
        }

        if ($request->filled('date_du')) {
            $query->whereDate('created_at', '>=', $request->date_du);
        }

        if ($request->filled('date_au')) {
            $query->whereDate('created_at', '<=', $request->date_au);
        }

        return $query;
    }

    private function buildLiveSummary(): array
    {
        $latest = Commande::query()
            ->latest()
            ->first(['id', 'created_at', 'gestionnaire_id', 'statut']);

        $lowStockCount = Burger::query()
            ->where('is_archived', false)
            ->whereBetween('stock', [1, Burger::LOW_STOCK_THRESHOLD])
            ->count();

        $outOfStockCount = Burger::query()
            ->where('is_archived', false)
            ->where('stock', '<=', 0)
            ->count();

        $lowStockBurgers = Burger::query()
            ->where('is_archived', false)
            ->whereBetween('stock', [1, Burger::LOW_STOCK_THRESHOLD])
            ->orderBy('stock')
            ->limit(5)
            ->get(['id', 'nom', 'stock'])
            ->map(fn (Burger $burger) => [
                'id' => $burger->id,
                'nom' => $burger->nom,
                'stock' => (int) $burger->stock,
            ])
            ->values()
            ->all();

        return [
            'latest_commande_id' => $latest?->id,
            'latest_created_at' => $latest?->created_at?->toIso8601String(),
            'pending_total' => Commande::whereIn('statut', ['en_attente', 'en_preparation'])->count(),
            'urgent_total' => Commande::whereIn('statut', ['en_attente', 'en_preparation'])
                ->where('created_at', '<=', now()->subMinutes(15))
                ->count(),
            'unassigned_total' => Commande::whereNull('gestionnaire_id')
                ->whereIn('statut', ['en_attente', 'en_preparation'])
                ->count(),
            'low_stock_total' => $lowStockCount,
            'out_of_stock_total' => $outOfStockCount,
            'low_stock_burgers' => $lowStockBurgers,
        ];
    }

    private function envoyerFacture(Commande $commande): void
    {
        $pdf = Pdf::loadView('pdf.facture', compact('commande'));

        Mail::send('emails.facture', ['commande' => $commande], function ($message) use ($commande, $pdf) {
            $message->to($commande->user->email)
                ->subject('Votre facture ISI BURGER')
                ->attachData($pdf->output(), 'facture.pdf');
        });
    }

    private function envoyerConfirmationCommande(Commande $commande): void
    {
        Mail::send('emails.commande-confirmation', ['commande' => $commande], function ($message) use ($commande) {
            $message->to($commande->user->email)
                ->subject("Confirmation de commande #{$commande->id} - ISI BURGER");
        });
    }

    private function notifierGestionnairesNouvelleCommande(Commande $commande): void
    {
        $emails = User::role('gestionnaire')
            ->whereNotNull('email')
            ->pluck('email')
            ->unique()
            ->values()
            ->all();

        if (empty($emails)) {
            return;
        }

        try {
            Notification::route('mail', $emails)
                ->notify(new NouvelleCommandeNotification($commande));
        } catch (\Throwable $e) {
            Log::error('Echec envoi notification nouvelle commande.', [
                'commande_id' => $commande->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
