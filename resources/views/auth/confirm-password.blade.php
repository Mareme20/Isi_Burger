<x-guest-layout>
    <h2>Confirmation requise</h2>
    <p class="auth-subtitle">Pour continuer, confirmez votre mot de passe actuel.</p>

    <form method="POST" action="{{ route('password.confirm') }}" class="d-grid gap-3">
        @csrf

        <div>
            <label for="password" class="auth-label">Mot de passe</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="form-control auth-input">
            @if ($errors->has('password'))
                <div class="auth-inline-error">{{ $errors->first('password') }}</div>
            @endif
        </div>

        <button type="submit" class="auth-btn w-100">Confirmer</button>
    </form>
</x-guest-layout>
