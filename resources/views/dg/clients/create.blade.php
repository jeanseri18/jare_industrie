@extends('layouts.dg')

@section('title', 'Créer un Nouveau Client')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-user-plus me-2"></i>
            Créer un Nouveau Client
        </div>
        <a href="{{ route('dg.clients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div style="padding: 20px;">
        <form method="POST" action="{{ route('dg.clients.store') }}" class="needs-validation" novalidate>
            @csrf
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="nom_prenom" class="form-label">Nom et Prénom *</label>
                        <input type="text" class="form-control @error('nom_prenom') is-invalid @enderror" 
                               id="nom_prenom" name="nom_prenom" value="{{ old('nom_prenom') }}" required>
                        @error('nom_prenom')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="date_naissance" class="form-label">Date de Naissance *</label>
                        <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                               id="date_naissance" name="date_naissance" value="{{ old('date_naissance') }}" required>
                        @error('date_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="lieu_naissance" class="form-label">Lieu de Naissance *</label>
                        <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                               id="lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance') }}" required>
                        @error('lieu_naissance')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nationalite" class="form-label">Nationalité *</label>
                        <input type="text" class="form-control @error('nationalite') is-invalid @enderror" 
                               id="nationalite" name="nationalite" value="{{ old('nationalite') }}" required>
                        @error('nationalite')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="telephone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control @error('telephone') is-invalid @enderror" 
                               id="telephone" name="telephone" value="{{ old('telephone') }}">
                        @error('telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="salaire_mensuel" class="form-label">Salaire Mensuel</label>
                        <input type="number" class="form-control @error('salaire_mensuel') is-invalid @enderror" 
                               id="salaire_mensuel" name="salaire_mensuel" value="{{ old('salaire_mensuel') }}" min="0">
                        @error('salaire_mensuel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="categorie_client" class="form-label">Catégorie Client *</label>
                        <select class="form-select @error('categorie_client') is-invalid @enderror" 
                                id="categorie_client" name="categorie_client" required>
                            <option value="">Sélectionner une catégorie</option>
                            <option value="individuel" {{ old('categorie_client') == 'individuel' ? 'selected' : '' }}>Individuel</option>
                            <option value="association" {{ old('categorie_client') == 'association' ? 'selected' : '' }}>Association</option>
                            <option value="syndicat" {{ old('categorie_client') == 'syndicat' ? 'selected' : '' }}>Syndicat</option>
                            <option value="diaspora" {{ old('categorie_client') == 'diaspora' ? 'selected' : '' }}>Diaspora</option>
                            <option value="mutuelle" {{ old('categorie_client') == 'mutuelle' ? 'selected' : '' }}>Mutuelle</option>
                        </select>
                        @error('categorie_client')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="situation_matrimoniale" class="form-label">Situation Matrimoniale *</label>
                        <select class="form-select @error('situation_matrimoniale') is-invalid @enderror" 
                                id="situation_matrimoniale" name="situation_matrimoniale" required>
                            <option value="">Sélectionner</option>
                            <option value="celibataire" {{ old('situation_matrimoniale') == 'celibataire' ? 'selected' : '' }}>Célibataire</option>
                            <option value="marie" {{ old('situation_matrimoniale') == 'marie' ? 'selected' : '' }}>Marié(e)</option>
                            <option value="divorce" {{ old('situation_matrimoniale') == 'divorce' ? 'selected' : '' }}>Divorcé(e)</option>
                            <option value="veuf" {{ old('situation_matrimoniale') == 'veuf' ? 'selected' : '' }}>Veuf/Veuve</option>
                        </select>
                        @error('situation_matrimoniale')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nombre_enfants" class="form-label">Nombre d'Enfants</label>
                        <input type="number" class="form-control @error('nombre_enfants') is-invalid @enderror" 
                               id="nombre_enfants" name="nombre_enfants" value="{{ old('nombre_enfants', 0) }}" min="0">
                        @error('nombre_enfants')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="ayant_droit" class="form-label">Ayant Droit</label>
                        <input type="text" class="form-control @error('ayant_droit') is-invalid @enderror" 
                               id="ayant_droit" name="ayant_droit" value="{{ old('ayant_droit') }}">
                        @error('ayant_droit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nature_piece" class="form-label">Nature Pièce d'Identité</label>
                        <select class="form-select @error('nature_piece') is-invalid @enderror" 
                                id="nature_piece" name="nature_piece">
                            <option value="">Sélectionner</option>
                            <option value="cni" {{ old('nature_piece') == 'cni' ? 'selected' : '' }}>CNI</option>
                            <option value="passeport" {{ old('nature_piece') == 'passeport' ? 'selected' : '' }}>Passeport</option>
                            <option value="carte_consulaire" {{ old('nature_piece') == 'carte_consulaire' ? 'selected' : '' }}>Carte Consulaire</option>
                            <option value="id" {{ old('nature_piece') == 'id' ? 'selected' : '' }}>ID</option>
                        </select>
                        @error('nature_piece')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="numero_piece" class="form-label">Numéro Pièce d'Identité</label>
                        <input type="text" class="form-control @error('numero_piece') is-invalid @enderror" 
                               id="numero_piece" name="numero_piece" value="{{ old('numero_piece') }}">
                        @error('numero_piece')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="fichier_piece" class="form-label">Fichier Pièce d'Identité</label>
                        <input type="text" class="form-control @error('fichier_piece') is-invalid @enderror" 
                               id="fichier_piece" name="fichier_piece" value="{{ old('fichier_piece') }}">
                        @error('fichier_piece')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="mb-3">
                <label for="mutuelle_id" class="form-label">Mutuelle</label>
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
            
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Créer le Client
                    </button>
                    <a href="{{ route('dg.clients.index') }}" class="btn btn-secondary">
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