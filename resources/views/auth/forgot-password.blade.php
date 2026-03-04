<x-guest-layout>
    <h2>Mot de passe oublié</h2>
    <p class="auth-subtitle">Indiquez votre e-mail, on vous envoie un lien de réinitialisation immédiatement.</p>

    <x-auth-session-status class="auth-flash auth-flash-success" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="d-grid gap-3">
        @csrf

        <div>
            <label for="email" class="auth-label">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control auth-input">
            @if ($errors->has('email'))
                <div class="auth-inline-error">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <button type="submit" class="auth-btn w-100">Envoyer le lien</button>

        <p class="mb-0 text-center small text-secondary">
            Vous vous souvenez de votre mot de passe ?
            <a class="auth-link" href="{{ route('login') }}">Retour à la connexion</a>
        </p>
    </form>
</x-guest-layout>
