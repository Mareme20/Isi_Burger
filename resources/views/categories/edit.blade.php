<x-app-layout>
    <x-slot name="header">
        <h1 class="h4 mb-0 fw-bold">Modifier la categorie</h1>
    </x-slot>

    <div class="app-section-card p-3 p-md-4" style="max-width: 720px;">
        <form method="POST" action="{{ route('categories.update', $category) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="nom" class="form-label fw-semibold">Nom de la categorie</label>
                <input id="nom" name="nom" type="text" value="{{ old('nom', $category->nom) }}" required class="form-control">
                @if($errors->get('nom'))<div class="text-danger small mt-1">{{ $errors->first('nom') }}</div>@endif
            </div>

            <div class="d-flex gap-2">
                <button class="auth-btn">Mettre a jour</button>
                <a href="{{ route('categories.index') }}" class="btn btn-light border">Annuler</a>
            </div>
        </form>
    </div>
</x-app-layout>
