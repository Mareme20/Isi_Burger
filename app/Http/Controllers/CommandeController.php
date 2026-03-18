<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCommandeStatutRequest;
use App\Models\Burger;
use App\Models\Commande;
use App\Models\User;
use App\Notifications\NouvelleCommandeNotification;
use App\Support\Panier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

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

        $commande = DB::transaction(function () use ($lignes, $panier, $items) {
            $commande = Commande::create([
                'user_id' => auth()->id(),
                'statut' => 'en_attente',
                'total' => $panier['total'],
            ]);

            $commande->burgers()->attach($lignes);

            foreach ($items as $ligne) {
                $ligne['burger']->decrement('stock', $ligne['quantite']);
            }

            return $commande->fresh(['user', 'burgers']);
        });

        Panier::clear();

        $this->envoyerConfirmationCommande($commande);
        $this->notifierGestionnairesNouvelleCommande($commande);

        return redirect()->route('commandes.mes')
            ->with('success', 'Commande effectuee.');
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

public function index()
{
    $commandes = Commande::with(['user', 'burgers'])
        ->latest()
        ->paginate(10);

    return view('admin.commandes.index', compact('commandes'));
}

public function show(Commande $commande)
{
    $commande->load(['user', 'burgers', 'paiement']);

    return view('admin.commandes.show', compact('commande'));
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

    if (! $commande->gestionnaire_id) {
        $commande->gestionnaire_id = auth()->id();
    }

    $commande->update([
        'statut' => $nouveauStatut,
        'gestionnaire_id' => $commande->gestionnaire_id,
    ]);

    if ($nouveauStatut === 'prete') {
        $this->envoyerFacture($commande);
    }

    return back()->with('success', 'Statut mis à jour.');
}

public function enregistrerPaiement(Request $request, Commande $commande)
{
    if ($commande->is_paid) {
        return back()->with('error', 'Commande déjà payée.');
    }

    if ($commande->statut === 'annulee') {
        return back()->with('error', 'Une commande annulee ne peut pas etre encaissee.');
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
        return back()->withErrors([
            'montant' => 'Le montant saisi doit correspondre exactement au total de la commande.',
        ])->withInput();
    }

    if (! $commande->gestionnaire_id) {
        $commande->gestionnaire_id = auth()->id();
        $commande->save();
    }

    $commande->paiement()->create([
        'montant' => $montant,
        'date_paiement' => now()
    ]);

    $commande->update([
        'is_paid' => true,
        'paid_at' => now(),
        'statut' => 'payee'
    ]);

    return back()->with('success', 'Paiement enregistré.');
}



private function envoyerFacture(Commande $commande)
{
    $pdf = Pdf::loadView('pdf.facture', compact('commande'));

    Mail::send('emails.facture', 
        ['commande' => $commande], 
        function ($message) use ($commande, $pdf) {

        $message->to($commande->user->email)
                ->subject('Votre facture ISI BURGER')
                ->attachData(
                    $pdf->output(),
                    'facture.pdf'
                );
    });
}

private function envoyerConfirmationCommande(Commande $commande): void
{
    Mail::send(
        'emails.commande-confirmation',
        ['commande' => $commande],
        function ($message) use ($commande) {
            $message->to($commande->user->email)
                ->subject("Confirmation de commande #{$commande->id} - ISI BURGER");
        }
    );
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
        // Single send for all managers to avoid SMTP rate-limit rejections.
        Notification::route('mail', $emails)
            ->notify(new NouvelleCommandeNotification($commande));
    } catch (\Throwable $e) {
        Log::error('Echec envoi notification nouvelle commande.', [
            'commande_id' => $commande->id,
            'error' => $e->getMessage(),
        ]);
    }
}

// Commandes filtrées par mois
public function commandesData(Request $request)
{
    $query = Commande::selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
        ->groupBy('mois')
        ->orderBy('mois');

    if ($request->filled('mois')) {
        $query->whereMonth('created_at', $request->mois);
    }

    return response()->json($query->pluck('total','mois'));
}

// Produits filtrés par catégorie
public function produitsData(Request $request)
{
    $query = DB::table('commande_burger')
        ->join('commandes', 'commandes.id', '=', 'commande_burger.commande_id')
        ->join('burgers', 'burgers.id', '=', 'commande_burger.burger_id')
        ->join('categories', 'categories.id', '=', 'burgers.category_id')
        ->selectRaw('categories.nom as categorie, SUM(commande_burger.quantite) as total')
        ->groupBy('categories.nom')
        ->orderBy('categories.nom');

    if ($request->filled('mois')) {
        $query->whereMonth('commandes.created_at', (int) $request->mois);
    }

    return response()->json($query->pluck('total','categorie'));
}
public function mesCommandes()
{
    // On récupère les commandes de l'utilisateur connecté avec ses burgers
    $commandes = auth()->user()->commandes()
        ->with('burgers')
        ->latest()
        ->get();

    return view('commandes.mes', compact('commandes'));
}

}
