<x-guest-layout>
    <h2>Créer un compte</h2>
    <p class="auth-subtitle">Inscrivez-vous pour commander plus vite et suivre toutes vos livraisons.</p>

    <form method="POST" action="{{ route('register') }}" class="d-grid gap-3">
        @csrf

        <div>
            <label for="name" class="auth-label">Nom complet</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" class="form-control auth-input">
            @if ($errors->has('name'))
                <div class="auth-inline-error">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div>
            <label for="email" class="auth-label">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" class="form-control auth-input">
            @if ($errors->has('email'))
                <div class="auth-inline-error">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div>
            <label for="password" class="auth-label">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control auth-input">
            @if ($errors->has('password'))
                <div class="auth-inline-error">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div>
            <label for="password_confirmation" class="auth-label">Confirmer le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control auth-input">
            @if ($errors->has('password_confirmation'))
                <div class="auth-inline-error">{{ $errors->first('password_confirmation') }}</div>
            @endif
        </div>

        <div>
            <label for="role" class="auth-label">Type de compte</label>
            <select id="role" name="role" required class="form-select auth-select">
                <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                <option value="gestionnaire" {{ old('role') == 'gestionnaire' ? 'selected' : '' }}>Gestionnaire</option>
            </select>
            @if ($errors->has('role'))
                <div class="auth-inline-error">{{ $errors->first('role') }}</div>
            @endif
        </div>

        <button type="submit" class="auth-btn w-100">S'inscrire</button>

        <p class="mb-0 text-center small text-secondary">
            Déjà inscrit ?
            <a class="auth-link" href="{{ route('login') }}">Se connecter</a>
        </p>
    </form>
</x-guest-layout>
