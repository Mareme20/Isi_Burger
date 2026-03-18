<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 mb-0 fw-bold">Gestion des commandes</h1>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-light border rounded-pill px-3">Retour dashboard</a>
        </div>
    </x-slot>

    <style>
        .adm-hero {
            border: 1px solid #f0cfac;
            border-radius: 1.2rem;
            padding: 1.1rem;
            background:
                radial-gradient(circle at 10% 15%, #fff2df 0, #fff2df 14%, transparent 40%),
                linear-gradient(130deg, #fff8ef, #ffe4c4 52%, #ffd09d);
            box-shadow: 0 12px 24px rgba(82, 31, 12, .08);
        }

        .adm-sub {
            color: #744d3f;
            margin: .4rem 0 0;
            max-width: 60ch;
        }

        .adm-stat {
            border: 1px solid #efd5b7;
            border-radius: .9rem;
            background: #fff8f0;
            padding: .75rem .9rem;
        }

        .adm-table-wrap {
            border: 1px solid #efd5b7;
            border-radius: 1rem;
            background: #fffaf5;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
        }

        .adm-id {
            font-weight: 800;
            color: #472114;
        }

        .adm-products {
            color: #6d4a3d;
            font-size: .9rem;
        }

        .adm-products > div + div {
            margin-top: .2rem;
        }

        .adm-total {
            color: #bf4600;
            font-weight: 800;
            white-space: nowrap;
        }

        .adm-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 800;
            letter-spacing: .04em;
            text-transform: uppercase;
            padding: .34rem .58rem;
            border: 1px solid transparent;
        }

        .adm-ok { color: #166534; background: #dcfce7; border-color: #bcead0; }
        .adm-no { color: #991b1b; background: #fee2e2; border-color: #f4c0c0; }

        .adm-next {
            min-width: 170px;
        }
    </style>

    @php
        $total = method_exists($commandes, 'total') ? $commandes->total() : $commandes->count();
        $paid = $commandes->where('is_paid', true)->count();
        $pending = $commandes->where('is_paid', false)->count();
    @endphp

    <section class="adm-hero mb-3">
        <h2 class="h5 fw-bold mb-1">Pilotage des commandes ISI BURGER</h2>
        <p class="adm-sub">Le gestionnaire fait avancer une commande de en attente a en preparation puis a prete. Si elle est annulee, elle est verrouillee.</p>
    </section>

    <section class="row g-2 mb-3">
        <div class="col-sm-4">
            <div class="adm-stat h-100">
                <p class="text-uppercase small fw-bold text-secondary mb-1">Commandes affichees</p>
                <p class="h4 fw-bold mb-0">{{ $total }}</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="adm-stat h-100">
                <p class="text-uppercase small fw-bold text-secondary mb-1">Payees</p>
                <p class="h4 fw-bold mb-0 text-success">{{ $paid }}</p>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="adm-stat h-100">
                <p class="text-uppercase small fw-bold text-secondary mb-1">Non payees</p>
                <p class="h4 fw-bold mb-0 text-danger">{{ $pending }}</p>
            </div>
        </div>
    </section>

    <section class="adm-table-wrap p-2 p-md-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th># ID</th>
                        <th>Client</th>
                        <th>Produits</th>
                        <th>Total</th>
                        <th>Statut</th>
                        <th>Paiement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($commandes as $commande)
                        @php
                            $nextStatut = match($commande->statut) {
                                'en_attente' => 'en_preparation',
                                'en_preparation' => 'prete',
                                default => null,
                            };
                        @endphp
                        <tr>
                            <td class="adm-id">CMD-{{ $commande->id }}</td>
                            <td>
                                <div class="fw-semibold">{{ $commande->user->name }}</div>
                                <small class="text-secondary">{{ $commande->user->email }}</small>
                            </td>
                            <td class="adm-products">
                                @foreach($commande->burgers as $burger)
                                    <div>{{ $burger->nom }} (x{{ $burger->pivot->quantite }})</div>
                                @endforeach
                            </td>
                            <td class="adm-total">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                            <td>
                                <div class="d-grid gap-2">
                                    <span class="adm-pill {{ $commande->statut === 'annulee' ? 'adm-no' : 'adm-ok' }}">
                                        {{ str_replace('_', ' ', $commande->statut) }}
                                    </span>

                                    @if($nextStatut)
                                        <form action="{{ route('admin.commandes.updateStatut', $commande) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="statut" value="{{ $nextStatut }}">
                                            <button type="submit" class="btn btn-outline-dark btn-sm adm-next">
                                                Passer a {{ str_replace('_', ' ', $nextStatut) }}
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.commandes.updateStatut', $commande) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="statut" value="annulee">
                                            <button type="submit" class="btn btn-outline-danger btn-sm adm-next">Annuler</button>
                                        </form>
                                    @elseif($commande->statut === 'annulee')
                                        <small class="text-danger fw-semibold">Statut verrouille</small>
                                    @else
                                        <small class="text-secondary fw-semibold">Aucune autre modification autorisee</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($commande->is_paid)
                                    <span class="adm-pill adm-ok">Payee</span>
                                @else
                                    <span class="adm-pill adm-no">Non payee</span>
                                    <div class="mt-1">
                                        @if($commande->statut !== 'annulee')
                                            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#payerCommandeModal{{ $commande->id }}">
                                                Encaisser
                                            </button>
                                        @else
                                            <small class="text-danger fw-semibold d-block mt-1">Paiement indisponible</small>
                                        @endif
                                    </div>

                                    @if($commande->statut !== 'annulee')
                                        <div class="modal fade" id="payerCommandeModal{{ $commande->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2 class="modal-title fs-5">Encaisser CMD-{{ $commande->id }}</h2>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                    </div>
                                                    <form action="{{ route('admin.commandes.payer', $commande) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <p class="mb-2">Total attendu: <strong>{{ number_format($commande->total, 0, ',', ' ') }} FCFA</strong></p>
                                                            <label for="montant_{{ $commande->id }}" class="form-label fw-semibold">Montant a payer</label>
                                                            <input
                                                                id="montant_{{ $commande->id }}"
                                                                type="number"
                                                                step="0.01"
                                                                min="0"
                                                                name="montant"
                                                                value="{{ old('montant', $commande->total) }}"
                                                                class="form-control"
                                                                required
                                                            >
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Fermer</button>
                                                            <button type="submit" class="btn btn-success">Confirmer le paiement</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.commandes.show', $commande) }}" class="btn btn-outline-dark btn-sm">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-secondary py-5">Aucune commande a afficher.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $commandes->links() }}
        </div>
    </section>
</x-app-layout>
