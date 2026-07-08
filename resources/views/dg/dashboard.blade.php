@extends('layouts.dg')

@section('title', 'Tableau de bord - Directeur Général')

@section('content')
@include('shared.dashboard-content', [
    'dashboardTitle' => 'Tableau de bord Directeur Général',
    'dashboardSubtitle' => 'Vue d\'ensemble de l\'activité immobilière',
])
@endsection
