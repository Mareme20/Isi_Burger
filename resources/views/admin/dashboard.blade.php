<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h1 class="h4 mb-1 fw-bold">Tableau de bord gestionnaire</h1>
                <p class="mb-0 text-secondary small">Suivi des performances quotidiennes de ISI BURGER.</p>
            </div>
            <a href="{{ route('admin.commandes.index') }}" class="btn btn-light border rounded-pill px-3">Voir les commandes</a>
        </div>
    </x-slot>

    <style>
        .dash-hero {
            border: 1px solid #f0cfac;
            border-radius: 1.2rem;
            padding: 1.1rem;
            background:
                radial-gradient(circle at 10% 15%, #fff2df 0, #fff2df 14%, transparent 40%),
                linear-gradient(130deg, #fff8ef, #ffe4c4 52%, #ffd09d);
            box-shadow: 0 12px 24px rgba(82, 31, 12, .08);
        }

        .dash-sub {
            color: #744d3f;
            margin: .4rem 0 0;
            max-width: 60ch;
        }

        .dash-stat {
            border: 1px solid #efd5b7;
            border-radius: 1rem;
            background: #fffaf5;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
            padding: 1rem;
            height: 100%;
        }

        .dash-stat-title {
            text-transform: uppercase;
            letter-spacing: .08em;
            font-size: .72rem;
            font-weight: 800;
            color: #7a5242;
            margin-bottom: .45rem;
        }

        .dash-stat-value {
            margin: 0;
            font-size: clamp(1.6rem, 3.2vw, 2.2rem);
            font-weight: 800;
            color: #31170f;
            line-height: 1;
        }

        .dash-chip {
            display: inline-flex;
            border-radius: 999px;
            font-size: .68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: .3rem .56rem;
            margin-top: .6rem;
        }

        .dash-chip-blue { color: #1d4ed8; background: #dbeafe; }
        .dash-chip-green { color: #166534; background: #dcfce7; }
        .dash-chip-amber { color: #92400e; background: #fef3c7; }

        .dash-card {
            border: 1px solid #efd5b7;
            border-radius: 1rem;
            background: #fffaf5;
            box-shadow: 0 8px 20px rgba(82, 31, 12, .06);
            padding: 1rem;
            height: 100%;
        }

        .dash-card-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            color: #31170f;
        }

        .dash-select {
            max-width: 220px;
            border-radius: .75rem;
            border-color: #e6c5a6;
        }

        .dash-canvas-wrap {
            position: relative;
            min-height: 280px;
        }

        .dash-canvas-small {
            position: relative;
            min-height: 280px;
            max-width: 360px;
            margin: 0 auto;
        }
    </style>

    <section class="dash-hero mb-3">
        <h2 class="h5 fw-bold mb-1">Vue globale de l'activite</h2>
        <p class="dash-sub">Analysez les commandes du jour, les paiements et la repartition des produits par categorie en un coup d'oeil.</p>
    </section>

    <section class="row g-3 mb-3">
        <div class="col-12 col-md-4">
            <div class="dash-stat">
                <p class="dash-stat-title">Commandes du jour</p>
                <p class="dash-stat-value">{{ $commandesDuJour }}</p>
                <span class="dash-chip dash-chip-blue">Activite quotidienne</span>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="dash-stat">
                <p class="dash-stat-title">Commandes payees</p>
                <p class="dash-stat-value">{{ $commandesValidees }}</p>
                <span class="dash-chip dash-chip-green">Paiements confirmes</span>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="dash-stat">
                <p class="dash-stat-title">Recette du jour</p>
                <p class="dash-stat-value">{{ number_format($recetteJour, 0, ',', ' ') }} <span class="h5">FCFA</span></p>
                <span class="dash-chip dash-chip-amber">Chiffre du jour</span>
            </div>
        </div>
    </section>

    <section class="row g-3">
        <div class="col-12 col-xl-8">
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h3 class="dash-card-title">Commandes par mois</h3>
                    <select id="filterMois" class="form-select form-select-sm dash-select">
                        <option value="">Tous les mois</option>
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="dash-canvas-wrap">
                    <canvas id="commandesChart"></canvas>
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-4">
            <div class="dash-card">
                <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                    <h3 class="dash-card-title">Produits par categorie</h3>
                    <select id="filterCategorieMois" class="form-select form-select-sm dash-select">
                        <option value="">Mois courant</option>
                        @for($m=1; $m<=12; $m++)
                            <option value="{{ $m }}">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                        @endfor
                    </select>
                </div>
                <div class="dash-canvas-small">
                    <canvas id="produitsChart"></canvas>
                </div>
            </div>
        </div>
    </section>

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
                    backgroundColor: 'rgba(232, 93, 4, 0.75)',
                    borderColor: 'rgba(196, 72, 0, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        let produitsChart = new Chart(ctxProduits, {
            type: 'pie',
            data: {
                labels: Object.keys(produitsData),
                datasets: [{
                    data: Object.values(produitsData),
                    backgroundColor: ['#ef4444', '#f59e0b', '#10b981', '#3b82f6', '#8b5cf6', '#f97316'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
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
    </script>
    @endpush
</x-app-layout>
