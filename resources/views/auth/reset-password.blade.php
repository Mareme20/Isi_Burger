<x-guest-layout>
    <h2>Réinitialisation du mot de passe</h2>
    <p class="auth-subtitle">Choisissez un nouveau mot de passe sécurisé pour votre compte.</p>

    <form method="POST" action="{{ route('password.store') }}" class="d-grid gap-3">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div>
            <label for="email" class="auth-label">Adresse e-mail</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="form-control auth-input">
            @if ($errors->has('email'))
                <div class="auth-inline-error">{{ $errors->first('email') }}</div>
            @endif
        </div>

        <div>
            <label for="password" class="auth-label">Nouveau mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" class="form-control auth-input">
            @if ($errors->has('password'))
                <div class="auth-inline-error">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <div>
            <label for="password_confirmation" class="auth-label">Confirmez le mot de passe</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" class="form-control auth-input">
            @if ($errors->has('password_confirmation'))
                <div class="auth-inline-error">{{ $errors->first('password_confirmation') }}</div>
            @endif
        </div>

        <button type="submit" class="auth-btn w-100">Mettre à jour</button>
    </form>
</x-guest-layout>
