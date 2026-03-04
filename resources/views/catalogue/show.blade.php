<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 mb-0 fw-bold">Detail du burger</h1>
            <a href="{{ route('catalogue.index') }}" class="btn btn-light border rounded-pill px-3">Retour catalogue</a>
        </div>
    </x-slot>

    <div class="app-section-card p-3 p-md-4">
        <div class="row g-3 align-items-start">
            <div class="col-md-5">
                <div class="rounded-4 border overflow-hidden" style="height: 320px; background: #fff3e3;">
                    @if($burger->image && $burger->image !== '0')
                        <img src="{{ route('burgers.image', $burger) }}?v={{ optional($burger->updated_at)->timestamp }}" alt="{{ $burger->nom }}" class="w-100 h-100" style="object-fit: cover;">
                    @else
                        <div class="h-100 d-flex align-items-center justify-content-center text-secondary">Pas d'image</div>
                    @endif
                </div>
            </div>

            <div class="col-md-7">
                <span class="badge text-bg-light border mb-2">{{ $burger->category->nom }}</span>
                <h2 class="h3 fw-bold mb-2">{{ $burger->nom }}</h2>
                <p class="text-secondary mb-3">{{ $burger->description ?: 'Aucune description disponible.' }}</p>

                <div class="d-flex flex-wrap gap-3 mb-3">
                    <div>
                        <p class="small text-uppercase text-secondary fw-bold mb-1">Prix</p>
                        <p class="h4 fw-bold mb-0" style="color:#bf4600;">{{ number_format($burger->prix, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div>
                        <p class="small text-uppercase text-secondary fw-bold mb-1">Stock</p>
                        <p class="h5 fw-bold mb-0">{{ $burger->stock }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('commandes.store') }}" class="d-flex gap-2 align-items-end flex-wrap">
                    @csrf
                    <input type="hidden" name="burger_id" value="{{ $burger->id }}">
                    <div>
                        <label for="quantite" class="form-label fw-semibold">Quantite</label>
                        <input id="quantite" type="number" name="quantite" min="1" max="{{ $burger->stock }}" value="{{ old('quantite', 1) }}" class="form-control" style="max-width:120px;">
                    </div>
                    <button class="auth-btn">Commander</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
