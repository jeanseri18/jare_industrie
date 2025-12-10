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

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="pourcentage_apport" class="form-label">Pourcentage d'Apport (%)</label>
                        <input type="number" class="form-control @error('pourcentage_apport') is-invalid @enderror" 
                               id="pourcentage_apport" name="pourcentage_apport" value="{{ old('pourcentage_apport', $projet->pourcentage_apport) }}" min="0" max="100" required>
                        @error('pourcentage_apport')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="frais_souscription" class="form-label">Frais de Souscription</label>
                        <input type="number" step="0.01" class="form-control @error('frais_souscription') is-invalid @enderror" 
                               id="frais_souscription" name="frais_souscription" value="{{ old('frais_souscription', $projet->frais_souscription) }}" min="0" required>
                        @error('frais_souscription')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="isduplex" name="isduplex" value="1" {{ old('isduplex', $projet->isduplex) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isduplex">Duplex</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="isappartement" name="isappartement" value="1" {{ old('isappartement', $projet->isappartement) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isappartement">Appartement</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="isvillabase" name="isvillabase" value="1" {{ old('isvillabase', $projet->isvillabase) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isvillabase">Villa</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="isterrains" name="isterrains" value="1" {{ old('isterrains', $projet->isterrains) ? 'checked' : '' }}>
                        <label class="form-check-label" for="isterrains">Terrain</label>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="prix_duplex" class="form-label">Prix Duplex</label>
                        <input type="number" step="0.01" class="form-control @error('prix_duplex') is-invalid @enderror" 
                               id="prix_duplex" name="prix_duplex" value="{{ old('prix_duplex', $projet->prix_duplex) }}" min="0">
                        @error('prix_duplex')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="prix_appartement" class="form-label">Prix Appartement</label>
                        <input type="number" step="0.01" class="form-control @error('prix_appartement') is-invalid @enderror" 
                               id="prix_appartement" name="prix_appartement" value="{{ old('prix_appartement', $projet->prix_appartement) }}" min="0">
                        @error('prix_appartement')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="prix_villa" class="form-label">Prix Villa</label>
                        <input type="number" step="0.01" class="form-control @error('prix_villa') is-invalid @enderror" 
                               id="prix_villa" name="prix_villa" value="{{ old('prix_villa', $projet->prix_villa) }}" min="0">
                        @error('prix_villa')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="prix_terrains" class="form-label">Prix Terrain</label>
                        <input type="number" step="0.01" class="form-control @error('prix_terrains') is-invalid @enderror" 
                               id="prix_terrains" name="prix_terrains" value="{{ old('prix_terrains', $projet->prix_terrains) }}" min="0">
                        @error('prix_terrains')
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