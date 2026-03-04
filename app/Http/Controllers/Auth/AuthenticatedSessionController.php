<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // L'import doit être UNIQUEMENT ici
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if (! $user->hasAnyRole(['gestionnaire', 'client'])) {
                Role::findOrCreate('client', 'web');
                $user->assignRole('client');
            }

            // Redirection selon le rôle Spatie
            if ($user->hasRole('gestionnaire')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('client')) {
                return redirect()->route('catalogue.index');
            } 
            
            return redirect(RouteServiceProvider::HOME);
        }

        return back()->withErrors([
            'email' => 'Identifiants invalides.',
        ]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
