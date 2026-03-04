<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BurgerController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () { return view('welcome'); });
Route::get('/media/burger/{burger}/image', [BurgerController::class, 'image'])->name('burgers.image');

// --- ROUTES CLIENT ---
Route::middleware(['auth', 'ensure.app.role', 'role:client'])->group(function () {
    Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
    Route::get('/catalogue/{burger}', [CatalogueController::class, 'show'])->name('catalogue.show');
    Route::post('/commandes/store', [CommandeController::class, 'store'])->name('commandes.store');
    Route::get('/mes-commandes', [CommandeController::class, 'mesCommandes'])->name('commandes.mes');
});

// --- ROUTES GESTIONNAIRE ---
Route::middleware(['auth', 'ensure.app.role', 'role:gestionnaire'])->group(function () {
    // Dashboard & Stats AJAX
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/dashboard/commandes-data', [DashboardController::class, 'commandesData']);
    Route::get('/admin/dashboard/produits-data', [DashboardController::class, 'produitsData']);

    // Ressources
    Route::post('/burgers/{burger}/archive', [BurgerController::class, 'archive'])->name('burgers.archive');
    Route::resource('burgers', BurgerController::class);
    Route::resource('categories', CategoryController::class);

    // Commandes
    Route::get('/admin/commandes', [CommandeController::class, 'index'])->name('admin.commandes.index');
    Route::get('/admin/commandes/{commande}', [CommandeController::class, 'show'])->name('admin.commandes.show');
    
    // CORRECTION ICI : Une seule route POST pour le statut
    Route::post('/admin/commandes/{commande}/statut', [CommandeController::class, 'updateStatut'])
        ->name('admin.commandes.updateStatut');

    // Paiement (Encaisser)
    Route::post('/admin/commandes/{commande}/payer', [CommandeController::class, 'enregistrerPaiement'])
        ->name('admin.commandes.payer');
});

// --- ROUTES PROFIL ---
Route::middleware(['auth', 'ensure.app.role'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
