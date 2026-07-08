@extends('layouts.dg')

@section('title', 'Modifier le Projet')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-edit me-2"></i>
            Modifier le Projet
        </div>
        <a href="{{ route('dg.projets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div style="padding: 20px;">
        <form method="POST" action="{{ route('dg.projets.update', $projet) }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du Projet *</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" name="nom" value="{{ old('nom', $projet->nom) }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="numero_agrement" class="form-label">Numéro d’agrément</label>
                        <input type="text" class="form-control @error('numero_agrement') is-invalid @enderror"
                               id="numero_agrement" name="numero_agrement" value="{{ old('numero_agrement', $projet->numero_agrement) }}">
                        @error('numero_agrement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_agrement" class="form-label">Date d’agrément</label>
                        <input type="date" class="form-control @error('date_agrement') is-invalid @enderror"
                               id="date_agrement" name="date_agrement"
                               value="{{ old('date_agrement', $projet->date_agrement?->format('Y-m-d')) }}">
                        @error('date_agrement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="localisation" class="form-label">Localisation</label>
                        <input type="text" class="form-control @error('localisation') is-invalid @enderror" 
                               id="localisation" name="localisation" value="{{ old('localisation', $projet->localisation) }}">
                        @error('localisation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="titre_foncier" class="form-label">Titre Foncier</label>
                        <input type="text" class="form-control @error('titre_foncier') is-invalid @enderror"
                               id="titre_foncier" name="titre_foncier" value="{{ old('titre_foncier', $projet->titre_foncier) }}">
                        @error('titre_foncier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="circonscription_fonciere" class="form-label">Circonscription foncière</label>
                        <input type="text" class="form-control @error('circonscription_fonciere') is-invalid @enderror"
                               id="circonscription_fonciere" name="circonscription_fonciere" value="{{ old('circonscription_fonciere', $projet->circonscription_fonciere) }}">
                        @error('circonscription_fonciere')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="superficie" class="form-label">Superficie (m²)</label>
                        <input type="number" step="0.01" class="form-control @error('superficie') is-invalid @enderror" 
                               id="superficie" name="superficie" value="{{ old('superficie', $projet->superficie) }}" min="0">
                        @error('superficie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nb_logements" class="form-label">Nombre de Logements</label>
                        <input type="number" class="form-control @error('nb_logements') is-invalid @enderror" 
                               id="nb_logements" name="nb_logements" value="{{ old('nb_logements', $projet->nb_logements) }}" min="0">
                        @error('nb_logements')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>







            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="est_actif" name="est_actif" value="1" {{ old('est_actif', $projet->est_actif) ? 'checked' : '' }}>
                        <label class="form-check-label" for="est_actif">Projet Actif</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="est_mutuelle" name="est_mutuelle" value="1" {{ old('est_mutuelle', $projet->est_mutuelle) ? 'checked' : '' }}>
                        <label class="form-check-label" for="est_mutuelle">Projet Mutuelle</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="mutuelle_id" class="form-label">Mutuelle Associée</label>
                <select class="form-select @error('mutuelle_id') is-invalid @enderror" id="mutuelle_id" name="mutuelle_id">
                    <option value="">Sélectionner une mutuelle (optionnel)</option>
                    @foreach($mutuelles as $mutuelle)
                        <option value="{{ $mutuelle->id }}" {{ old('mutuelle_id', $projet->mutuelle_id) == $mutuelle->id ? 'selected' : '' }}>
                            {{ $mutuelle->nom }}
                        </option>
                    @endforeach
                </select>
                @error('mutuelle_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
                <a href="{{ route('dg.projets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>
@endpush
