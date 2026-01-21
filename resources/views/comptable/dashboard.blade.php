@extends('layouts.comptable')

@section('content')
<div class="container-fluid">
    <!-- Statistiques principales -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <span>Total encaissé</span>
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <div class="stat-value">{{ $totalEncaisseShort }}</div>
            <div class="stat-sub">Montant encaissé (tous paiements validés)</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span>Frais de dossier validés</span>
                <i class="fas fa-file-invoice"></i>
            </div>
            <div class="stat-value">{{ $fraisDossierValides }}/ {{ $fraisDossierTotal }}</div>
            <div class="stat-sub">Validation des frais de dossier</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span>Suivi paiements projet</span>
                <i class="fas fa-chart-line"></i>
            </div>
            <div class="stat-value">{{ $paiementsEnCours }} en cours</div>
            <div class="stat-sub">Souscriptions avec paiements en cours</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <span>Paiements soldés</span>
                <i class="fas fa-trophy"></i>
            </div>
            <div class="stat-value">{{ $clientsSoldes }} clients soldés</div>
            <div class="stat-sub">Souscriptions entièrement réglées</div>
        </div>
    </div>

    <!-- Graphiques -->
    <div class="row">
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <strong>Total encaissé</strong>
                </div>
                <div class="card-body">
                    <canvas id="barEncaisseParProjet" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <strong>Pourcentage encaissé par projet</strong>
                </div>
                <div class="card-body">
                    <canvas id="donutPartProjet" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($barChartLabels);
    const values = @json($barChartValues);

    // Plugin to draw value labels above bars
    const valueLabels = {
        id: 'valueLabels',
        afterDatasetsDraw(chart) {
            const { ctx, data } = chart;
            const meta = chart.getDatasetMeta(0);
            ctx.save();
            ctx.font = '12px Inter, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif';
            ctx.fillStyle = '#111827';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';
            meta.data.forEach((bar, i) => {
                const val = data.datasets[0].data[i];
                const pos = bar.tooltipPosition();
                const text = new Intl.NumberFormat('fr-FR').format(val) + ' FCFA';
                ctx.fillText(text, pos.x, pos.y - 4);
            });
            ctx.restore();
        }
    };

    // Bar chart - montant encaissé par projet
    const barCtx = document.getElementById('barEncaisseParProjet');
    if (barCtx) {
        new Chart(barCtx.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Montant encaissé par projet',
                    data: values,
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

    // Donut chart - part par projet
    const donutCtx = document.getElementById('donutPartProjet');
    if (donutCtx) {
        new Chart(donutCtx.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: labels.map(name => `Part du projet ${name}`),
                datasets: [{
                    data: values,
                    backgroundColor: ['#2563eb','#10b981','#f59e0b','#ef4444','#8b5cf6','#06b6d4'],
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
</script>
@endpush