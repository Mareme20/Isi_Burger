<?php

namespace App\Http\Controllers;

use App\Models\Burger;
use App\Models\Category;
use Illuminate\Http\Request;

class CatalogueController extends Controller
{
    public function index(Request $request)
    {
        $query = Burger::with('category')
            ->where('stock', '>', 0); // On n'affiche que ce qui est en stock

        // 🔎 Filtre libellé
        if ($request->filled('nom')) {
            $query->where('nom', 'like', '%' . $request->nom . '%');
        }

        // 💰 Filtre prix max
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // 📂 Filtre catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $burgers = $query->paginate(9)->withQueryString();
        $categories = Category::all();

        return view('catalogue.index', compact('burgers', 'categories'));
    }

    public function show(Burger $burger)
    {
        if ($burger->is_archived || $burger->stock <= 0) {
            abort(404);
        }

        $burger->load('category');

        return view('catalogue.show', compact('burger'));
    }
}
