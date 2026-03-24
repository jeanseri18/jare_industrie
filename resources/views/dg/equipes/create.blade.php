@extends('layouts.dg')

@section('title', 'Suivi des équipes / Ajouter un utilisateur')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-user-plus me-2"></i>
            Suivi des équipes / Ajouter un utilisateur
            <a href="{{ route('dg.equipes.index') }}" class="btn btn-secondary btn-sm ms-3">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div style="padding: 20px;">
        <form method="POST" action="{{ route('dg.equipes.store') }}" class="needs-validation" novalidate>
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nom complet</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Bamba Moussa" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="bambamoussa@gmail.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label me-3">Rôle :</label>
                <div class="d-flex flex-wrap gap-4">
                    <div class="form-check">
                        <input class="form-check-input role-option" type="checkbox" id="role_operateur" name="role" value="operateur" checked>
                        <label class="form-check-label" for="role_operateur">Opérateur</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input role-option" type="checkbox" id="role_comptable" name="role" value="comptable">
                        <label class="form-check-label" for="role_comptable">Comptable</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input role-option" type="checkbox" id="role_dg" name="role" value="dg">
                        <label class="form-check-label" for="role_dg">DG</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input role-option" type="checkbox" id="role_admin_tech" name="role" value="admin_technique">
                        <label class="form-check-label" for="role_admin_tech">Administrateur technique</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input role-option" type="checkbox" id="role_dircom" name="role" value="chef_commercial">
                        <label class="form-check-label" for="role_dircom">Directeur commercial</label>
                    </div>
                </div>
            </div>

            <div class="row align-items-end mt-4">
                <div class="col-md-6">
                    <label for="password_display" class="form-label">Mot de passe</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="password_display" placeholder="Générer un mot de passe" readonly>
                        <button type="button" class="btn btn-outline-secondary" id="btn_generate_pwd">Générer un mot de passe</button>
                    </div>
                    <small class="text-muted d-block mt-2">Un lien envoyé par email</small>
                    <input type="hidden" id="password" name="password">
                </div>
                <div class="col-md-6 text-end">
                    <button type="submit" class="btn btn-primary">Ajouter un utilisateur</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    // Une seule sélection parmi les rôles (checkbox style)
    const roleInputs = document.querySelectorAll('.role-option');
    roleInputs.forEach((inp) => {
        inp.addEventListener('change', () => {
            if (inp.checked) {
                roleInputs.forEach((other) => { if (other !== inp) other.checked = false; });
            }
        });
    });

    // Génération d'un mot de passe
    const btn = document.getElementById('btn_generate_pwd');
    const display = document.getElementById('password_display');
    const hidden = document.getElementById('password');

    function genPwd(len = 12) {
        const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz23456789!@#$%';
        let p = '';
        for (let i = 0; i < len; i++) {
            p += chars[Math.floor(Math.random() * chars.length)];
        }
        return p;
    }

    btn && btn.addEventListener('click', () => {
        const pwd = genPwd();
        display.value = pwd;
        hidden.value = pwd;
    });
})();
</script>
@endpush
