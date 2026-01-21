@extends('layouts.dg')

@section('title', 'Créer un Projet')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-plus-circle me-2"></i>
            Créer un Nouveau Projet
        </div>
        <a href="{{ route('dg.projets.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div style="padding: 20px;">
        <form method="POST" action="{{ route('dg.projets.store') }}" class="needs-validation" novalidate>
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom du Projet *</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" name="nom" value="{{ old('nom') }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="localisation" class="form-label">Localisation</label>
                        <input type="text" class="form-control @error('localisation') is-invalid @enderror" 
                               id="localisation" name="localisation" value="{{ old('localisation') }}">
                        @error('localisation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="superficie" class="form-label">Superficie (m²)</label>
                        <input type="number" step="0.01" class="form-control @error('superficie') is-invalid @enderror" 
                               id="superficie" name="superficie" value="{{ old('superficie') }}" min="0">
                        @error('superficie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nb_logements" class="form-label">Nombre de Logements</label>
                        <input type="number" class="form-control @error('nb_logements') is-invalid @enderror" 
                               id="nb_logements" name="nb_logements" value="{{ old('nb_logements') }}" min="0">
                        @error('nb_logements')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>







            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="est_actif" name="est_actif" value="1" {{ old('est_actif', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_actif">Projet Actif</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="est_mutuelle" name="est_mutuelle" value="1" {{ old('est_mutuelle') ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_mutuelle">Projet Mutuelle</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="mutuelle_id" class="form-label">Mutuelle Associée</label>
                <select class="form-select @error('mutuelle_id') is-invalid @enderror" 
                        id="mutuelle_id" name="mutuelle_id">
                    <option value="">Sélectionner une mutuelle (optionnel)</option>
                    @foreach($mutuelles as $mutuelle)
                        <option value="{{ $mutuelle->id }}" {{ old('mutuelle_id') == $mutuelle->id ? 'selected' : '' }}>
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
                    <i class="fas fa-save me-1"></i> Créer le Projet
                </button>
                <a href="{{ route('dg.projets.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times me-1"></i> Annuler
                </a>
            </div>
        </form>
    </div>
</div>

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
@endsection