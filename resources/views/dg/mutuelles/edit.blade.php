@extends('layouts.dg')

@section('title', 'Modifier une Mutuelle')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-building me-2"></i>
            Modifier une Mutuelle
        </div>
        <a href="{{ route('dg.mutuelles.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div style="padding: 20px;">
        <form method="POST" action="{{ route('dg.mutuelles.update', $mutuelle) }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom de la Mutuelle *</label>
                        <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                               id="nom" name="nom" value="{{ old('nom', $mutuelle->nom) }}" required>
                        @error('nom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="code" class="form-label">Code de la Mutuelle *</label>
                        <input type="text" class="form-control @error('code') is-invalid @enderror" 
                               id="code" name="code" value="{{ old('code', $mutuelle->code) }}" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="site_web" class="form-label">Site Web</label>
                        <input type="url" class="form-control @error('site_web') is-invalid @enderror" 
                               id="site_web" name="site_web" value="{{ old('site_web', $mutuelle->site_web) }}">
                        @error('site_web')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="taux_reduction" class="form-label">Taux de Réduction (%) *</label>
                        <input type="number" step="0.01" class="form-control @error('taux_reduction') is-invalid @enderror" 
                               id="taux_reduction" name="taux_reduction" value="{{ old('taux_reduction', $mutuelle->taux_reduction) }}" min="0" max="100" required>
                        @error('taux_reduction')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nom_contact" class="form-label">Nom du Contact</label>
                        <input type="text" class="form-control @error('nom_contact') is-invalid @enderror" 
                               id="nom_contact" name="nom_contact" value="{{ old('nom_contact', $mutuelle->nom_contact) }}">
                        @error('nom_contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="telephone_contact" class="form-label">Téléphone du Contact</label>
                        <input type="text" class="form-control @error('telephone_contact') is-invalid @enderror" 
                               id="telephone_contact" name="telephone_contact" value="{{ old('telephone_contact', $mutuelle->telephone_contact) }}">
                        @error('telephone_contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email_contact" class="form-label">E-mail du Contact</label>
                        <input type="email" class="form-control @error('email_contact') is-invalid @enderror" 
                               id="email_contact" name="email_contact" value="{{ old('email_contact', $mutuelle->email_contact) }}">
                        @error('email_contact')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="project_id" class="form-label">Projet Associé (Optionnel)</label>
                        <div class="input-group">
                            <select class="form-select @error('project_id') is-invalid @enderror" id="project_id" name="project_id">
                                <option value="">-- Sélectionner un projet --</option>
                                @foreach($projets as $id => $nom)
                                    <option value="{{ $id }}" {{ old('project_id', $mutuelle->project_id) == $id ? 'selected' : '' }}>{{ $nom }}</option>
                                @endforeach
                            </select>
                            <a href="{{ route('dg.projets.create') }}" target="_blank" class="btn btn-outline-secondary" title="Créer un nouveau projet">
                                <i class="fas fa-plus"></i> Créer
                            </a>
                        </div>
                        <small class="form-text text-muted">Si aucun projet n'existe, cliquez sur "Créer" pour en ajouter un nouveau</small>
                        @error('project_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $mutuelle->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="est_active" name="est_active" value="1" {{ old('est_active', $mutuelle->est_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="est_active">Mutuelle Active</label>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Mettre à jour
                    </button>
                    <a href="{{ route('dg.mutuelles.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Validation Bootstrap
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