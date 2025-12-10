@extends('layouts.admin')

@section('title', 'Modifier l\'Utilisateur')
@section('subtitle', 'Modifier les informations et le mot de passe')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-user-edit me-2"></i>
            Modifier l\'Utilisateur
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>
    
    <div style="padding: 20px;">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs mb-4" id="userEditTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info" type="button" role="tab">
                    <i class="fas fa-user me-1"></i> Informations
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="password-tab" data-bs-toggle="tab" data-bs-target="#password" type="button" role="tab">
                    <i class="fas fa-lock me-1"></i> Mot de passe
                </button>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <!-- Informations tab -->
            <div class="tab-pane fade show active" id="info" role="tabpanel">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom complet</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                <div class="invalid-feedback">
                                    Veuillez saisir le nom complet.
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                <div class="invalid-feedback">
                                    Veuillez saisir une adresse email valide.
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
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                    <option value="dg" {{ old('role', $user->role) == 'dg' ? 'selected' : '' }}>Directeur Général</option>
                                    <option value="comptable" {{ old('role', $user->role) == 'comptable' ? 'selected' : '' }}>Comptable</option>
                                    <option value="chef_commercial" {{ old('role', $user->role) == 'chef_commercial' ? 'selected' : '' }}>Chef Commercial</option>
                                    <option value="operateur" {{ old('role', $user->role) == 'operateur' ? 'selected' : '' }}>Opérateur de Saisie</option>
                                </select>
                                <div class="invalid-feedback">
                                    Veuillez sélectionner un rôle.
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" value="{{ old('telephone', $user->telephone) }}">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour les informations
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Password tab -->
            <div class="tab-pane fade" id="password" role="tabpanel">
                <form method="POST" action="{{ route('admin.users.password.update', $user) }}" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Nouveau mot de passe</label>
                                <input type="password" class="form-control" id="new_password" name="password" required>
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
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key"></i> Mettre à jour le mot de passe
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
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