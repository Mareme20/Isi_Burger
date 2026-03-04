<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 mb-0 fw-bold">Catalogue des burgers</h1>
            <a href="{{ route('commandes.mes') }}" class="btn btn-light border rounded-pill px-3">Mes commandes</a>
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

        .cat-stock-ok,
        .cat-stock-ko {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 700;
            padding: .32rem .58rem;
        }

        .cat-stock-ok {
            color: #166534;
            background: #dcfce7;
            border: 1px solid #b7eac7;
        }

        .cat-stock-ko {
            color: #991b1b;
            background: #fee2e2;
            border: 1px solid #f4c0c0;
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

        .cat-btn:disabled {
            background: #dc2626;
            box-shadow: none;
            opacity: .7;
        }
    </style>

    @php
        $totalBurgers = method_exists($burgers, 'total') ? $burgers->total() : $burgers->count();
    @endphp

    <section class="cat-hero mb-3">
        <h2 class="cat-hero-title">Des recettes genereuses, preparees minute</h2>
        <p class="cat-hero-text">Filtrez votre burger ideal, choisissez la quantite, puis commandez en un clic. {{ $totalBurgers }} burger(s) disponible(s) actuellement.</p>
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

    <form method="POST" action="{{ route('commandes.store') }}" class="d-grid gap-3">
        @csrf

        @if($errors->has('items'))
            <div class="auth-flash auth-flash-error mb-0">{{ $errors->first('items') }}</div>
        @endif

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <p class="small text-secondary mb-0">Definissez la quantite pour chaque burger (0 = non selectionne).</p>
            <button class="cat-btn">Passer la commande</button>
        </div>

        <section class="row g-3">
            @forelse($burgers as $burger)
                <div class="col-sm-6 col-xl-4">
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
                                @if($burger->stock > 0)
                                    <span class="cat-stock-ok">Stock: {{ $burger->stock }}</span>
                                @else
                                    <span class="cat-stock-ko">Rupture</span>
                                @endif
                            </div>

                            <div class="mt-auto d-grid gap-2">
                                <a href="{{ route('catalogue.show', $burger) }}" class="btn btn-light border rounded-3">Voir details</a>
                                <input
                                    type="number"
                                    name="items[{{ $burger->id }}]"
                                    min="0"
                                    max="{{ $burger->stock }}"
                                    value="{{ old('items.'.$burger->id, 0) }}"
                                    class="form-control cat-qty w-100"
                                    @disabled($burger->stock <= 0)
                                >
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
    </form>

    <div class="mt-3">
        {{ $burgers->links() }}
    </div>
</x-app-layout>
