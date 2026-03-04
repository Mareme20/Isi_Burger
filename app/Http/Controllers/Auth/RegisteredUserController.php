<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

public function store(RegisterRequest $request): RedirectResponse
{
    $validated = $request->validated();
    $role = $validated['role'] ?? 'client';

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
    ]);

    Role::findOrCreate($role, 'web');
    $user->assignRole($role);

    event(new Registered($user));

    Auth::login($user);

    if ($role === 'gestionnaire') {
        return redirect()->route('admin.dashboard');
    }

    return redirect(RouteServiceProvider::HOME);
}

}
