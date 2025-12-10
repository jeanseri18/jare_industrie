@extends('layouts.admin')

@section('title', 'Créer un Utilisateur')
@section('subtitle', 'Ajouter un nouvel utilisateur au système')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-user-plus me-2"></i>
            Créer un Utilisateur
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    <div style="padding: 20px;">

    <form method="POST" action="{{ route('admin.users.store') }}" class="needs-validation" novalidate>
        @csrf
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label">Nom complet</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                    <div class="invalid-feedback">
                        Veuillez saisir le nom complet.
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                    <div class="invalid-feedback">
                        Veuillez saisir une adresse email valide.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="invalid-feedback">
                        Veuillez saisir un mot de passe.
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                    <div class="invalid-feedback">
                        Veuillez confirmer le mot de passe.
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="role" class="form-label">Rôle</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="">Sélectionner un rôle</option>
                        <option value="admin">Administrateur</option>
                        <option value="dg">Directeur Général</option>
                        <option value="comptable">Comptable</option>
                        <option value="chef_commercial">Chef Commercial</option>
                        <option value="operateur">Opérateur de Saisie</option>
                    </select>
                    <div class="invalid-feedback">
                        Veuillez sélectionner un rôle.
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone">
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Créer l'utilisateur
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
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