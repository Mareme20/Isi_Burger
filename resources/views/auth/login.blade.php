<x-guest-layout>
    <h2>Connexion</h2>
    <p class="auth-subtitle">Retrouvez vos commandes, vos favoris et l'historique de vos paiements.</p>

    <x-auth-session-status class="auth-flash auth-flash-success" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="d-grid gap-3">
        @csrf

        <div>
            <label for="email" class="auth-label">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" class="form-control auth-input">
            @if ($errors->has('email'))
                <div class="auth-inline-error">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div>
            <label for="password" class="auth-label">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control auth-input">
            @if ($errors->has('password'))
                <div class="auth-inline-error">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="form-check">
                <input id="remember_me" type="checkbox" class="form-check-input auth-check" name="remember">
                <label for="remember_me" class="form-check-label">Se souvenir de moi</label>
            </div>

            @if (Route::has('password.request'))
                <a class="auth-link small" href="{{ route('password.request') }}">Mot de passe oublié ?</a>
            @endif
        </div>

        <button type="submit" class="auth-btn w-100">Se connecter</button>

        <p class="mb-0 text-center small text-secondary">
            Pas encore de compte ?
            <a class="auth-link" href="{{ route('register') }}">Créer un compte</a>
        </p>
    </form>
</x-guest-layout>
