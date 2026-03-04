<x-app-layout>
    <x-slot name="header">
        <div>
            <h1 class="h4 mb-1 fw-bold">Mon profil</h1>
            <p class="mb-0 text-secondary small">Mettez a jour vos informations, votre mot de passe et la securite du compte.</p>
        </div>
    </x-slot>

    <div class="row g-3">
        <div class="col-12">
            <div class="app-section-card p-3 p-md-4">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="app-section-card p-3 p-md-4 h-100">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="app-section-card p-3 p-md-4 h-100">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
