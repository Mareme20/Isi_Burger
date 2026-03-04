<x-app-layout>
    <x-slot name="header">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h1 class="h4 mb-1 fw-bold">Dashboard</h1>
                <p class="mb-0 text-secondary small">Bienvenue {{ auth()->user()->name }}, voici un apercu rapide de votre espace.</p>
            </div>
        </div>
    </x-slot>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-xl-4">
            <div class="app-section-card p-3 h-100">
                <p class="text-uppercase small fw-bold text-secondary mb-2">Statut compte</p>
                <h2 class="h5 mb-1 fw-bold">Connecte</h2>
                <p class="mb-0 text-secondary small">Session active et securisee.</p>
            </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
            <div class="app-section-card p-3 h-100">
                <p class="text-uppercase small fw-bold text-secondary mb-2">Role</p>
                <h2 class="h5 mb-1 fw-bold text-capitalize">{{ auth()->user()->roles->pluck('name')->first() }}</h2>
                <p class="mb-0 text-secondary small">Permissions chargees avec succes.</p>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="app-section-card p-3 h-100">
                <p class="text-uppercase small fw-bold text-secondary mb-2">Acces rapide</p>
                <div class="d-grid gap-2">
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-dark rounded-3">Modifier mon profil</a>
                    @role('gestionnaire')
                        <a href="{{ route('admin.commandes.index') }}" class="btn auth-btn">Voir les commandes</a>
                    @endrole
                    @role('client')
                        <a href="{{ route('catalogue.index') }}" class="btn auth-btn">Voir le catalogue</a>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
