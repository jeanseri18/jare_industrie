@extends('layouts.admin')

@section('title', 'Mot de passe client')
@section('subtitle', 'Modifier le mot de passe du client')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-key me-2"></i>
            Modifier le mot de passe
        </div>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div style="padding: 20px;">
        <div class="mb-3">
            <div class="fw-semibold">Client</div>
            <div class="text-muted">{{ $user->name }} — {{ $user->email }}</div>
        </div>

        <form method="POST" action="{{ route('admin.clients.password.update', $user) }}" class="needs-validation" novalidate>
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
                        <i class="fas fa-save"></i> Mettre à jour le mot de passe
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        Array.prototype.filter.call(forms, function(form) {
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

