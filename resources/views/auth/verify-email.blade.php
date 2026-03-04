<x-guest-layout>
    <h2>Vérification de votre e-mail</h2>
    <p class="auth-subtitle">Cliquez sur le lien reçu par e-mail. Vous pouvez aussi en demander un nouveau.</p>

    @if (session('status') == 'verification-link-sent')
        <div class="auth-flash auth-flash-success">
            Un nouveau lien de vérification vient d'être envoyé.
        </div>
    @endif

    <div class="d-grid gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="auth-btn w-100">Renvoyer l'e-mail</button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="text-center">
            @csrf
            <button type="submit" class="btn btn-link auth-link text-decoration-none">Se déconnecter</button>
        </form>
    </div>
</x-guest-layout>
