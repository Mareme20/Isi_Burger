<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 mb-0 fw-bold">Detail du burger</h1>
            <a href="{{ route('catalogue.index') }}" class="btn btn-light border rounded-pill px-3">Retour catalogue</a>
        </div>
    </x-slot>

    <style>
        .cat-show-layout {
            display: block;
        }

        .cat-show-card {
            border: 1px solid #efd5b7;
            border-radius: 1rem;
            background: #fffaf5;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
        }

        .cat-cart-fab {
            position: fixed;
            right: 1.1rem;
            bottom: 1.1rem;
            z-index: 1050;
            width: 64px;
            height: 64px;
            border: 0;
            border-radius: 999px;
            background: linear-gradient(135deg, #2f1a12, #e85d04);
            color: #fff;
            box-shadow: 0 18px 40px rgba(82, 31, 12, .28);
        }

        .cat-cart-badge {
            position: absolute;
            top: -4px;
            right: -2px;
            min-width: 26px;
            height: 26px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #fff7ed;
            color: #bf4600;
            font-size: .76rem;
            font-weight: 800;
            border: 2px solid #ffd4aa;
        }

        .cat-cart-panel {
            position: fixed;
            right: 1rem;
            bottom: 5.9rem;
            z-index: 1045;
            width: min(420px, calc(100vw - 1.5rem));
            max-height: min(78vh, 720px);
            overflow: auto;
        }

        @media (max-width: 991.98px) {
            .cat-cart-panel {
                right: .75rem;
                left: .75rem;
                width: auto;
                bottom: 5.6rem;
            }
        }
    </style>

    <section class="cat-show-layout">
        <div class="cat-show-card p-3 p-md-4">
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

                    <form method="POST" action="{{ route('panier.add', $burger) }}" class="d-flex gap-2 align-items-end flex-wrap" data-panier-form>
                        @csrf
                        <div>
                            <label for="quantite" class="form-label fw-semibold">Quantite</label>
                            <input id="quantite" type="number" name="quantite" min="1" max="{{ $burger->stock }}" value="{{ old('quantite', 1) }}" class="form-control" style="max-width:120px;">
                        </div>
                        <button type="submit" class="auth-btn">Ajouter au panier</button>
                    </form>

                    <p class="text-secondary small mt-3 mb-0">Votre panier reste visible quand vous revenez sur le catalogue ou cette fiche produit.</p>
                </div>
            </div>
        </div>
    </section>

    <div x-data="{ open: false }" class="cat-cart-ui">
        <button type="button" class="cat-cart-fab" @click="open = !open" aria-label="Afficher le panier">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .49.402L2.89 3H14.5a.5.5 0 0 1 .49.598l-1.5 7A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.49-.402L1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 6h8.183l1.286-6zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m5 2a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
            </svg>
            <span class="cat-cart-badge" data-panier-count>{{ $panier['count'] }}</span>
        </button>

        <div class="cat-cart-panel" x-show="open" x-transition.opacity x-cloak @click.outside="open = false">
            <div id="panier-panel-content">
                @include('catalogue._panier')
            </div>
        </div>
    </div>
</x-app-layout>
