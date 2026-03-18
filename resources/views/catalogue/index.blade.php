<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 mb-0 fw-bold">Catalogue des burgers</h1>
            @auth
                <a href="{{ route('commandes.mes') }}" class="btn btn-light border rounded-pill px-3">Mes commandes</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-light border rounded-pill px-3">Se connecter pour commander</a>
            @endauth
        </div>
    </x-slot>

    <style>
        .cat-hero {
            border: 1px solid #f0cfac;
            border-radius: 1.2rem;
            padding: 1.2rem;
            background:
                radial-gradient(circle at 12% 18%, #fff2df 0, #fff2df 14%, transparent 40%),
                linear-gradient(120deg, #fff8ef, #ffe4c4 50%, #ffd09d);
            box-shadow: 0 12px 24px rgba(82, 31, 12, .08);
        }

        .cat-hero-title {
            margin: 0;
            font-size: clamp(1.3rem, 2.6vw, 2rem);
            font-weight: 800;
            color: #2f1a12;
        }

        .cat-hero-text {
            margin: .5rem 0 0;
            color: #744d3f;
            max-width: 60ch;
        }

        .cat-filter-box {
            border: 1px solid #f0d6ba;
            border-radius: 1rem;
            background: #fff9f2;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
        }

        .cat-input,
        .cat-select {
            border: 1px solid #e8c6a7;
            border-radius: .85rem;
            background: #fff;
            padding: .64rem .85rem;
        }

        .cat-card {
            border: 1px solid #efd3b5;
            border-radius: 1rem;
            background: #fffaf5;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
            transition: transform .2s ease, box-shadow .2s ease;
        }

        .cat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 28px rgba(82, 31, 12, .12);
        }

        .cat-media {
            height: 220px;
            background: linear-gradient(160deg, #fff2df, #ffe0be);
        }

        .cat-pill {
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: .04em;
            padding: .32rem .6rem;
            border: 1px solid #f2d3b0;
            background: #fff7ed;
            color: #704736;
        }

        .cat-price {
            color: #bf4600;
            font-size: 1.25rem;
            font-weight: 800;
        }

        .cat-stock-ok {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 700;
            padding: .32rem .58rem;
            color: #166534;
            background: #dcfce7;
            border: 1px solid #b7eac7;
        }

        .cat-qty {
            max-width: 90px;
            border-radius: .75rem;
        }

        .cat-btn {
            border: 0;
            border-radius: .82rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .78rem;
            color: #fff;
            background: linear-gradient(90deg, #e85d04, #ff8f2b);
            padding: .72rem 1rem;
            box-shadow: 0 10px 20px rgba(200, 78, 9, .22);
        }

        .cat-layout {
            display: block;
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

        .cat-cart-card {
            border: 1px solid #efd3b5;
            border-radius: 1rem;
            background: #fffaf5;
            padding: 1rem;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
        }

        .cat-cart-item {
            border: 1px solid #f1dcc5;
            border-radius: .95rem;
            padding: .85rem;
            background: #fff;
        }

        .cat-cart-total {
            border-top: 1px dashed #dfc1a0;
            padding-top: .85rem;
            font-size: 1.02rem;
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

    @php
        $totalBurgers = method_exists($burgers, 'total') ? $burgers->total() : $burgers->count();
    @endphp

    <section class="cat-hero mb-3">
        <h2 class="cat-hero-title">Des recettes genereuses, preparees minute</h2>
        <p class="cat-hero-text">Ajoutez vos burgers au panier, ajustez les quantites, supprimez un article si besoin et retrouvez votre resume de commande meme apres avoir change de page. {{ $totalBurgers }} burger(s) disponible(s) actuellement.</p>
    </section>

    <section class="cat-filter-box p-3 p-md-4 mb-3">
        <form method="GET" action="{{ route('catalogue.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label for="nom" class="form-label fw-semibold">Nom du burger</label>
                <input id="nom" name="nom" value="{{ request('nom') }}" class="form-control cat-input" placeholder="Ex: Double Cheese">
            </div>

            <div class="col-md-3">
                <label for="prix_max" class="form-label fw-semibold">Prix max (FCFA)</label>
                <input id="prix_max" type="number" name="prix_max" value="{{ request('prix_max') }}" class="form-control cat-input" placeholder="Ex: 5000">
            </div>

            <div class="col-md-3">
                <label for="category_id" class="form-label fw-semibold">Categorie</label>
                <select id="category_id" name="category_id" class="form-select cat-select">
                    <option value="">Toutes</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected(request('category_id') == $category->id)>{{ $category->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-grid gap-2">
                <button class="cat-btn">Filtrer</button>
                <a href="{{ route('catalogue.index') }}" class="btn btn-light border rounded-3">Reset</a>
            </div>
        </form>
    </section>

    <section class="cat-layout">
        <section class="row g-3">
            @forelse($burgers as $burger)
                <div class="col-sm-6 col-xl-6">
                    <article class="cat-card h-100 d-flex flex-column">
                        <div class="cat-media d-flex align-items-center justify-content-center overflow-hidden">
                            @if($burger->image && $burger->image !== '0')
                                <img src="{{ route('burgers.image', $burger) }}?v={{ optional($burger->updated_at)->timestamp }}" class="w-100 h-100" style="object-fit:cover" alt="{{ $burger->nom }}">
                            @else
                                <span class="text-secondary">Pas d'image</span>
                            @endif
                        </div>

                        <div class="p-3 d-flex flex-column h-100">
                            <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                                <h3 class="h5 mb-0 fw-bold">{{ $burger->nom }}</h3>
                                <span class="cat-pill">{{ $burger->category->nom }}</span>
                            </div>

                            @if($burger->description)
                                <p class="text-secondary small mb-2">{{ \Illuminate\Support\Str::limit($burger->description, 90) }}</p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="cat-price">{{ number_format($burger->prix, 0, ',', ' ') }} FCFA</span>
                                <span class="cat-stock-ok">Stock: {{ $burger->stock }}</span>
                            </div>

                            <div class="mt-auto d-grid gap-2">
                                <a href="{{ route('catalogue.show', $burger) }}" class="btn btn-light border rounded-3">Voir details</a>

                                <form method="POST" action="{{ route('panier.add', $burger) }}" class="d-flex gap-2 align-items-end" data-panier-form>
                                    @csrf
                                    <div class="flex-grow-1">
                                        <label for="quantite_{{ $burger->id }}" class="form-label fw-semibold small">Quantite</label>
                                        <input
                                            id="quantite_{{ $burger->id }}"
                                            type="number"
                                            name="quantite"
                                            min="1"
                                            max="{{ $burger->stock }}"
                                            value="1"
                                            class="form-control cat-qty w-100"
                                        >
                                    </div>
                                    <button type="submit" class="cat-btn">Ajouter</button>
                                </form>
                            </div>
                        </div>
                    </article>
                </div>
            @empty
                <div class="col-12">
                    <div class="auth-flash auth-flash-error mb-0">Aucun burger ne correspond a votre recherche.</div>
                </div>
            @endforelse
        </section>
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

    <div class="mt-3">
        {{ $burgers->links() }}
    </div>
</x-app-layout>
