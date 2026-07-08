@extends('layouts.dg')

@section('title', $dashboardTitle ?? 'Tableau de bord')

@section('content')
<x-page-header :title="$dashboardTitle ?? 'Tableau de bord'" :subtitle="$dashboardSubtitle ?? null" />
<x-alert />

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 mb-6">
    <x-stat-card label="Total clients" :value="$totalClients ?? 0" footer="Clients enregistrés" />
    <x-stat-card label="Total projets" :value="$totalProjets ?? 0" footer="Programmes immobiliers" />
    <x-stat-card label="Mutuelles" :value="$totalMutuelles ?? 0" footer="Partenaires actifs" />
    <x-stat-card label="Projets actifs" :value="$projetsActifs ?? 0" footer="En cours de commercialisation" />
</div>

@if(isset($souscriptionsEnAttente))
<div class="grid grid-cols-1 gap-4 md:grid-cols-3 mb-6">
    <x-stat-card label="Souscriptions en attente" :value="$souscriptionsEnAttente" />
    <x-stat-card label="Souscriptions en cours" :value="$souscriptionsEnCours ?? 0" />
    <x-stat-card label="Souscriptions soldées" :value="$souscriptionsSoldees ?? 0" />
</div>
@endif

@if(!empty($slot))
    {{ $slot }}
@endif
@endsection
