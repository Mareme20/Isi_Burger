<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommandeRequest;
use App\Http\Requests\UpdateCommandeStatutRequest;
use App\Models\Burger;
use App\Models\Commande;
use App\Models\User;
use App\Notifications\NouvelleCommandeNotification;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class CommandeController extends Controller
{

public function store(StoreCommandeRequest $request)
{
    $validated = $request->validated();
    $items = collect($validated['items'] ?? [])
        ->mapWithKeys(fn ($quantite, $burgerId) => [(int) $burgerId => (int) $quantite])
        ->filter(fn ($quantite) => $quantite > 0);

    if ($items->isEmpty() && isset($validated['burger_id'], $validated['quantite'])) {
        $items = collect([(int) $validated['burger_id'] => (int) $validated['quantite']])
            ->filter(fn ($quantite) => $quantite > 0);
    }

    if ($items->isEmpty()) {
        return back()->withInput()->withErrors(['items' => 'Ajoutez au moins un burger à la commande.']);
    }

    $burgerIds = $items->keys()->all();
    $burgers = Burger::whereIn('id', $burgerIds)->get()->keyBy('id');
    $lignes = [];
    $total = 0;

    foreach ($items as $burgerId => $quantite) {
        $burger = $burgers->get($burgerId);
        if (! $burger) {
            return back()->withInput()->withErrors(['items' => 'Un burger sélectionné est introuvable.']);
        }
        if ($burger->stock < $quantite) {
            return back()
                ->withInput()
                ->withErrors(['items' => "Stock insuffisant pour {$burger->nom}."]);
        }

        $lignes[$burgerId] = [
            'quantite' => $quantite,
            'prix_unitaire' => $burger->prix,
        ];
        $total += $burger->prix * $quantite;
    }

    $commande = DB::transaction(function () use ($lignes, $total, $burgers) {
        $commande = Commande::create([
            'user_id' => auth()->id(),
            'statut' => 'en_attente',
            'total' => $total
        ]);

        $commande->burgers()->attach($lignes);

        foreach ($lignes as $burgerId => $ligne) {
            $burgers->get($burgerId)?->decrement('stock', $ligne['quantite']);
        }

        return $commande->fresh(['user', 'burgers']);
    });

    $this->envoyerConfirmationCommande($commande);
    $this->notifierGestionnairesNouvelleCommande($commande);

    return redirect()->route('commandes.mes')
        ->with('success', 'Commande effectuée.');
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
    $commande->update([
        'statut' => $request->validated('statut')
    ]);

    if ($request->validated('statut') === 'prete') {
        $this->envoyerFacture($commande);
    }

    return back()->with('success', 'Statut mis à jour.');
}

public function enregistrerPaiement(Commande $commande)
{
    if ($commande->is_paid) {
        return back()->with('error', 'Commande déjà payée.');
    }

    $commande->paiement()->create([
        'montant' => $commande->total,
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
