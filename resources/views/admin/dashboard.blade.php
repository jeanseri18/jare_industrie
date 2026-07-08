@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
<!-- Alertes -->
@if(!empty($alerts))
    @foreach($alerts as $alert)
        <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert" style="border-radius: 8px; border-left: 4px solid currentColor;">
            <i class="{{ $alert['icon'] }} me-2"></i>
            {{ $alert['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endforeach
@endif

<!-- Statistiques générales -->
<div class="stats-grid stats-grid--compact mb-4">
    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Utilisateurs</span>
            <div class="stat-icon icon-black">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['total_users'] }}</div>
        <div class="stat-footer">
            @if(isset($monthlyStats['users']))
                @if($monthlyStats['users']['current'] > $monthlyStats['users']['previous'])
                    <span class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i> +{{ $monthlyStats['users']['current'] - $monthlyStats['users']['previous'] }} ce mois
                    </span>
                @else
                    <span class="stat-trend trend-down">
                        <i class="fas fa-arrow-down"></i> {{ $monthlyStats['users']['current'] - $monthlyStats['users']['previous'] }} ce mois
                    </span>
                @endif
            @endif
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Clients</span>
            <div class="stat-icon icon-gray">
                <i class="fas fa-user-tie"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['total_clients'] }}</div>
        <div class="stat-footer">
            @if(isset($monthlyStats['clients']))
                @if($monthlyStats['clients']['current'] > $monthlyStats['clients']['previous'])
                    <span class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i> +{{ $monthlyStats['clients']['current'] - $monthlyStats['clients']['previous'] }} ce mois
                    </span>
                @else
                    <span class="stat-trend trend-down">
                        <i class="fas fa-arrow-down"></i> {{ $monthlyStats['clients']['current'] - $monthlyStats['clients']['previous'] }} ce mois
                    </span>
                @endif
            @endif
        </div>
    </div>

    <!-- <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Projets</span>
            <div class="stat-icon icon-orange">
                <i class="fas fa-building"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['total_projets'] }}</div>
        <div class="stat-footer">
            <span class="stat-info">
                <i class="fas fa-check-circle"></i> Projets actifs
            </span>
        </div>
    </div> -->

    <!-- <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Souscriptions</span>
            <div class="stat-icon icon-purple">
                <i class="fas fa-file-contract"></i>
            </div>
        </div>
        <div class="stat-value">{{ $stats['total_souscriptions'] }}</div>
        <div class="stat-footer">
            @if(isset($monthlyStats['souscriptions']))
                @if($monthlyStats['souscriptions']['current'] > $monthlyStats['souscriptions']['previous'])
                    <span class="stat-trend trend-up">
                        <i class="fas fa-arrow-up"></i> +{{ $monthlyStats['souscriptions']['current'] - $monthlyStats['souscriptions']['previous'] }} ce mois
                    </span>
                @else
                    <span class="stat-trend trend-down">
                        <i class="fas fa-arrow-down"></i> {{ $monthlyStats['souscriptions']['current'] - $monthlyStats['souscriptions']['previous'] }} ce mois
                    </span>
                @endif
            @endif
        </div>
    </div> -->

    <!-- <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Total Paiements</span>
            <div class="stat-icon" style="background: #fef3c7; color: #d97706;">
                <i class="fas fa-euro-sign"></i>
            </div>
        </div>
        <div class="stat-value" style="font-size: 24px;">{{ number_format($stats['total_paiements'], 0, ',', ' ') }} €</div>
        <div class="stat-footer">
            <span class="stat-info">
                <i class="fas fa-chart-line"></i> Cumul total
            </span>
        </div>
    </div> -->
</div>

<!-- Graphiques -->
@include('shared.dashboard.charts-section')

