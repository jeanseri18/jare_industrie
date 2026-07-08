@extends('layouts.dg')

@section('title', 'Tableau de bord - Administrateur technique')

@section('content')
@if(!empty($alerts))
    @foreach($alerts as $alert)
        <div class="alert alert-{{ $alert['type'] }} alert-dismissible fade show" role="alert" style="border-radius: 8px; border-left: 4px solid currentColor;">
            <i class="{{ $alert['icon'] }} me-2"></i>
            {{ $alert['message'] }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endforeach
@endif

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

    <div class="stat-card">
        <div class="stat-header">
            <span class="stat-title">Sauvegardes</span>
            <div class="stat-icon" style="background: #e0e7ff; color: #4f46e5;">
                <i class="fas fa-database"></i>
            </div>
        </div>
        <div class="stat-value">{{ $recentBackups->count() }}</div>
        <div class="stat-footer">
            @if($stats['lastBackup'])
                <span class="stat-info">
                    <i class="fas fa-clock"></i> {{ $stats['lastBackup']->created_at->diffForHumans() }}
                </span>
            @else
                <span class="stat-trend trend-down">
                    <i class="fas fa-exclamation-triangle"></i> Aucune sauvegarde
                </span>
            @endif
        </div>
    </div>
</div>

@include('shared.dashboard.charts-section')

@endsection

@push('styles')
<style>
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
    font-size: 18px;
    flex-shrink: 0;
}

.action-btn-title {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
}

.action-btn-subtitle {
    font-size: 13px;
    color: #6b7280;
}

.empty-state {
    text-align: center;
    padding: 40px 20px;
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

