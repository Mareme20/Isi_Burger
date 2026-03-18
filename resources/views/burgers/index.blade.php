<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 mb-0 fw-bold">Gestion des burgers</h1>
            <a href="{{ route('burgers.create') }}" class="btn auth-btn">Ajouter un burger</a>
        </div>
    </x-slot>

    <style>
        .burger-stat {
            border: 1px solid #efd5b7;
            border-radius: 1rem;
            background: linear-gradient(180deg, #fffaf4, #fff4e8);
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
            padding: 1rem;
            height: 100%;
        }

        .burger-stat-title {
            margin: 0 0 .4rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .72rem;
            font-weight: 800;
            color: #7a5242;
        }

        .burger-stat-value {
            margin: 0;
            color: #31170f;
            font-size: 1.8rem;
            font-weight: 900;
            line-height: 1;
        }
    </style>

    <section class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="burger-stat">
                <p class="burger-stat-title">Burgers actifs</p>
                <p class="burger-stat-value">{{ $stockStats['total'] }}</p>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="burger-stat">
                <p class="burger-stat-title">Stock faible</p>
                <p class="burger-stat-value text-warning">{{ $stockStats['low'] }}</p>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="burger-stat">
                <p class="burger-stat-title">Ruptures</p>
                <p class="burger-stat-value text-danger">{{ $stockStats['out'] }}</p>
            </div>
        </div>
    </section>

    <div class="app-section-card p-3 p-md-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>Stock</th>
                        <th>Categorie</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($burgers as $burger)
                        <tr>
                            <td>
                                @if($burger->image && $burger->image !== '0')
                                    <img src="{{ route('burgers.image', $burger) }}?v={{ optional($burger->updated_at)->timestamp }}" class="rounded border" style="width:64px;height:64px;object-fit:cover;" alt="{{ $burger->nom }}">
                                @else
                                    <span class="text-secondary">Pas d'image</span>
                                @endif
                            </td>
                            <td class="fw-semibold">{{ $burger->nom }}</td>
                            <td class="fw-bold">{{ number_format($burger->prix, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($burger->stock <= 0)
                                    <span class="badge text-bg-danger">Rupture</span>
                                @elseif($burger->stock <= \App\Models\Burger::LOW_STOCK_THRESHOLD)
                                    <span class="badge text-bg-warning">{{ $burger->stock }} restant(s)</span>
                                @else
                                    <span class="badge text-bg-success">{{ $burger->stock }}</span>
                                @endif
                            </td>
                            <td>{{ $burger->category->nom ?? 'Sans categorie' }}</td>
                            <td class="text-center">
                                <a href="{{ route('burgers.show', $burger) }}" class="btn btn-outline-dark btn-sm">Voir</a>
                                <a href="{{ route('burgers.edit', $burger) }}" class="btn btn-outline-primary btn-sm">Modifier</a>
                                <form method="POST" action="{{ route('burgers.archive', $burger) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Archiver ce burger ?')">Archiver</button>
                                </form>
                                <form method="POST" action="{{ route('burgers.destroy', $burger) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer définitivement ce burger ? Cette action est irréversible.')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $burgers->links() }}</div>
    </div>
</x-app-layout>
