<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 mb-0 fw-bold">Mes commandes</h1>
            <a href="{{ route('catalogue.index') }}" class="btn btn-light border rounded-pill px-3">Retour au catalogue</a>
        </div>
    </x-slot>

    <style>
        .ord-hero {
            border: 1px solid #f0cfac;
            border-radius: 1.2rem;
            padding: 1.1rem;
            background:
                radial-gradient(circle at 10% 15%, #fff2df 0, #fff2df 14%, transparent 40%),
                linear-gradient(130deg, #fff8ef, #ffe4c4 52%, #ffd09d);
            box-shadow: 0 12px 24px rgba(82, 31, 12, .08);
        }

        .ord-sub {
            color: #744d3f;
            margin: .4rem 0 0;
            max-width: 60ch;
        }

        .ord-stat {
            border: 1px solid #efd5b7;
            border-radius: .9rem;
            background: #fff8f0;
            padding: .7rem .85rem;
        }

        .ord-table-wrap {
            border: 1px solid #efd5b7;
            border-radius: 1rem;
            background: #fffaf5;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
        }

        .ord-row-id {
            font-weight: 800;
            color: #472114;
        }

        .ord-items {
            margin: 0;
            padding-left: 1rem;
            color: #6d4a3d;
        }

        .ord-items li + li {
            margin-top: .2rem;
        }

        .ord-total {
            color: #bf4600;
            font-size: 1.02rem;
            font-weight: 800;
        }

        .ord-status {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 800;
            letter-spacing: .04em;
            padding: .36rem .62rem;
            border: 1px solid transparent;
            text-transform: uppercase;
        }

        .ord-warn { color: #92400e; background: #fef3c7; border-color: #f8dfa2; }
        .ord-prep { color: #1d4ed8; background: #dbeafe; border-color: #bfd8ff; }
        .ord-ok { color: #166534; background: #dcfce7; border-color: #bcead0; }
        .ord-no { color: #991b1b; background: #fee2e2; border-color: #f4c0c0; }
    </style>

    @php
        $totalCommandes = $commandes->count();
        $totalMontant = $commandes->sum('total');
    @endphp

    <section class="ord-hero mb-3">
        <h2 class="h5 fw-bold mb-1">Suivi de vos commandes ISI BURGER</h2>
        <p class="ord-sub">Consultez rapidement vos commandes, leurs articles, et leur statut de preparation.</p>
    </section>

    <section class="row g-2 mb-3">
        <div class="col-sm-6 col-lg-4">
            <div class="ord-stat h-100">
                <p class="text-uppercase small fw-bold text-secondary mb-1">Commandes</p>
                <p class="h4 fw-bold mb-0">{{ $totalCommandes }}</p>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="ord-stat h-100">
                <p class="text-uppercase small fw-bold text-secondary mb-1">Montant cumule</p>
                <p class="h4 fw-bold mb-0">{{ number_format($totalMontant, 0, ',', ' ') }} FCFA</p>
            </div>
        </div>
    </section>

    <section class="ord-table-wrap p-2 p-md-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>N Commande</th>
                        <th>Date</th>
                        <th>Articles</th>
                        <th>Total</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                        @php
                            $statusClass = [
                                'en_attente' => 'ord-warn',
                                'en_preparation' => 'ord-prep',
                                'prete' => 'ord-ok',
                                'annulee' => 'ord-no',
                                'payee' => 'ord-ok',
                            ][$commande->statut] ?? 'ord-warn';
                        @endphp
                        <tr>
                            <td class="ord-row-id">CMD-{{ $commande->id }}</td>
                            <td>{{ $commande->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <ul class="ord-items">
                                    @foreach($commande->burgers as $burger)
                                        <li>{{ $burger->nom }} (x{{ $burger->pivot->quantite }})</li>
                                    @endforeach
                                </ul>
                            </td>
                            <td class="ord-total">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <span class="ord-status {{ $statusClass }}">{{ str_replace('_', ' ', $commande->statut) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-secondary py-5">
                                <p class="mb-2 fw-semibold">Vous n'avez pas encore passe de commande.</p>
                                <a class="auth-link" href="{{ route('catalogue.index') }}">Voir le catalogue</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</x-app-layout>
