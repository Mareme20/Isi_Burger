<section>
    <header class="mb-3">
        <h2 class="h5 fw-bold mb-1">Mot de passe</h2>
        <p class="text-secondary small mb-0">Choisissez un mot de passe long et difficile a deviner.</p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="d-grid gap-3">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-label fw-semibold">Mot de passe actuel</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password">
            @if ($errors->updatePassword->get('current_password'))
                <div class="text-danger small mt-1">{{ $errors->updatePassword->first('current_password') }}</div>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="form-label fw-semibold">Nouveau mot de passe</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password">
            @if ($errors->updatePassword->get('password'))
                <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password') }}</div>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="form-label fw-semibold">Confirmation</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password">
            @if ($errors->updatePassword->get('password_confirmation'))
                <div class="text-danger small mt-1">{{ $errors->updatePassword->first('password_confirmation') }}</div>
            @endif
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="submit" class="auth-btn">Mettre a jour</button>
            @if (session('status') === 'password-updated')
                <span class="small text-success fw-semibold">Mot de passe mis a jour.</span>
            @endif
        </div>
    </form>
</section>
