<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBurgerRequest;
use App\Http\Requests\UpdateBurgerRequest;
use App\Models\Burger;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
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

    return view('burgers.index', compact('burgers'));
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
        $imagePath = $request->file('image')->store('burgers', 'public');
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

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('burgers', 'public');
    } else {
        $validated['image'] = $burger->image;
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
