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
                        <input type="text" class="form-control @error('site_web') is-invalid @enderror" 
                               id="site_web" name="site_web" value="{{ old('site_web', $mutuelle->site_web) }}">
                        @error('site_web')
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
                <div class="col-12">
                    <div id="biens-container"></div>
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

        // Gestion de l'affichage des biens
        const projectSelect = document.getElementById('project_id');
        const container = document.getElementById('biens-container');
        const existingBiens = @json($mutuelle->biens->mapWithKeys(function ($item) { return [$item->id => $item->pivot->prix_special]; }));
        
        if (projectSelect) {
            projectSelect.addEventListener('change', function() {
                const projectId = this.value;
                container.innerHTML = '<div class="text-center my-3"><i class="fas fa-spinner fa-spin"></i> Chargement...</div>';
                
                if (projectId) {
                    // Use a dummy ID and replace it with the actual project ID
                    const url = "{{ route('dg.mutuelles.getBiens', ['projet' => 'PROJET_ID']) }}".replace('PROJET_ID', projectId);
                    
                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(biens => {
                            if (biens.length > 0) {
                                let html = '<h5 class="mt-4 mb-3">Prix Spéciaux par Type de Logement</h5>';
                                html += '<div class="row">';
                                biens.forEach(bien => {
                                    const prixFormatted = new Intl.NumberFormat('fr-FR').format(bien.prix);
                                    const existingPrice = existingBiens[bien.id] !== undefined ? parseInt(existingBiens[bien.id]) : '';
                                    html += `
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">${bien.titre} (${bien.type}) <br><small class="text-muted">Prix Standard: ${prixFormatted} FCFA</small></label>
                                            <div class="input-group">
                                                <input type="number" name="biens[${bien.id}]" class="form-control" placeholder="Prix spécial" min="0" step="1" value="${existingPrice}">
                                                <span class="input-group-text">FCFA</span>
                                            </div>
                                        </div>
                                    `;
                                });
                                html += '</div>';
                                container.innerHTML = html;
                            } else {
                                container.innerHTML = '<div class="alert alert-info mt-3">Aucun bien immobilier trouvé pour ce projet.</div>';
                            }
                        })
                        .catch(error => {
                            console.error('Erreur:', error);
                            container.innerHTML = '<div class="alert alert-danger mt-3">Erreur lors du chargement des biens.</div>';
                        });
                } else {
                    container.innerHTML = '';
                }
            });
            
            // Trigger change if value is pre-selected
            if (projectSelect.value) {
                projectSelect.dispatchEvent(new Event('change'));
            }
        }
    }, false);
})();
</script>
@endsection
