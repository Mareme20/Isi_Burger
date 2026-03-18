<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="h4 mb-1 fw-bold">Tableau de bord gestionnaire</h1>
                <p class="mb-0 text-secondary small">Une vue simple pour suivre vos commandes, votre chiffre et les alertes utiles.</p>
            </div>
            <a href="{{ route('admin.commandes.index') }}" class="btn btn-dark rounded-pill px-3">Voir les commandes</a>
        </div>
    </x-slot>

    <style>
        .dash-wrap {
            display: grid;
            gap: 1rem;
        }

        .dash-banner {
            border: 1px solid #e5c9ab;
            border-radius: 1.2rem;
            padding: 1.1rem 1.2rem;
            background: linear-gradient(135deg, #fffaf4 0%, #ffe8cc 100%);
            box-shadow: 0 12px 24px rgba(82, 31, 12, .06);
        }

        .dash-banner-title {
            margin: 0 0 .3rem;
            color: #2f1a12;
            font-size: 1.4rem;
            font-weight: 900;
        }

        .dash-banner-text {
            margin: 0;
            color: #714d3f;
            max-width: 68ch;
        }

        .dash-alert {
            border: 1px solid #efd7b7;
            border-radius: 1rem;
            background: #fffaf3;
            padding: .95rem 1rem;
            box-shadow: 0 8px 18px rgba(82, 31, 12, .05);
        }

        .dash-alert-title {
            margin: 0 0 .35rem;
            font-size: .74rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #7b5242;
        }

        .dash-alert-text {
            color: #31170f;
            font-weight: 700;
        }

        .dash-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .9rem;
        }

        .dash-kpi {
            border: 1px solid #ecd3b5;
            border-radius: 1rem;
            background: #fff;
            box-shadow: 0 10px 22px rgba(82, 31, 12, .05);
            padding: 1rem;
        }

        .dash-kpi-title {
            margin: 0 0 .4rem;
            color: #7d5747;
            font-size: .72rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .dash-kpi-value {
            margin: 0;
            color: #31170f;
            font-size: clamp(1.45rem, 3vw, 2rem);
            font-weight: 900;
            line-height: 1;
        }

        .dash-kpi-note {
            margin-top: .55rem;
            color: #7b5a4d;
            font-size: .84rem;
            font-weight: 700;
        }

        .dash-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, .85fr);
            gap: 1rem;
        }

        .dash-column {
            display: grid;
            gap: 1rem;
        }

        .dash-card {
            border: 1px solid #ecd3b5;
            border-radius: 1.1rem;
            background: #fff;
            box-shadow: 0 10px 22px rgba(82, 31, 12, .05);
            padding: 1rem;
        }

        .dash-card-title {
            margin: 0 0 .85rem;
            color: #31170f;
            font-size: 1rem;
            font-weight: 900;
        }

        .dash-stats-mini {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .75rem;
        }

        .dash-mini {
            border: 1px solid #f0dcc8;
            border-radius: .95rem;
            background: #fffaf5;
            padding: .85rem;
        }

        .dash-mini-label {
            margin: 0 0 .35rem;
            color: #7b5242;
            font-size: .7rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .08em;
        }

        .dash-mini-value {
            margin: 0;
            color: #31170f;
            font-size: 1.15rem;
            font-weight: 900;
        }

        .dash-list {
            display: grid;
            gap: .7rem;
        }

        .dash-item {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
            align-items: center;
            border: 1px solid #f0dcc8;
            border-radius: .95rem;
            padding: .85rem .9rem;
            background: #fffaf5;
        }

        .dash-item-title {
            color: #2f1a12;
            font-weight: 900;
        }

        .dash-item-sub {
            color: #7b5a4d;
            font-size: .84rem;
        }

        .dash-chart-wrap {
            position: relative;
            min-height: 290px;
        }

        .dash-chart-small {
            position: relative;
            min-height: 290px;
            max-width: 340px;
            margin: 0 auto;
        }

        .dash-select {
            max-width: 220px;
            border-radius: .75rem;
            border-color: #e6c5a6;
        }

        .dash-toast {
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

        .dash-toast.is-visible {
            opacity: 1;
            pointer-events: auto;
            transform: translateY(0);
        }

        @media (max-width: 1199.98px) {
            .dash-grid {
                grid-template-columns: 1fr;
            }

            .dash-kpis {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 767.98px) {
            .dash-kpis,
            .dash-stats-mini {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="dash-wrap">
        <section class="dash-banner">
            <h2 class="dash-banner-title">
                {{ $commandesEnCours > 0 ? 'Vous avez des commandes a traiter.' : 'Aucune commande urgente dans votre file.' }}
            </h2>
            <p class="dash-banner-text">
                {{ $topBurger?->nom ? 'Le produit qui ressort le plus actuellement est ' . $topBurger->nom . '.' : 'Le dashboard affichera vos tendances personnelles au fur et a mesure des traitements.' }}
            </p>
        </section>

        <section id="dashRealtimeAlert" class="dash-alert" data-url="{{ route('admin.commandes.live-summary') }}">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <p class="dash-alert-title">Alertes</p>
                    <div id="dashRealtimeText" class="dash-alert-text">
                        {{ $commandesNonAttribuees }} commande(s) non attribuee(s), {{ $burgersStockFaible->count() }} burger(s) en stock faible, {{ $burgersEnRupture->count() }} en rupture.
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <button type="button" id="dashEnableNotifications" class="btn btn-outline-dark btn-sm">Activer les notifications</button>
                    <a href="{{ route('burgers.index') }}" class="btn btn-light border btn-sm">Voir le stock</a>
                </div>
            </div>
        </section>

        <section class="dash-kpis">
            <article class="dash-kpi">
                <p class="dash-kpi-title">Commandes du jour</p>
                <p class="dash-kpi-value">{{ $commandesDuJour }}</p>
                <div class="dash-kpi-note">{{ $commandesEnCours }} encore en cours</div>
            </article>
            <article class="dash-kpi">
                <p class="dash-kpi-title">Recette du jour</p>
                <p class="dash-kpi-value">{{ number_format($recetteJour, 0, ',', ' ') }} FCFA</p>
                <div class="dash-kpi-note">{{ $commandesValidees }} paiement(s) valides</div>
            </article>
            <article class="dash-kpi">
                <p class="dash-kpi-title">Ticket moyen</p>
                <p class="dash-kpi-value">{{ number_format($ticketMoyen ?? 0, 0, ',', ' ') }} FCFA</p>
                <div class="dash-kpi-note">Panier moyen encaisse</div>
            </article>
            <article class="dash-kpi">
                <p class="dash-kpi-title">Temps moyen</p>
                <p class="dash-kpi-value">{{ $tempsMoyenPreparation ? round($tempsMoyenPreparation) : 0 }} min</p>
                <div class="dash-kpi-note">De creation a prete</div>
            </article>
        </section>

        <section class="dash-grid">
            <div class="dash-column">
                <section class="dash-card">
                    <h3 class="dash-card-title">Vue rapide</h3>
                    <div class="dash-stats-mini">
                        <div class="dash-mini">
                            <p class="dash-mini-label">Commandes pretes</p>
                            <p class="dash-mini-value">{{ $commandesPretes }}</p>
                        </div>
                        <div class="dash-mini">
                            <p class="dash-mini-label">Commandes annulees</p>
                            <p class="dash-mini-value">{{ $commandesAnnulees }}</p>
                        </div>
                        <div class="dash-mini">
                            <p class="dash-mini-label">Heure la plus chargee</p>
                            <p class="dash-mini-value">{{ $heurePic?->heure !== null ? str_pad((string) $heurePic->heure, 2, '0', STR_PAD_LEFT) . 'h' : '--' }}</p>
                        </div>
                        <div class="dash-mini">
                            <p class="dash-mini-label">Client principal</p>
                            <p class="dash-mini-value">{{ $topClient?->nom ?? 'Aucun' }}</p>
                        </div>
                    </div>
                </section>

                <section class="dash-card">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h3 class="dash-card-title mb-0">Commandes par mois</h3>
                        <select id="filterMois" class="form-select form-select-sm dash-select">
                            <option value="">Tous les mois</option>
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="dash-chart-wrap">
                        <canvas id="commandesChart"></canvas>
                    </div>
                </section>

                <section class="dash-card">
                    <h3 class="dash-card-title">Activite recente</h3>
                    <div class="dash-list">
                        @forelse($activitesRecentes as $activite)
                            <div class="dash-item">
                                <div>
                                    <div class="dash-item-title">{{ $activite->description }}</div>
                                    <div class="dash-item-sub">CMD-{{ $activite->commande_id }} • {{ $activite->user?->name ?? 'Systeme' }}</div>
                                </div>
                                <div class="dash-item-sub">{{ $activite->created_at->format('d/m/Y H:i') }}</div>
                            </div>
                        @empty
                            <p class="mb-0 text-secondary">Aucune activite recente a afficher.</p>
                        @endforelse
                    </div>
                </section>
            </div>

            <div class="dash-column">
                <section class="dash-card">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h3 class="dash-card-title mb-0">Produits par categorie</h3>
                        <select id="filterCategorieMois" class="form-select form-select-sm dash-select">
                            <option value="">Mois courant</option>
                            @for($m=1; $m<=12; $m++)
                                <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="dash-chart-small">
                        <canvas id="produitsChart"></canvas>
                    </div>
                </section>

                <section class="dash-card">
                    <h3 class="dash-card-title">Stock faible</h3>
                    <div class="dash-list">
                        @forelse($burgersStockFaible as $burger)
                            <div class="dash-item">
                                <div>
                                    <div class="dash-item-title">{{ $burger->nom }}</div>
                                    <div class="dash-item-sub">Approche de la rupture</div>
                                </div>
                                <span class="badge text-bg-warning">{{ $burger->stock }} restant(s)</span>
                            </div>
                        @empty
                            <p class="mb-0 text-secondary">Aucun burger en stock faible actuellement.</p>
                        @endforelse
                    </div>
                </section>

                <section class="dash-card">
                    <h3 class="dash-card-title">Ruptures</h3>
                    <div class="dash-list">
                        @forelse($burgersEnRupture as $burger)
                            <div class="dash-item">
                                <div>
                                    <div class="dash-item-title">{{ $burger->nom }}</div>
                                    <div class="dash-item-sub">Indisponible pour les nouvelles commandes</div>
                                </div>
                                <span class="badge text-bg-danger">Rupture</span>
                            </div>
                        @empty
                            <p class="mb-0 text-secondary">Aucune rupture detectee.</p>
                        @endforelse
                    </div>
                </section>
            </div>
        </section>
    </div>

    <div id="dashMiniToast" class="dash-toast" aria-live="polite" aria-atomic="true"></div>

    @push('scripts')
    <script>
        const ctxCommandes = document.getElementById('commandesChart').getContext('2d');
        const ctxProduits = document.getElementById('produitsChart').getContext('2d');
        const commandesData = @json($commandesParMois);
        const produitsData = @json($produitsParCategorie);

        let commandesChart = new Chart(ctxCommandes, {
            type: 'bar',
            data: {
                labels: Object.keys(commandesData),
                datasets: [{
                    label: 'Nombre de commandes',
                    data: Object.values(commandesData),
                    backgroundColor: 'rgba(232, 93, 4, 0.78)',
                    borderColor: 'rgba(196, 72, 0, 1)',
                    borderWidth: 1,
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });

        let produitsChart = new Chart(ctxProduits, {
            type: 'doughnut',
            data: {
                labels: Object.keys(produitsData),
                datasets: [{
                    data: Object.values(produitsData),
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#f97316'],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '58%'
            }
        });

        function updateChart(url, chart, paramName, paramValue) {
            fetch(`${url}?${paramName}=${paramValue}`)
                .then(res => res.json())
                .then(data => {
                    chart.data.labels = Object.keys(data);
                    chart.data.datasets[0].data = Object.values(data);
                    chart.update();
                });
        }

        document.getElementById('filterMois').addEventListener('change', function() {
            updateChart('/admin/dashboard/commandes-data', commandesChart, 'mois', this.value);
        });

        document.getElementById('filterCategorieMois').addEventListener('change', function() {
            updateChart('/admin/dashboard/produits-data', produitsChart, 'mois', this.value);
        });

        (() => {
            const alertBox = document.getElementById('dashRealtimeAlert');
            const alertText = document.getElementById('dashRealtimeText');
            const enableButton = document.getElementById('dashEnableNotifications');
            const toast = document.getElementById('dashMiniToast');

            if (!alertBox || !alertText || !enableButton || !toast) {
                return;
            }

            let latestCommandeId = null;
            let lowStockTotal = {{ $burgersStockFaible->count() }};
            let outOfStockTotal = {{ $burgersEnRupture->count() }};
            let toastTimeout;

            const showToast = (message) => {
                toast.textContent = message;
                toast.classList.add('is-visible');
                clearTimeout(toastTimeout);
                toastTimeout = setTimeout(() => toast.classList.remove('is-visible'), 5000);
            };

            const notify = (title, body) => {
                if ('Notification' in window && Notification.permission === 'granted') {
                    new Notification(title, { body });
                }
            };

            enableButton.addEventListener('click', () => {
                if (!('Notification' in window)) {
                    showToast('Notifications non supportees sur ce navigateur.');
                    return;
                }

                Notification.requestPermission().then(permission => {
                    if (permission === 'granted') {
                        showToast('Notifications navigateur activees.');
                    }
                });
            });

            setInterval(() => {
                fetch(alertBox.dataset.url, {
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

                        alertText.textContent = `${data.unassigned_total} commande(s) non attribuee(s), ${data.low_stock_total} burger(s) en stock faible, ${data.out_of_stock_total} en rupture.`;

                        if (latestCommandeId !== null && String(data.latest_commande_id || '') !== String(latestCommandeId || '')) {
                            showToast('Nouvelle commande detectee. Ouvrez la file de traitement.');
                            notify('Nouvelle commande', 'Une nouvelle commande attend un gestionnaire.');
                        }

                        if ((data.low_stock_total || 0) > lowStockTotal || (data.out_of_stock_total || 0) > outOfStockTotal) {
                            showToast('Alerte stock: un burger approche de la rupture ou est en rupture.');
                            notify('Alerte stock', 'Consultez la gestion du stock.');
                        }

                        latestCommandeId = data.latest_commande_id || latestCommandeId;
                        lowStockTotal = data.low_stock_total || 0;
                        outOfStockTotal = data.out_of_stock_total || 0;
                    })
                    .catch(() => {});
            }, 20000);
        })();
    </script>
    @endpush
</x-app-layout>
