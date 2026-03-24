@extends('layouts.comptable')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title">Tableau de bord</h2>
        </div>
    </div>

    <!-- 1. Souscriptions Overview -->
    <h5 class="mb-3 text-muted">État des Souscriptions</h5>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">En attente</span>
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $souscriptionsEnAttente }}</div>
                <div class="stat-sub">Dossiers en attente de paiement</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">En cours</span>
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-spinner"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $paiementsEnCours }}</div>
                <div class="stat-sub">Paiements partiels effectués</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Réglé</span>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $clientsSoldes }}</div>
                <div class="stat-sub">Dossiers entièrement soldés</div>
            </div>
        </div>
    </div>

    <!-- 2. Frais & Apport Overview -->
    <h5 class="mb-3 text-muted">Détails Financiers par Catégorie</h5>
    <div class="row mb-4">
        <!-- Frais de Dossier -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <strong><i class="fas fa-file-invoice"></i> Frais de Dossier</strong>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4 border-end">
                            <div class="text-muted small">Total Attendu</div>
                            <div class="fw-bold text-primary">{{ number_format($fraisDossierTotalAmount, 0, ',', ' ') }}</div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="text-muted small">Total Encaissé</div>
                            <div class="fw-bold text-success">{{ number_format($fraisDossierPaye, 0, ',', ' ') }}</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Reste à Percevoir</div>
                            <div class="fw-bold text-danger">{{ number_format($fraisDossierReste, 0, ',', ' ') }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between px-3">
                        <span>Dossiers soldés: <strong>{{ $fraisDossierCountSoldes }}</strong> / {{ $fraisDossierCountTotal }}</span>
                        <div class="progress w-50 mt-1" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $fraisDossierCountTotal > 0 ? ($fraisDossierCountSoldes / $fraisDossierCountTotal) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Apport Initial -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <strong><i class="fas fa-hand-holding-usd"></i> Apport Initial</strong>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4 border-end">
                            <div class="text-muted small">Total Attendu</div>
                            <div class="fw-bold text-primary">{{ number_format($apportInitialTotalAmount, 0, ',', ' ') }}</div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="text-muted small">Total Encaissé</div>
                            <div class="fw-bold text-success">{{ number_format($apportInitialPaye, 0, ',', ' ') }}</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Reste à Percevoir</div>
                            <div class="fw-bold text-danger">{{ number_format($apportInitialReste, 0, ',', ' ') }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between px-3">
                        <span>Apports soldés: <strong>{{ $apportInitialCountSoldes }}</strong> / {{ $apportInitialCountTotal }}</span>
                        <div class="progress w-50 mt-1" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $apportInitialCountTotal > 0 ? ($apportInitialCountSoldes / $apportInitialCountTotal) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Suivi Paiement Projet -->
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header bg-light">
                    <strong><i class="fas fa-home"></i> Suivi Paiement Projet</strong>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4 border-end">
                            <div class="text-muted small">Total Attendu</div>
                            <div class="fw-bold text-primary">{{ number_format($projetTotalAttendu, 0, ',', ' ') }}</div>
                        </div>
                        <div class="col-4 border-end">
                            <div class="text-muted small">Total Encaissé</div>
                            <div class="fw-bold text-success">{{ number_format($projetTotalPaye, 0, ',', ' ') }}</div>
                        </div>
                        <div class="col-4">
                            <div class="text-muted small">Reste à Percevoir</div>
                            <div class="fw-bold text-danger">{{ number_format($projetTotalReste, 0, ',', ' ') }}</div>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between px-3">
                        <span>Dossiers soldés: <strong>{{ $projetCountSoldes }}</strong> / {{ $projetCountTotal }}</span>
                        <div class="progress w-50 mt-1" style="height: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                style="width: {{ $projetCountTotal > 0 ? ($projetCountSoldes / $projetCountTotal) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Global Totals -->
    <h5 class="mb-3 text-muted">Situation Globale</h5>
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="stat-card bg-success text-white">
                <div class="stat-header">
                    <span class="stat-title text-white">Total Encaissé Global</span>
                    <div class="stat-icon bg-white text-success">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="stat-value text-white">{{ number_format($totalEncaisse, 0, ',', ' ') }} FCFA</div>
                <div class="stat-sub text-white-50">Cumul de tous les paiements validés</div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stat-card bg-danger text-white">
                <div class="stat-header">
                    <span class="stat-title text-white">Total Restant Global (Estimé)</span>
                    <div class="stat-icon bg-white text-danger">
                        <i class="fas fa-chart-pie"></i>
                    </div>
                </div>
                <div class="stat-value text-white">{{ number_format($totalRestant, 0, ',', ' ') }} FCFA</div>
                <div class="stat-sub text-white-50">Montant restant à recouvrer sur les engagements</div>
            </div>
        </div>
    </div>

    <!-- Graphiques -->
    <h5 class="mb-3 text-muted">Analyses par Projet</h5>
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <strong>Total encaissé par projet</strong>
                </div>
                <div class="card-body">
                    <canvas id="barEncaisseParProjet" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <strong>Répartition par projet</strong>
                </div>
                <div class="card-body">
                    <canvas id="donutPartProjet" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Filter -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title mb-3">Filtrer les graphiques par projet :</h6>
                    <div class="d-flex flex-wrap gap-2" id="projectFilters">
                        <button class="btn btn-sm btn-outline-primary active" data-filter="all" onclick="filterProjects('all')">
                            Tous les projets
                        </button>
                        @foreach($projets as $projet)
                            @if(in_array($projet->nom, $barChartLabels))
                                <button class="btn btn-sm btn-outline-secondary" data-filter="{{ $projet->nom }}" onclick="toggleProject('{{ $projet->nom }}', this)">
                                    {{ $projet->nom }}
                                </button>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Données initiales
    const allLabels = @json($barChartLabels);
    const allValues = @json($barChartValues);
    
    // État de la sélection
    let selectedProjects = [...allLabels]; // Par défaut, tous sélectionnés

    // Charts instances
    let barChart = null;
    let donutChart = null;

    // Plugin to draw value labels above bars
    const valueLabels = {
        id: 'valueLabels',
        afterDatasetsDraw(chart) {
            const { ctx, data } = chart;
            const meta = chart.getDatasetMeta(0);
            if (!meta.data.length) return;
            
            ctx.save();
            ctx.font = '12px Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif';
            ctx.fillStyle = '#111827';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';
            meta.data.forEach((bar, i) => {
                if (bar.hidden) return;
                const val = data.datasets[0].data[i];
                if (val === undefined) return;
                
                const pos = bar.tooltipPosition();
                const text = new Intl.NumberFormat('fr-FR').format(val) + ' FCFA';
                ctx.fillText(text, pos.x, pos.y - 4);
            });
            ctx.restore();
        }
    };

    function initCharts() {
        // Bar chart
        const barCtx = document.getElementById('barEncaisseParProjet');
        if (barCtx) {
            barChart = new Chart(barCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: allLabels,
                    datasets: [{
                        label: 'Montant encaissé',
                        data: allValues,
                        backgroundColor: '#3b82f6'
                    }]
                },
                plugins: [valueLabels],
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { callback: (v) => new Intl.NumberFormat('fr-FR').format(v) }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => new Intl.NumberFormat('fr-FR').format(ctx.parsed.y) + ' FCFA'
                            }
                        }
                    }
                }
            });
        }

        // Donut chart
        const donutCtx = document.getElementById('donutPartProjet');
        if (donutCtx) {
            donutChart = new Chart(donutCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: allLabels.map(name => `Part ${name}`),
                    datasets: [{
                        data: allValues,
                        backgroundColor: ['#2563eb','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4', '#6366f1', '#ec4899'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '60%'
                }
            });
        }
    }

    // Fonction de filtrage "Tous"
    function filterProjects(type) {
        if (type === 'all') {
            selectedProjects = [...allLabels];
            
            // Reset UI buttons
            document.querySelectorAll('#projectFilters button').forEach(btn => {
                if (btn.dataset.filter === 'all') {
                    btn.classList.add('active', 'btn-outline-primary');
                    btn.classList.remove('btn-outline-secondary');
                } else {
                    btn.classList.remove('active', 'btn-primary');
                    btn.classList.add('btn-outline-secondary');
                }
            });
            
            updateCharts();
        }
    }

    // Fonction de bascule individuelle
    function toggleProject(projectName, btn) {
        // Si "Tous" était actif, on le désactive et on part d'une sélection vide
        const allBtn = document.querySelector('button[data-filter="all"]');
        if (allBtn.classList.contains('active')) {
            allBtn.classList.remove('active', 'btn-outline-primary');
            allBtn.classList.add('btn-outline-secondary');
            selectedProjects = []; // On repart de zéro car l'utilisateur commence à sélectionner
        }

        const index = selectedProjects.indexOf(projectName);
        if (index === -1) {
            // Ajouter
            selectedProjects.push(projectName);
            btn.classList.add('active', 'btn-primary');
            btn.classList.remove('btn-outline-secondary');
        } else {
            // Retirer
            selectedProjects.splice(index, 1);
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-outline-secondary');
        }

        // Si tout est désélectionné, on remet "Tous" par défaut ? Ou on laisse vide ?
        // UX: Si vide, on remet "Tous" pour éviter graphique vide
        if (selectedProjects.length === 0) {
            filterProjects('all');
            return;
        }

        updateCharts();
    }

    function updateCharts() {
        // Filtrer les données
        const newLabels = [];
        const newValues = [];
        
        // On préserve l'ordre initial
        allLabels.forEach((label, index) => {
            if (selectedProjects.includes(label)) {
                newLabels.push(label);
                newValues.push(allValues[index]);
            }
        });

        // Update Bar Chart
        if (barChart) {
            barChart.data.labels = newLabels;
            barChart.data.datasets[0].data = newValues;
            barChart.update();
        }

        // Update Donut Chart
        if (donutChart) {
            donutChart.data.labels = newLabels.map(name => `Part ${name}`);
            donutChart.data.datasets[0].data = newValues;
            donutChart.update();
        }
    }

    document.addEventListener('DOMContentLoaded', initCharts);
</script>
@endpush
