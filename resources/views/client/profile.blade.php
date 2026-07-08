@extends('layouts.client')

@section('title', 'Mon profil')

@section('content')
<x-page-header :title="$user->prenom . ' ' . $user->nom" :subtitle="$user->email" />
<x-alert />

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <h3 class="text-lg font-semibold text-slate-900 mb-4">Modifier mon profil</h3>

            <form action="{{ route('client.profile.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Prénom</label>
                    <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nom</label>
                    <input type="text" name="nom" class="form-control" value="{{ old('nom', $user->nom) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Téléphone</label>
                    <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $user->telephone) }}" placeholder="Entrez votre numéro de téléphone">
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                    <a href="{{ route('client.dashboard') }}" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="data-table-container mb-4">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Actions rapides</h3>
            </div>
            <div class="list-body p-3 d-grid gap-2">
                <button type="button" class="btn-secondary w-100" data-bs-toggle="modal" data-bs-target="#passwordModal">
                    <i class="fas fa-key me-1"></i> Changer le mot de passe
                </button>
                <a href="{{ route('client.historique') }}" class="btn-secondary w-100">
                    <i class="fas fa-history me-1"></i> Historique de paiement
                </a>
                <a href="{{ route('client.notifications') }}" class="btn-secondary w-100">
                    <i class="fas fa-bell me-1"></i> Notifications
                </a>
                <a href="{{ route('client.documents') }}" class="btn-secondary w-100">
                    <i class="fas fa-file-pdf me-1"></i> Mes documents
                </a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="passwordModal" tabindex="-1" aria-labelledby="passwordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="passwordModalLabel">Changer le mot de passe</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <form action="{{ route('client.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nouveau mot de passe</label>
                        <input type="password" name="new_password" class="form-control" required minlength="8">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required minlength="8">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save me-1"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
