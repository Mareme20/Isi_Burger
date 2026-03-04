<section>
    <header class="mb-3">
        <h2 class="h5 fw-bold mb-1">Informations du profil</h2>
        <p class="text-secondary small mb-0">Mettez a jour votre nom et votre adresse e-mail.</p>
    </header>

    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="POST" action="{{ route('profile.update') }}" class="d-grid gap-3">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="form-label fw-semibold">Nom complet</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
            @if ($errors->get('name'))
                <div class="text-danger small mt-1">{{ $errors->first('name') }}</div>
            @endif
        </div>

        <div>
            <label for="email" class="form-label fw-semibold">Adresse e-mail</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username">
            @if ($errors->get('email'))
                <div class="text-danger small mt-1">{{ $errors->first('email') }}</div>
            @endif

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2 small">
                    <span class="text-secondary">Votre e-mail n'est pas verifie.</span>
                    <button form="send-verification" class="btn btn-link auth-link p-0 align-baseline">Renvoyer le lien de verification</button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <div class="text-success small mt-1 fw-semibold">
                        Nouveau lien envoye avec succes.
                    </div>
                @endif
            @endif
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <button type="submit" class="auth-btn">Enregistrer</button>
            @if (session('status') === 'profile-updated')
                <span class="small text-success fw-semibold">Modifications enregistrees.</span>
            @endif
        </div>
    </form>
</section>
