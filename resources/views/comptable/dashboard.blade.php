@extends('layouts.comptable')

@section('title', 'Tableau de bord - Comptable')

@section('content')
@include('shared.dashboard-content', [
    'dashboardTitle' => 'Tableau de bord Comptable',
    'dashboardSubtitle' => 'Suivi financier et encaissements',
])
@endsection
