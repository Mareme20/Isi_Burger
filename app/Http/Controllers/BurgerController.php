<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBurgerRequest;
use App\Http\Requests\UpdateBurgerRequest;
use App\Models\Burger;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BurgerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $burgers = Burger::with('category')
        ->where('is_archived', false)
        ->latest()
        ->paginate(10);

    $stockStats = [
        'total' => Burger::where('is_archived', false)->count(),
        'low' => Burger::where('is_archived', false)
            ->whereBetween('stock', [1, Burger::LOW_STOCK_THRESHOLD])
            ->count(),
        'out' => Burger::where('is_archived', false)
            ->where('stock', '<=', 0)
            ->count(),
    ];

    return view('burgers.index', compact('burgers', 'stockStats'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $categories = Category::all();
    return view('burgers.create', compact('categories'));
}

    /**
     * Store a newly created resource in storage.
     */
  public function store(StoreBurgerRequest $request)
{
    $validated = $request->validated();

    $imagePath = null;

    if ($request->hasFile('image')) {
        $stored = $request->file('image')->store('burgers', 'public');
        if (! is_string($stored) || $stored === '') {
            return back()
                ->withInput()
                ->withErrors(['image' => "L'image n'a pas pu etre enregistree sur le serveur."]);
        }
        $imagePath = $stored;
    }

    Burger::create([
        'nom' => $validated['nom'],
        'prix' => $validated['prix'],
        'description' => $validated['description'] ?? null,
        'image' => $imagePath,
        'stock' => $validated['stock'],
        'category_id' => $validated['category_id'],
    ]);

    return redirect()->route('burgers.index')
        ->with('success', 'Burger ajouté avec succès.');
}

    /**
     * Display the specified resource.
     */
    public function show(Burger $burger): View
    {
        return view('burgers.show', compact('burger'));
    }

    public function image(Burger $burger)
    {
        if (! $burger->image || $burger->image === '0') {
            abort(404);
        }

        $path = storage_path('app/public/' . ltrim($burger->image, '/'));
        if (! File::exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }

    /**
     * Show the form for editing the specified resource.
     */
 public function edit(Burger $burger)
{
    $categories = Category::all();
    return view('burgers.edit', compact('burger', 'categories'));
}

    /**
     * Update the specified resource in storage.
     */
 public function update(UpdateBurgerRequest $request, Burger $burger)
{
    $validated = $request->validated();
    $currentImage = (is_string($burger->image) && $burger->image !== '0') ? $burger->image : null;

    if ($request->hasFile('image')) {
        $stored = $request->file('image')->store('burgers', 'public');
        if (! is_string($stored) || $stored === '') {
            return back()
                ->withInput()
                ->withErrors(['image' => "L'image n'a pas pu etre enregistree sur le serveur."]);
        }
        $validated['image'] = $stored;
        if ($currentImage) {
            Storage::disk('public')->delete($currentImage);
        }
    } else {
        $validated['image'] = $currentImage;
    }

    $burger->update($validated);

    return redirect()->route('burgers.index')
        ->with('success', 'Burger modifié avec succès.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Burger $burger): RedirectResponse
{
    $burger->delete();

    return redirect()->route('burgers.index')
        ->with('success', 'Burger supprimé définitivement.');
}

    public function archive(Burger $burger): RedirectResponse
{
    $burger->update(['is_archived' => true]);

    return redirect()->route('burgers.index')
        ->with('success', 'Burger archivé.');
}
}
