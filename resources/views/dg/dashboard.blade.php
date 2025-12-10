@extends('layouts.dg')

@section('title', 'Tableau de bord - Directeur Général')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Tableau de bord Directeur Général</h1>
    </div>

    <!-- Statistiques -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Total Clients</div>
                <div class="stat-icon icon-blue">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalClients ?? 0 }}</div>
            <div class="text-muted">Clients enregistrés</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Total Projets</div>
                <div class="stat-icon icon-orange">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalProjets ?? 0 }}</div>
            <div class="text-muted">Projets actifs</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Total Mutuelles</div>
                <div class="stat-icon icon-green">
                    <i class="fas fa-handshake"></i>
                </div>
            </div>
            <div class="stat-value">{{ $totalMutuelles ?? 0 }}</div>
            <div class="text-muted">Mutuelles actives</div>
        </div>

        <div class="stat-card">
            <div class="stat-header">
                <div class="stat-title">Projets Actifs</div>
                <div class="stat-icon icon-purple">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-value">{{ $projetsActifs ?? 0 }}</div>
            <div class="text-muted">En cours</div>
        </div>
    </div>

  
</div>
@endsection