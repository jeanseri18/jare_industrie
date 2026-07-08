@extends('layouts.dg')

@section('title', 'Bien immobilier créé')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div class="card-title-custom">
            <i class="fas fa-check-circle text-success me-2"></i>
            Bien immobilier enregistré
        </div>
    </div>
    <div style="padding: 24px;">
        <div class="alert alert-success mb-4">
            <strong>{{ $bien->titre }}</strong> a été ajouté au projet <strong>{{ $projet->nom }}</strong>.
        </div>
        <p class="mb-4">
            Que souhaitez-vous faire ensuite ?
        </p>
        <div class="d-flex flex-wrap gap-3">
            <a href="{{ route('dg.projets.biens.index', $projet) }}" class="btn btn-primary btn-lg">
                <i class="fas fa-list me-2"></i>
                Voir la liste des biens
            </a>
            <a href="{{ route('dg.projets.biens.create', $projet) }}{{ $ilot ? '?ilot='.urlencode($ilot) : '' }}" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-plus me-2"></i>
                Ajouter un autre bien
            </a>
            @if($ilot)
                <a href="{{ route('dg.projets.lots.ilot.lots', [$projet, $ilot]) }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-map-marked-alt me-2"></i>
                    Retour aux lots de l’îlot
                </a>
            @else
                <a href="{{ route('dg.projets.lots.index', $projet) }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-th-list me-2"></i>
                    Liste des îlots &amp; lots
                </a>
            @endif
        </div>
        <p class="text-muted small mt-4 mb-0">
            @if($ilot)
                Le lien « Retour aux lots de l’îlot » vous ramène à la page où vous pouvez associer ce bien aux lots.
            @else
                La « Liste des îlots &amp; lots » permet de générer ou gérer les lots du projet.
            @endif
        </p>
    </div>
</div>
@endsection
