@extends('layouts.dg')

@section('title', 'Projet créé')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="card-title-custom">
            <i class="fas fa-check-circle text-success me-2"></i>
            Projet enregistré
        </div>
    </div>
    <div style="padding: 24px;">
        <div class="alert alert-success mb-4">
            <strong>{{ $projet->nom }}</strong> a été créé avec succès.
        </div>
        <p class="mb-4">
            Souhaitez-vous renseigner tout de suite l’<strong>inventaire des îlots et des lots</strong> pour ce projet, ou préférez-vous retourner à la liste des projets ?
        </p>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('dg.projets.lots.index', $projet) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-map-marked-alt me-2"></i>
                Créer / gérer les lots
            </a>
            <a href="{{ route('dg.projets.index') }}" class="btn btn-outline-secondary btn-lg">
                <i class="fas fa-list me-2"></i>
                Retour à la liste des projets
            </a>
        </div>
        <p class="text-muted small mt-4 mb-0">
            Vous pourrez toujours accéder aux lots plus tard depuis la liste des projets (bouton « Îlots & lots »).
        </p>
    </div>
</div>
@endsection