<!-- Activités récentes -->
<div class="row mb-4">
    <div class="col-lg-12 mb-4">
        <div class="data-table-container">
            <div class="card-header-custom">
                <h5 class="card-title-custom">
                    <i class="fas fa-history me-2"></i>Activités récentes
                </h5>
                <a href="{{ route('admin.history.index') }}" class="btn-link-custom">
                    Voir tout <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="activity-timeline">
                @forelse($recentActivities as $activity)
                    <div class="activity-item">
                        <div class="activity-marker bg-{{ $activity->action_class }}"></div>
                        <div class="activity-content">
                            <div class="activity-title">{{ $activity->description }}</div>
                            <div class="activity-meta">
                                <span>
                                    <i class="fas fa-user"></i> {{ $activity->user?->name ?? 'Système' }}
                                </span>
                                <span class="ms-3">
                                    <i class="fas fa-clock"></i> {{ $activity->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <i class="fas fa-inbox fa-3x mb-3"></i>
                        <p>Aucune activité récente</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="data-table-container">
    <div class="card-header-custom">
        <h5 class="card-title-custom">
            <i class="fas fa-bolt me-2"></i>Actions rapides
        </h5>
    </div>
    <div style="padding: 20px;">
        <div class="row">
            <div class="col-md-3 mb-3">
                <a href="{{ route('admin.users.create') }}" class="action-btn">
                    <div class="action-btn-icon" style="background: #dbeafe; color: #2563eb;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="action-btn-text">
                        <div class="action-btn-title">Nouvel utilisateur</div>
                        <div class="action-btn-subtitle">Créer un compte</div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-3">
                <a href="{{ route('admin.history.index') }}" class="action-btn">
                    <div class="action-btn-icon" style="background: #e0e7ff; color: #4f46e5;">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="action-btn-text">
                        <div class="action-btn-title">Historique</div>
                        <div class="action-btn-subtitle">Voir les logs</div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 mb-3">
                <button onclick="window.print()" class="action-btn">
                    <div class="action-btn-icon" style="background: #f3f4f6; color: #6b7280;">
                        <i class="fas fa-print"></i>
                    </div>
                    <div class="action-btn-text">
                        <div class="action-btn-title">Imprimer</div>
                        <div class="action-btn-subtitle">Export PDF</div>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Stats Cards */
.stat-footer {
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid #f3f4f6;
}

.stat-trend {
    font-size: 13px;
    font-weight: 600;
}

.trend-up {
    color: #6b7280;
}

.trend-down {
    color: #ff7200;
}

.stat-info {
    font-size: 13px;
    color: #6b7280;
}

/* Card Headers */
.card-header-custom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.card-title-custom {
    margin: 0;
    font-size: 16px;
    font-weight: 600;
    color: #111827;
}

.btn-link-custom {
    color: #2563eb;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.2s;
}

.btn-link-custom:hover {
    color: #1d4ed8;
}

/* Metric Boxes */
.metric-box {
    padding: 20px 10px;
}

.metric-value {
    font-size: 32px;
    font-weight: 700;
    color: #111827;
    margin-bottom: 8px;
}

.metric-label {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 8px;
}

.metric-change {
    font-size: 14px;
    font-weight: 600;
}

.metric-change.positive {
    color: #6b7280;
}

.metric-change.negative {
    color: #ff7200;
}

/* Activity Timeline */
.activity-timeline {
    padding: 20px;
}

.activity-item {
    display: flex;
    gap: 16px;
    padding: 16px 0;
    border-bottom: 1px solid #f3f4f6;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-marker {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin-top: 4px;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-size: 14px;
    font-weight: 500;
    color: #111827;
    margin-bottom: 6px;
}

.activity-meta {
    font-size: 13px;
    color: #6b7280;
}

/* Info Banners */
.info-banner {
    display: flex;
    gap: 12px;
    padding: 16px;
    border-radius: 8px;
    border-left: 4px solid;
}

.info-banner.success {
    background: #f3f4f6;
    border-color: #9ca3af;
    color: #065f46;
}

.info-banner.warning {
    background: #fef3c7;
    border-color: #d97706;
    color: #92400e;
}

.info-banner i {
    font-size: 20px;
    margin-top: 2px;
}

.badge-custom {
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
}

.badge-primary { background: #dbeafe; color: #1e40af; }
.badge-success { background: #6b7280; color: #fff; }
.badge-warning { background: #fef3c7; color: #92400e; }
.badge-danger { background: #ffedd5; color: #c2410c; }

/* Action Buttons */
.action-btn {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.2s;
    height: 100%;
}

.action-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    border-color: #d1d5db;
}

.action-btn-icon {
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

.action-btn-text {
    flex: 1;
}

.action-btn-title {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.action-btn-subtitle {
    font-size: 13px;
    color: #6b7280;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
}

.empty-state i {
    color: #d1d5db;
}

.empty-state p {
    margin: 0;
    font-size: 14px;
}
</style>
@endpush

