<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 mb-0 fw-bold">Détails du burger</h1>
            <a href="{{ route('burgers.index') }}" class="btn btn-light border rounded-pill px-3">Retour liste</a>
        </div>
    </x-slot>

    <div class="app-section-card p-3 p-md-4">
        <div class="row g-4 align-items-start">
            <div class="col-md-5">
                @if($burger->image && $burger->image !== '0')
                    <img src="{{ route('burgers.image', $burger) }}?v={{ optional($burger->updated_at)->timestamp }}" class="img-fluid rounded-4 border shadow-sm w-100" style="max-height: 360px; object-fit: cover;" alt="{{ $burger->nom }}">
                @else
                    <div class="border rounded-4 p-5 text-center text-secondary h-100 d-flex align-items-center justify-content-center">Pas d'image</div>
                @endif
            </div>

            <div class="col-md-7">
                <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                    <h2 class="h3 fw-bold mb-0">{{ $burger->nom }}</h2>
                    <span class="badge text-bg-light border">{{ $burger->category->nom ?? 'Sans catégorie' }}</span>
                </div>

                <p class="text-secondary mb-3">{{ $burger->description ?: 'Aucune description disponible pour ce burger.' }}</p>

                <div class="row g-2 mb-4">
                    <div class="col-sm-6">
                        <div class="border rounded-3 p-3 bg-light-subtle">
                            <p class="small text-uppercase fw-bold text-secondary mb-1">Prix</p>
                            <p class="h4 fw-bold mb-0" style="color:#bf4600;">{{ number_format($burger->prix, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="border rounded-3 p-3 bg-light-subtle">
                            <p class="small text-uppercase fw-bold text-secondary mb-1">Stock</p>
                            @if($burger->stock > 0)
                                <p class="h4 fw-bold text-success mb-0">{{ $burger->stock }}</p>
                            @else
                                <p class="h5 fw-bold text-danger mb-0">Rupture</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('burgers.edit', $burger) }}" class="btn btn-outline-primary">Modifier</a>

                    <form method="POST" action="{{ route('burgers.archive', $burger) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-warning" onclick="return confirm('Archiver ce burger ?')">Archiver</button>
                    </form>

                    <form method="POST" action="{{ route('burgers.destroy', $burger) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Supprimer définitivement ce burger ? Cette action est irréversible.')">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
