<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="h4 mb-1 fw-bold">Gestion des commandes</h1>
                <p class="mb-0 text-secondary small">Un poste de traitement pense pour lire vite, agir vite et prioriser correctement.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.commandes.export.csv', request()->query()) }}" class="btn btn-outline-dark rounded-pill px-3">Export CSV</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light border rounded-pill px-3">Dashboard</a>
            </div>
        </div>
    </x-slot>

    <style>
        .ops-shell {
            display: grid;
            gap: 1rem;
        }

        .ops-hero {
            border: 1px solid #e9c8aa;
            border-radius: 1.4rem;
            padding: 1.2rem;
            background:
                radial-gradient(circle at 12% 16%, rgba(255, 239, 220, .95) 0, rgba(255, 239, 220, .95) 12%, transparent 34%),
                linear-gradient(135deg, #fffaf1 0%, #ffe9cb 55%, #ffc98f 100%);
            box-shadow: 0 18px 34px rgba(72, 28, 11, .10);
        }

        .ops-hero-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.3fr) minmax(260px, .7fr);
            gap: 1rem;
            align-items: start;
        }

        .ops-kpi-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: .75rem;
        }

        .ops-kpi {
            border-radius: 1.05rem;
            padding: .9rem 1rem;
            background: rgba(255, 255, 255, .68);
            border: 1px solid rgba(238, 204, 176, .9);
            backdrop-filter: blur(6px);
        }

        .ops-kpi-title {
            margin: 0 0 .35rem;
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .7rem;
            font-weight: 800;
            color: #895947;
        }

        .ops-kpi-value {
            margin: 0;
            font-size: clamp(1.35rem, 2.6vw, 1.95rem);
            line-height: 1;
            font-weight: 900;
            color: #30170f;
        }

        .ops-feed {
            display: grid;
            gap: .75rem;
        }

        .ops-feed-card {
            border-radius: 1rem;
            padding: .95rem 1rem;
            background: rgba(255, 248, 239, .9);
            border: 1px solid rgba(233, 200, 170, .9);
        }

        .ops-feed-label {
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            margin-bottom: .35rem;
            font-size: .72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #784c3e;
        }

        .ops-feed-body {
            color: #392016;
            font-weight: 700;
        }

        .ops-toolbar {
            position: sticky;
            top: .75rem;
            z-index: 20;
            display: grid;
            gap: .9rem;
        }

        .ops-panel {
            border: 1px solid #ecd3b5;
            border-radius: 1.2rem;
            background: linear-gradient(180deg, #fffdf8, #fff5ea);
            box-shadow: 0 12px 26px rgba(82, 31, 12, .06);
            padding: 1rem;
        }

        .ops-panel-title {
            margin: 0 0 .85rem;
            color: #31170f;
            font-size: 1rem;
            font-weight: 900;
        }

        .ops-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            border-radius: 999px;
            padding: .38rem .72rem;
            font-size: .78rem;
            font-weight: 800;
            border: 1px solid transparent;
        }

        .ops-chip-soft { background: #fff1df; border-color: #f0cfac; color: #744d3f; }
        .ops-chip-warn { background: #fff0c7; border-color: #efcf79; color: #8a5806; }
        .ops-chip-danger { background: #fee2e2; border-color: #f4c0c0; color: #991b1b; }
        .ops-chip-blue { background: #dbeafe; border-color: #c7ddff; color: #1d4ed8; }
        .ops-chip-green { background: #dcfce7; border-color: #bcead0; color: #166534; }

        .ops-filter-grid {
            display: grid;
            grid-template-columns: 1.3fr repeat(3, minmax(0, .8fr)) repeat(2, minmax(110px, .55fr)) minmax(120px, .55fr);
            gap: .75rem;
            align-items: end;
        }

        .ops-list {
            display: grid;
            gap: .95rem;
        }

        .ops-order {
            border: 1px solid #ecd2b3;
            border-radius: 1.35rem;
            background: linear-gradient(180deg, #fffefb, #fff6ed);
            box-shadow: 0 14px 28px rgba(82, 31, 12, .06);
            overflow: hidden;
        }

        .ops-order-top {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr) 230px;
            gap: 1rem;
            padding: 1rem 1rem 0;
            align-items: start;
        }

        .ops-order-main {
            display: grid;
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr) minmax(0, 1fr);
            gap: 1rem;
            padding: 1rem;
        }

        .ops-aside {
            border-radius: 1.1rem;
            background: linear-gradient(180deg, #2f1a12, #4a2417);
            color: #fff7ef;
            padding: 1rem;
        }

        .ops-order-id {
            margin: 0 0 .35rem;
            font-size: 1.2rem;
            font-weight: 900;
        }

        .ops-order-meta {
            color: rgba(255, 247, 239, .82);
            font-size: .88rem;
        }

        .ops-order-total {
            margin-top: .85rem;
            font-size: 1.6rem;
            font-weight: 900;
            line-height: 1;
        }

        .ops-section {
            border-radius: 1rem;
            background: #fff;
            border: 1px solid #f0dcc8;
            padding: .95rem;
        }

        .ops-section-title {
            margin: 0 0 .65rem;
            color: #7a5242;
            font-size: .72rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .09em;
        }

        .ops-name {
            color: #2f1a12;
            font-weight: 900;
        }

        .ops-muted {
            color: #7b5a4d;
            font-size: .84rem;
        }

        .ops-products {
            display: grid;
            gap: .45rem;
        }

        .ops-product {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .7rem;
            border-radius: .85rem;
            background: #fff8f0;
            padding: .5rem .65rem;
        }

        .ops-actions {
            display: grid;
            gap: .55rem;
        }

        .ops-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            width: 100%;
            border-radius: .95rem;
            padding: .8rem .9rem;
            text-decoration: none;
            font-size: .82rem;
            font-weight: 900;
            border: 1px solid transparent;
        }

        .ops-btn-dark { background: linear-gradient(90deg, #2f1a12, #5a2818); color: #fff; }
        .ops-btn-orange { background: linear-gradient(90deg, #e85d04, #ff8f2b); color: #fff; }
        .ops-btn-green { background: #ecfdf5; border-color: #bce8d1; color: #14532d; }
        .ops-btn-red { background: #fff1f2; border-color: #fecdd3; color: #991b1b; }
        .ops-btn-light { background: #fff; border-color: #ecd4b9; color: #3f2a20; }

        .ops-history {
            margin-top: .85rem;
            padding-top: .85rem;
            border-top: 1px dashed #ecd5bb;
        }

        .ops-empty {
            border: 1px dashed #e4c4a3;
            border-radius: 1.2rem;
            background: #fffaf5;
            padding: 2.2rem 1.2rem;
            text-align: center;
            color: #7b5a4d;
            font-weight: 700;
        }

        .ops-toast {
            position: fixed;
            right: 1rem;
            bottom: 1rem;
            z-index: 1080;
            min-width: min(360px, calc(100vw - 2rem));
            border-radius: 1rem;
            border: 1px solid #f0cfac;
            background: #2f1a12;
            color: #fff7ef;
            padding: .9rem 1rem;
            box-shadow: 0 18px 30px rgba(0, 0, 0, .18);
            opacity: 0;
            pointer-events: none;
            transform: translateY(20px);
            transition: opacity .25s ease, transform .25s ease;
        }

        .ops-toast.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        .ops-toast-title {
            font-weight: 900;
            margin-bottom: .2rem;
        }

        @media (max-width: 1399.98px) {
            .ops-hero-grid,
            .ops-order-top,
            .ops-order-main {
                grid-template-columns: 1fr;
            }

            .ops-kpi-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }

            .ops-filter-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (max-width: 991.98px) {
            .ops-kpi-grid,
            .ops-filter-grid {
                grid-template-columns: 1fr;
            }

            .ops-toolbar {
                position: static;
            }
        }
    </style>

    <div class="ops-shell">
        <section class="ops-hero">
            <div class="ops-hero-grid">
                <div>
                    <h2 class="h4 fw-bold mb-2">File de traitement des commandes</h2>
                    <p class="text-secondary mb-3" style="max-width:68ch;">
                        L'ecran est organise pour trier les urgences, lire l'essentiel d'un coup d'oeil et laisser les actions les plus utiles au meme endroit.
                    </p>

                    <div class="ops-kpi-grid">
                        <div class="ops-kpi">
                            <p class="ops-kpi-title">Affichees</p>
                            <p class="ops-kpi-value">{{ $stats['total'] }}</p>
                        </div>
                        <div class="ops-kpi">
                            <p class="ops-kpi-title">Urgentes</p>
                            <p class="ops-kpi-value text-danger">{{ $stats['urgent'] }}</p>
                        </div>
                        <div class="ops-kpi">
                            <p class="ops-kpi-title">Non attribuees</p>
                            <p class="ops-kpi-value text-warning">{{ $stats['unassigned'] }}</p>
                        </div>
                        <div class="ops-kpi">
                            <p class="ops-kpi-title">Payees</p>
                            <p class="ops-kpi-value text-success">{{ $stats['paid'] }}</p>
                        </div>
                        <div class="ops-kpi">
                            <p class="ops-kpi-title">A encaisser</p>
                            <p class="ops-kpi-value text-primary">{{ $stats['pending'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="ops-feed">
                    <section id="liveCommandesBanner" class="ops-feed-card"
                        data-latest-commande-id="{{ $liveSummary['latest_commande_id'] ?? '' }}"
                        data-url="{{ route('admin.commandes.live-summary') }}">
                        <div class="ops-feed-label">Surveillance file</div>
                        <div id="liveCommandesText" class="ops-feed-body">
                            {{ $liveSummary['pending_total'] }} en cours, {{ $liveSummary['unassigned_total'] }} non attribuee(s), {{ $liveSummary['urgent_total'] }} urgente(s), {{ $liveSummary['low_stock_total'] }} stock faible(s).
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <button type="button" id="enableBrowserNotifications" class="btn btn-sm btn-outline-dark">Activer les notifications</button>
                            <button type="button" id="liveCommandesReload" class="btn btn-sm btn-outline-dark d-none">Recharger</button>
                        </div>
                    </section>

                    <section class="ops-feed-card">
                        <div class="ops-feed-label">Surveillance stock</div>
                        <div id="liveStockText" class="ops-feed-body">
                            {{ $liveSummary['low_stock_total'] }} burger(s) bas en stock, {{ $liveSummary['out_of_stock_total'] }} en rupture.
                        </div>
                        <div class="d-flex flex-wrap gap-2 mt-3">
                            <a href="{{ route('burgers.index') }}" class="btn btn-sm btn-outline-dark">Voir le stock</a>
                        </div>
                    </section>
                </div>
            </div>
        </section>

        <section class="ops-panel">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h3 class="ops-panel-title mb-0">Filtres de travail</h3>
                @if(request()->filled('q') || request()->filled('statut') || request()->filled('paiement') || request()->filled('attribution') || request()->filled('date_du') || request()->filled('date_au'))
                    <div class="d-flex flex-wrap gap-2">
                        @if(request('q'))
                            <span class="ops-chip ops-chip-soft">Recherche: {{ request('q') }}</span>
                        @endif
                        @if(request('statut'))
                            <span class="ops-chip ops-chip-soft">Statut: {{ str_replace('_', ' ', request('statut')) }}</span>
                        @endif
                        @if(request('paiement'))
                            <span class="ops-chip ops-chip-soft">Paiement: {{ request('paiement') === 'payee' ? 'Payee' : 'Non payee' }}</span>
                        @endif
                        @if(request('attribution'))
                            <span class="ops-chip ops-chip-soft">Attribution: {{ str_replace('_', ' ', request('attribution')) }}</span>
                        @endif
                    </div>
                @endif
            </div>

            <form method="GET" action="{{ route('admin.commandes.index') }}" class="ops-filter-grid">
                <div>
                    <label for="q" class="form-label fw-semibold">Recherche</label>
                    <input id="q" type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="ID, nom ou email client">
                </div>
                <div>
                    <label for="statut" class="form-label fw-semibold">Statut</label>
                    <select id="statut" name="statut" class="form-select">
                        <option value="">Tous</option>
                        <option value="en_attente" @selected(request('statut') === 'en_attente')>En attente</option>
                        <option value="en_preparation" @selected(request('statut') === 'en_preparation')>En preparation</option>
                        <option value="prete" @selected(request('statut') === 'prete')>Prete</option>
                        <option value="annulee" @selected(request('statut') === 'annulee')>Annulee</option>
                        <option value="payee" @selected(request('statut') === 'payee')>Payee</option>
                    </select>
                </div>
                <div>
                    <label for="paiement" class="form-label fw-semibold">Paiement</label>
                    <select id="paiement" name="paiement" class="form-select">
                        <option value="">Tous</option>
                        <option value="payee" @selected(request('paiement') === 'payee')>Payee</option>
                        <option value="non_payee" @selected(request('paiement') === 'non_payee')>Non payee</option>
                    </select>
                </div>
                <div>
                    <label for="attribution" class="form-label fw-semibold">Attribution</label>
                    <select id="attribution" name="attribution" class="form-select">
                        <option value="">Toutes</option>
                        <option value="moi" @selected(request('attribution') === 'moi')>Mes commandes</option>
                        <option value="non_attribuee" @selected(request('attribution') === 'non_attribuee')>Non attribuees</option>
                        <option value="autres" @selected(request('attribution') === 'autres')>Autres gestionnaires</option>
                    </select>
                </div>
                <div>
                    <label for="date_du" class="form-label fw-semibold">Du</label>
                    <input id="date_du" type="date" name="date_du" value="{{ request('date_du') }}" class="form-control">
                </div>
                <div>
                    <label for="date_au" class="form-label fw-semibold">Au</label>
                    <input id="date_au" type="date" name="date_au" value="{{ request('date_au') }}" class="form-control">
                </div>
                <div class="d-grid gap-2">
                    <button class="btn btn-dark">Filtrer</button>
                    <a href="{{ route('admin.commandes.index') }}" class="btn btn-light border">Reset</a>
                </div>
            </form>
        </section>

        <section class="ops-list">
            @forelse($commandes as $commande)
                @php
                    $nextStatut = match($commande->statut) {
                        'en_attente' => 'en_preparation',
                        'en_preparation' => 'prete',
                        default => null,
                    };

                    $isUrgent = in_array($commande->statut, ['en_attente', 'en_preparation'], true) && $commande->created_at->lte(now()->subMinutes(15));
                    $isLockedByOtherManager = $commande->gestionnaire_id && $commande->gestionnaire_id !== auth()->id();
                @endphp

                <article class="ops-order">
                    <div class="ops-order-top">
                        <aside class="ops-aside">
                            <p class="ops-order-id">CMD-{{ $commande->id }}</p>
                            <div class="ops-order-meta">{{ $commande->created_at->format('d/m/Y H:i') }}</div>
                            <div class="ops-order-meta mt-1">Depuis {{ $commande->created_at->diffForHumans() }}</div>
                            <div class="ops-order-total">{{ number_format($commande->total, 0, ',', ' ') }} FCFA</div>

                            <div class="d-flex flex-wrap gap-2 mt-3">
                                @if($isUrgent)
                                    <span class="ops-chip ops-chip-danger">Urgente</span>
                                @endif
                                <span class="ops-chip {{ $commande->statut === 'annulee' ? 'ops-chip-danger' : ($commande->statut === 'en_attente' ? 'ops-chip-warn' : ($commande->statut === 'en_preparation' ? 'ops-chip-blue' : 'ops-chip-green')) }}">
                                    {{ str_replace('_', ' ', $commande->statut) }}
                                </span>
                                <span class="ops-chip {{ $commande->is_paid ? 'ops-chip-green' : 'ops-chip-soft' }}">
                                    {{ $commande->is_paid ? 'Payee' : 'Non payee' }}
                                </span>
                            </div>
                        </aside>

                        <section class="ops-section">
                            <p class="ops-section-title">Client et commande</p>
                            <div class="ops-name">{{ $commande->user->name }}</div>
                            <div class="ops-muted mb-3">{{ $commande->user->email }}</div>

                            <div class="ops-products">
                                @foreach($commande->burgers as $burger)
                                    <div class="ops-product">
                                        <span class="fw-semibold text-dark">{{ $burger->nom }}</span>
                                        <span class="ops-chip ops-chip-blue">x{{ $burger->pivot->quantite }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </section>

                        <section class="ops-section">
                            <p class="ops-section-title">Dernier evenement</p>
                            @if($commande->latestHistorique)
                                <div class="ops-name mb-2">{{ $commande->latestHistorique->description }}</div>
                                <div class="ops-muted">{{ $commande->latestHistorique->user?->name ?? 'Systeme' }} • {{ $commande->latestHistorique->created_at->format('d/m/Y H:i') }}</div>
                            @else
                                <div class="ops-muted">Aucun historique.</div>
                            @endif
                        </section>
                    </div>

                    <div class="ops-order-main">
                        <section class="ops-section">
                            <p class="ops-section-title">Attribution</p>
                            @if($commande->gestionnaire)
                                <div class="ops-name">{{ $commande->gestionnaire->name }}</div>
                                <div class="ops-muted mb-3">{{ $commande->gestionnaire->email }}</div>
                            @else
                                <div class="ops-muted mb-3">Commande non attribuee.</div>
                            @endif

                            <form action="{{ route('admin.commandes.assign', $commande) }}" method="POST" class="d-grid gap-2">
                                @csrf
                                <select name="gestionnaire_id" class="form-select">
                                    @foreach($gestionnaires as $gestionnaire)
                                        <option value="{{ $gestionnaire->id }}" @selected(($commande->gestionnaire_id ?? auth()->id()) === $gestionnaire->id)>
                                            {{ $gestionnaire->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="ops-btn ops-btn-light">Attribuer / reaffecter</button>
                            </form>
                        </section>

                        <section class="ops-section">
                            <p class="ops-section-title">Traitement</p>
                            <div class="ops-actions">
                                @if(!$commande->gestionnaire)
                                    <form action="{{ route('admin.commandes.assign', $commande) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="ops-btn ops-btn-dark">Prendre en charge</button>
                                    </form>
                                @elseif($isLockedByOtherManager)
                                    <div class="ops-muted text-danger">Commande geree par un autre gestionnaire.</div>
                                @elseif($nextStatut)
                                    <form action="{{ route('admin.commandes.updateStatut', $commande) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="statut" value="{{ $nextStatut }}">
                                        <button type="submit" class="ops-btn ops-btn-orange">Passer a {{ str_replace('_', ' ', $nextStatut) }}</button>
                                    </form>

                                    <form action="{{ route('admin.commandes.updateStatut', $commande) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="statut" value="annulee">
                                        <button type="submit" class="ops-btn ops-btn-red">Annuler la commande</button>
                                    </form>
                                @elseif($commande->statut === 'annulee')
                                    <div class="ops-muted text-danger">Commande annulee et verrouillee.</div>
                                @else
                                    <div class="ops-muted">Le flux de traitement est termine.</div>
                                @endif
                            </div>
                        </section>

                        <section class="ops-section">
                            <p class="ops-section-title">Actions rapides</p>
                            <div class="ops-actions">
                                @if(!$commande->is_paid)
                                    @if($isLockedByOtherManager)
                                        <div class="ops-muted text-danger">Encaissement bloque.</div>
                                    @elseif($commande->statut !== 'annulee')
                                        <button type="button" class="ops-btn ops-btn-green" data-bs-toggle="modal" data-bs-target="#payerCommandeModal{{ $commande->id }}">
                                            Encaisser maintenant
                                        </button>
                                    @else
                                        <div class="ops-muted text-danger">Paiement indisponible.</div>
                                    @endif
                                @endif

                                <a href="{{ route('admin.commandes.show', $commande) }}" class="ops-btn ops-btn-light">Ouvrir le detail</a>
                            </div>
                        </section>
                    </div>

                    @if(!$commande->is_paid && $commande->statut !== 'annulee' && (! $commande->gestionnaire_id || $commande->gestionnaire_id === auth()->id()))
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
                </article>
            @empty
                <div class="ops-empty">Aucune commande a afficher avec les filtres actuels.</div>
            @endforelse
        </section>

        <div>
            {{ $commandes->links() }}
        </div>
    </div>

    <div id="adminLiveToast" class="ops-toast" aria-live="polite" aria-atomic="true">
        <div class="ops-toast-title" id="adminLiveToastTitle"></div>
        <div id="adminLiveToastBody"></div>
    </div>

    @push('scripts')
    <script>
        (() => {
            const banner = document.getElementById('liveCommandesBanner');
            const text = document.getElementById('liveCommandesText');
            const stockText = document.getElementById('liveStockText');
            const reloadButton = document.getElementById('liveCommandesReload');
            const notificationButton = document.getElementById('enableBrowserNotifications');
            const toast = document.getElementById('adminLiveToast');
            const toastTitle = document.getElementById('adminLiveToastTitle');
            const toastBody = document.getElementById('adminLiveToastBody');

            if (!banner || !text || !stockText || !reloadButton || !notificationButton || !toast || !toastTitle || !toastBody) {
                return;
            }

            let latestCommandeId = banner.dataset.latestCommandeId || '';
            let lowStockTotal = {{ (int) ($liveSummary['low_stock_total'] ?? 0) }};
            let outOfStockTotal = {{ (int) ($liveSummary['out_of_stock_total'] ?? 0) }};
            let toastTimeout;

            const showToast = (title, body) => {
                toastTitle.textContent = title;
                toastBody.textContent = body;
                toast.classList.add('is-visible');
                clearTimeout(toastTimeout);
                toastTimeout = setTimeout(() => toast.classList.remove('is-visible'), 5000);
            };

            const playAlert = () => {
                try {
                    const context = new (window.AudioContext || window.webkitAudioContext)();
                    const oscillator = context.createOscillator();
                    const gainNode = context.createGain();
                    oscillator.type = 'triangle';
                    oscillator.frequency.value = 880;
                    gainNode.gain.value = 0.03;
                    oscillator.connect(gainNode);
                    gainNode.connect(context.destination);
                    oscillator.start();
                    oscillator.stop(context.currentTime + 0.12);
                } catch (_) {}
            };

            const sendBrowserNotification = (title, body) => {
                if (!('Notification' in window) || Notification.permission !== 'granted') {
                    return;
                }

                new Notification(title, { body });
            };

            notificationButton.addEventListener('click', () => {
                if (!('Notification' in window)) {
                    showToast('Notifications indisponibles', 'Ce navigateur ne supporte pas les notifications.');
                    return;
                }

                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        showToast('Notifications actives', 'Les nouvelles commandes et alertes stock seront signalees.');
                    }
                });
            });

            reloadButton.addEventListener('click', () => window.location.reload());

            setInterval(() => {
                fetch(banner.dataset.url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                    .then(response => response.ok ? response.json() : null)
                    .then(data => {
                        if (!data) {
                            return;
                        }

                        text.textContent = `${data.pending_total} en cours, ${data.unassigned_total} non attribuee(s), ${data.urgent_total} urgente(s), ${data.low_stock_total} stock faible(s).`;
                        stockText.textContent = `${data.low_stock_total} burger(s) bas en stock, ${data.out_of_stock_total} en rupture.`;

                        if (String(data.latest_commande_id || '') !== String(latestCommandeId || '')) {
                            latestCommandeId = data.latest_commande_id || '';
                            reloadButton.classList.remove('d-none');
                            showToast('Nouvelle commande', 'Une nouvelle commande vient d\'arriver. Rechargez pour la traiter.');
                            sendBrowserNotification('Nouvelle commande', 'Une nouvelle commande vient d\'arriver.');
                            playAlert();
                        }

                        if ((data.low_stock_total || 0) > lowStockTotal || (data.out_of_stock_total || 0) > outOfStockTotal) {
                            const lowStockNames = Array.isArray(data.low_stock_burgers) ? data.low_stock_burgers.map(item => `${item.nom} (${item.stock})`).join(', ') : '';
                            showToast('Alerte stock', lowStockNames || 'Un ou plusieurs burgers approchent de la rupture.');
                            sendBrowserNotification('Alerte stock', lowStockNames || 'Un ou plusieurs burgers approchent de la rupture.');
                        }

                        lowStockTotal = data.low_stock_total || 0;
                        outOfStockTotal = data.out_of_stock_total || 0;
                    })
                    .catch(() => {});
            }, 20000);
        })();
    </script>
    @endpush
</x-app-layout>
