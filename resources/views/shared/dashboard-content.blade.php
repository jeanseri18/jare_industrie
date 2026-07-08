<x-page-header :title="$dashboardTitle ?? 'Tableau de bord'" :subtitle="$dashboardSubtitle ?? null" />
<x-alert />

<div class="stats-grid stats-grid--compact mb-4">
    @if(isset($totalClients))
        <x-stat-card label="Total clients" :value="$totalClients" footer="Clients enregistrés" />
    @endif
    @if(isset($totalProjets))
        <x-stat-card label="Total projets" :value="$totalProjets" footer="Programmes immobiliers" />
    @endif
    @if(isset($totalMutuelles))
        <x-stat-card label="Mutuelles" :value="$totalMutuelles" footer="Partenaires actifs" />
    @endif
    @if(isset($projetsActifs))
        <x-stat-card label="Projets actifs" :value="$projetsActifs" footer="En cours" />
    @endif
    @if(isset($totalEncaisse))
        <x-stat-card label="Montant encaissé" :value="number_format($totalEncaisse, 0, ',', ' ') . ' FCFA'" footer="Total payé" />
    @endif
    @if(isset($totalRestant))
        <x-stat-card label="Montant restant" :value="number_format($totalRestant, 0, ',', ' ') . ' FCFA'" footer="Solde global" />
    @endif
</div>

@if(isset($souscriptionsEnAttente) && !empty($chartDonut))
<div class="row mb-4 dashboard-souscriptions-row align-items-stretch">
    <div class="col-lg-5 col-xl-4">
        <x-dashboard-chart
            id="chart-donut-status"
            title="{{ $chartDonut['title'] ?? 'Répartition des souscriptions' }}"
            icon="fas fa-chart-pie"
            type="doughnut"
            :labels="$chartDonut['labels'] ?? []"
            :values="$chartDonut['values'] ?? []"
            height="220px"
            class="mb-0 h-100 dashboard-chart-card--fill"
        />
    </div>
    <div class="col-lg-7 col-xl-8">
        <div class="stats-grid stats-grid--compact stats-grid--donut-aside h-100">
            <x-stat-card label="En attente" :value="$souscriptionsEnAttente" footer="Dossiers en attente" />
            <x-stat-card label="En cours" :value="$souscriptionsEnCours ?? $paiementsEnCours ?? 0" footer="Paiements en cours" />
            <x-stat-card label="Soldées" :value="$souscriptionsSoldees ?? $clientsSoldes ?? 0" footer="Dossiers soldés" />
        </div>
    </div>
</div>
@endif

@include('shared.dashboard.charts-section', [
    'skipDonut' => isset($souscriptionsEnAttente) && !empty($chartDonut),
])
