<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h1 class="h4 mb-0 fw-bold">Details de la commande #{{ $commande->id }}</h1>
            <a href="{{ route('admin.commandes.index') }}" class="btn btn-light border rounded-pill px-3">Retour a la liste</a>
        </div>
    </x-slot>

    <style>
        .show-hero {
            border: 1px solid #f0cfac;
            border-radius: 1.2rem;
            padding: 1.1rem;
            background:
                radial-gradient(circle at 10% 15%, #fff2df 0, #fff2df 14%, transparent 40%),
                linear-gradient(130deg, #fff8ef, #ffe4c4 52%, #ffd09d);
            box-shadow: 0 12px 24px rgba(82, 31, 12, .08);
        }

        .show-sub {
            color: #744d3f;
            margin: .4rem 0 0;
            max-width: 60ch;
        }

        .show-card {
            border: 1px solid #efd5b7;
            border-radius: 1rem;
            background: #fffaf5;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
        }

        .show-label {
            color: #6d4a3d;
            font-size: .82rem;
            text-transform: uppercase;
            letter-spacing: .07em;
            font-weight: 800;
            margin-bottom: .15rem;
        }

        .show-value {
            font-weight: 700;
            margin-bottom: .7rem;
            color: #31170f;
        }

        .show-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            padding: .35rem .62rem;
            border: 1px solid transparent;
        }

        .show-ok { color: #166534; background: #dcfce7; border-color: #bcead0; }
        .show-no { color: #991b1b; background: #fee2e2; border-color: #f4c0c0; }

        .show-table-wrap {
            border: 1px solid #efd5b7;
            border-radius: 1rem;
            background: #fffaf5;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
        }

        .show-total {
            color: #bf4600;
            font-weight: 800;
        }
    </style>

    @php
        $nextStatut = match($commande->statut) {
            'en_attente' => 'en_preparation',
            'en_preparation' => 'prete',
            default => null,
        };
    @endphp

    <section class="show-hero mb-3">
        <h2 class="h5 fw-bold mb-1">Fiche detaillee de commande</h2>
        <p class="show-sub">Consultez les informations client, les lignes de commande et finalisez l'encaissement si necessaire.</p>
    </section>

    <section class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="show-card p-3 h-100">
                <h3 class="h6 fw-bold mb-3">Informations client</h3>

                <p class="show-label">Nom</p>
                <p class="show-value">{{ $commande->user->name }}</p>

                <p class="show-label">Email</p>
                <p class="show-value">{{ $commande->user->email }}</p>

                <p class="show-label">Date</p>
                <p class="show-value mb-0">{{ $commande->created_at->format('d/m/Y H:i') }}</p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="show-card p-3 h-100">
                <h3 class="h6 fw-bold mb-3">Statut et paiement</h3>

                <p class="show-label">Statut</p>
                <p class="show-value text-capitalize">{{ str_replace('_', ' ', $commande->statut) }}</p>

                <div class="d-flex flex-wrap gap-2 mb-3">
                    @if($nextStatut)
                        <form action="{{ route('admin.commandes.updateStatut', $commande) }}" method="POST">
                            @csrf
                            <input type="hidden" name="statut" value="{{ $nextStatut }}">
                            <button type="submit" class="btn btn-outline-dark btn-sm">
                                Passer a {{ str_replace('_', ' ', $nextStatut) }}
                            </button>
                        </form>

                        <form action="{{ route('admin.commandes.updateStatut', $commande) }}" method="POST">
                            @csrf
                            <input type="hidden" name="statut" value="annulee">
                            <button type="submit" class="btn btn-outline-danger btn-sm">Annuler</button>
                        </form>
                    @elseif($commande->statut === 'annulee')
                        <span class="show-pill show-no">Statut verrouille</span>
                    @else
                        <span class="show-pill show-ok">Statut final</span>
                    @endif
                </div>

                <p class="show-label">Paiement</p>
                <p class="mb-0">
                    @if($commande->is_paid)
                        <span class="show-pill show-ok">Payee</span>
                    @else
                        <span class="show-pill show-no">En attente</span>
                    @endif
                </p>
            </div>
        </div>
    </section>

    <section class="show-table-wrap p-2 p-md-3 mb-3">
        <h3 class="h6 fw-bold px-1">Articles commandes</h3>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Burger</th>
                        <th>Prix unitaire</th>
                        <th>Quantite</th>
                        <th>Sous-total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($commande->burgers as $burger)
                        <tr>
                            <td class="fw-semibold">{{ $burger->nom }}</td>
                            <td>{{ number_format($burger->pivot->prix_unitaire, 0, ',', ' ') }} FCFA</td>
                            <td>x {{ $burger->pivot->quantite }}</td>
                            <td class="show-total">{{ number_format($burger->pivot->prix_unitaire * $burger->pivot->quantite, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">TOTAL</td>
                        <td class="show-total">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>

    <div class="d-flex justify-content-between flex-wrap gap-2">
        <a href="{{ route('admin.commandes.index') }}" class="btn btn-light border">Retour</a>
        @if(!$commande->is_paid && $commande->statut !== 'annulee')
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#payerCommandeDetailModal">
                Enregistrer le paiement
            </button>
        @endif
    </div>

    @if(!$commande->is_paid && $commande->statut !== 'annulee')
        <div class="modal fade" id="payerCommandeDetailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title fs-5">Encaisser la commande #{{ $commande->id }}</h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <form action="{{ route('admin.commandes.payer', $commande) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="mb-2">Total attendu: <strong>{{ number_format($commande->total, 0, ',', ' ') }} FCFA</strong></p>
                            <label for="montant_detail" class="form-label fw-semibold">Montant a payer</label>
                            <input
                                id="montant_detail"
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
</x-app-layout>
