<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Paiement;
use Illuminate\Support\Facades\DB;
class DashboardController extends Controller
{
    public function index()
    {
        $gestionnaireId = auth()->id();

        $commandesDuJour = Commande::where('gestionnaire_id', $gestionnaireId)
            ->whereDate('created_at', today())
            ->count();

        $commandesValidees = Paiement::query()
            ->join('commandes', 'commandes.id', '=', 'paiements.commande_id')
            ->where('commandes.gestionnaire_id', $gestionnaireId)
            ->whereDate('paiements.date_paiement', today())
            ->count();

        $recetteJour = Paiement::query()
            ->join('commandes', 'commandes.id', '=', 'paiements.commande_id')
            ->where('commandes.gestionnaire_id', $gestionnaireId)
            ->whereDate('paiements.date_paiement', today())
            ->sum('paiements.montant');

        $commandesParMois = Commande::where('gestionnaire_id', $gestionnaireId)
            ->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')
            ->orderBy('mois')
            ->pluck('total', 'mois');

        $produitsParCategorie = DB::table('commande_burger')
            ->join('commandes', 'commandes.id', '=', 'commande_burger.commande_id')
            ->join('burgers', 'burgers.id', '=', 'commande_burger.burger_id')
            ->join('categories', 'categories.id', '=', 'burgers.category_id')
            ->where('commandes.gestionnaire_id', $gestionnaireId)
            ->whereMonth('commandes.created_at', now()->month)
            ->selectRaw('categories.nom as categorie, SUM(commande_burger.quantite) as total')
            ->groupBy('categories.nom')
            ->orderBy('categories.nom')
            ->pluck('total', 'categorie');

        return view('admin.dashboard', compact(
            'commandesDuJour',
            'commandesValidees',
            'recetteJour',
            'commandesParMois',
            'produitsParCategorie'
        ));
    }

    public function commandesData(Request $request)
    {
        $query = Commande::where('gestionnaire_id', auth()->id())
            ->selectRaw('MONTH(created_at) as mois, COUNT(*) as total')
            ->groupBy('mois')
            ->orderBy('mois');

        if ($request->filled('mois')) {
            $query->whereMonth('created_at', (int) $request->mois);
        }

        return response()->json($query->pluck('total', 'mois'));
    }

    public function produitsData(Request $request)
    {
        $query = DB::table('commande_burger')
            ->join('commandes', 'commandes.id', '=', 'commande_burger.commande_id')
            ->join('burgers', 'burgers.id', '=', 'commande_burger.burger_id')
            ->join('categories', 'categories.id', '=', 'burgers.category_id')
            ->where('commandes.gestionnaire_id', auth()->id())
            ->selectRaw('categories.nom as categorie, SUM(commande_burger.quantite) as total')
            ->groupBy('categories.nom')
            ->orderBy('categories.nom');

        if ($request->filled('mois')) {
            $query->whereMonth('commandes.created_at', (int) $request->mois);
        }

        return response()->json($query->pluck('total', 'categorie'));
    }
}
