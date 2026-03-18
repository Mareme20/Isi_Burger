<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Burger;
use App\Models\Commande;
use App\Models\CommandeHistorique;
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

        $commandesEnCours = Commande::where('gestionnaire_id', $gestionnaireId)
            ->whereIn('statut', ['en_attente', 'en_preparation'])
            ->count();

        $commandesAnnulees = Commande::where('gestionnaire_id', $gestionnaireId)
            ->where('statut', 'annulee')
            ->count();

        $commandesPretes = Commande::where('gestionnaire_id', $gestionnaireId)
            ->where('statut', 'prete')
            ->count();

        $commandesNonAttribuees = Commande::whereNull('gestionnaire_id')
            ->whereIn('statut', ['en_attente', 'en_preparation'])
            ->count();

        $burgersStockFaible = Burger::query()
            ->where('is_archived', false)
            ->whereBetween('stock', [1, Burger::LOW_STOCK_THRESHOLD])
            ->orderBy('stock')
            ->limit(6)
            ->get(['id', 'nom', 'stock']);

        $burgersEnRupture = Burger::query()
            ->where('is_archived', false)
            ->where('stock', '<=', 0)
            ->orderBy('nom')
            ->limit(6)
            ->get(['id', 'nom', 'stock']);

        $topBurger = DB::table('commande_burger')
            ->join('commandes', 'commandes.id', '=', 'commande_burger.commande_id')
            ->join('burgers', 'burgers.id', '=', 'commande_burger.burger_id')
            ->where('commandes.gestionnaire_id', $gestionnaireId)
            ->selectRaw('burgers.nom as nom, SUM(commande_burger.quantite) as total')
            ->groupBy('burgers.id', 'burgers.nom')
            ->orderByDesc('total')
            ->first();

        $tempsMoyenPreparation = CommandeHistorique::query()
            ->join('commandes', 'commandes.id', '=', 'commande_historiques.commande_id')
            ->where('commandes.gestionnaire_id', $gestionnaireId)
            ->where('commande_historiques.action', 'statut_modifie')
            ->where('commande_historiques.description', 'like', '% a prete.%')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, commandes.created_at, commande_historiques.created_at)) as avg_minutes')
            ->value('avg_minutes');

        $ticketMoyen = Paiement::query()
            ->join('commandes', 'commandes.id', '=', 'paiements.commande_id')
            ->where('commandes.gestionnaire_id', $gestionnaireId)
            ->avg('paiements.montant');

        $topClient = Commande::query()
            ->join('users', 'users.id', '=', 'commandes.user_id')
            ->where('commandes.gestionnaire_id', $gestionnaireId)
            ->selectRaw('users.name as nom, COUNT(commandes.id) as total')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total')
            ->first();

        $heurePic = Commande::query()
            ->where('gestionnaire_id', $gestionnaireId)
            ->selectRaw('HOUR(created_at) as heure, COUNT(*) as total')
            ->groupBy('heure')
            ->orderByDesc('total')
            ->first();

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

        $activitesRecentes = CommandeHistorique::query()
            ->join('commandes', 'commandes.id', '=', 'commande_historiques.commande_id')
            ->where('commandes.gestionnaire_id', $gestionnaireId)
            ->select('commande_historiques.*')
            ->with(['commande', 'user'])
            ->latest('commande_historiques.created_at')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'commandesDuJour',
            'commandesValidees',
            'recetteJour',
            'commandesEnCours',
            'commandesAnnulees',
            'commandesPretes',
            'commandesNonAttribuees',
            'topBurger',
            'tempsMoyenPreparation',
            'ticketMoyen',
            'topClient',
            'heurePic',
            'commandesParMois',
            'produitsParCategorie',
            'activitesRecentes',
            'burgersStockFaible',
            'burgersEnRupture'
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
