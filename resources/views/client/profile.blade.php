@extends('layouts.client')

@section('content')
<style>
    .profile-container {
        padding: 0;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Header Section */
    .profile-header {
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(59, 89, 152, 0.3);
        color: white;
    }

    .profile-header-content {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: #3b5998;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    }

    .profile-info h1 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .profile-info p {
        font-size: 1rem;
        opacity: 0.9;
    }

    /* Profile Content Grid */
    .profile-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
    }

    /* Profile Form Section */
    .profile-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .section-title i {
        color: #3b5998;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        padding: 0.9rem 1.2rem;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f8fafc;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b5998;
        background: white;
        box-shadow: 0 0 0 3px rgba(59, 89, 152, 0.1);
    }

    .form-control:disabled {
        background: #f1f5f9;
        cursor: not-allowed;
    }

    .btn {
        padding: 0.9rem 2rem;
        border-radius: 10px;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 89, 152, 0.3);
    }

    .btn-secondary {
        background: #f8fafc;
        color: #64748b;
        border: 2px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
    }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .action-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }

    .action-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .action-card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .action-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .action-icon.blue {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .action-icon.green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .action-icon.orange {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .action-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
    }

    .action-description {
        font-size: 0.9rem;
        color: #64748b;
    }

    /* Alert Messages */
    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border-left: 4px solid #059669;
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border-left: 4px solid #dc2626;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        max-width: 500px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .modal-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
    }

    .modal-close {
        background: transparent;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #64748b;
    }

    @media (max-width: 1024px) {
        .profile-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .profile-header-content {
            flex-direction: column;
            text-align: center;
        }

        .profile-info h1 {
            font-size: 1.5rem;
        }
    }
</style>

<div class="profile-container">
    <!-- Header Profile -->
    <div class="profile-header">
        <div class="profile-header-content">
            <div class="profile-avatar">
                {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
            </div>
            <div class="profile-info">
                <h1>{{ $user->prenom }} {{ $user->nom }}</h1>
                <p><i class="fas fa-envelope"></i> {{ $user->email }}</p>
            </div>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Profile Content -->
    <div class="profile-grid">
        <!-- Main Profile Section -->
        <div>
            <div class="profile-section">
                <h2 class="section-title">
                    <i class="fas fa-user-edit"></i>
                    Modifier Mon Profil
                </h2>

                <form action="{{ route('client.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="form-label">Prénom</label>
                        <input type="text" name="prenom" class="form-control" value="{{ old('prenom', $user->prenom) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nom</label>
                        <input type="text" name="nom" class="form-control" value="{{ old('nom', $user->nom) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Téléphone</label>
                        <input type="text" name="telephone" class="form-control" value="{{ old('telephone', $user->telephone) }}" placeholder="Entrez votre numéro de téléphone">
                    </div>

                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Enregistrer les modifications
                        </button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('client.dashboard') }}'">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Quick Actions Sidebar -->
        <div class="quick-actions">
            <div class="action-card" onclick="openPasswordModal()">
                <div class="action-card-header">
                    <div class="action-icon blue">
                        <i class="fas fa-key"></i>
                    </div>
                </div>
                <h3 class="action-title">Changer le mot de passe</h3>
                <p class="action-description">Modifiez votre mot de passe pour sécuriser votre compte</p>
            </div>

            <a href="{{ route('client.historique') }}" class="action-card">
                <div class="action-card-header">
                    <div class="action-icon green">
                        <i class="fas fa-history"></i>
                    </div>
                </div>
                <h3 class="action-title">Historique de paiement</h3>
                <p class="action-description">Consultez vos paiements effectués</p>
            </a>

            <a href="{{ route('client.notifications') }}" class="action-card">
                <div class="action-card-header">
                    <div class="action-icon orange">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
                <h3 class="action-title">Notifications</h3>
                <p class="action-description">Gérez vos notifications</p>
            </a>
        </div>
    </div>
</div>

<!-- Modal Change Password -->
<div id="passwordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title">Changer le mot de passe</h3>
            <button class="modal-close" onclick="closePasswordModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('client.password.update') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-control" required minlength="8">
            </div>

            <div class="form-group">
                <label class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" name="new_password_confirmation" class="form-control" required minlength="8">
            </div>

            <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closePasswordModal()">
                    Annuler
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Changer le mot de passe
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openPasswordModal() {
        document.getElementById('passwordModal').classList.add('active');
    }

    function closePasswordModal() {
        document.getElementById('passwordModal').classList.remove('active');
    }

    // Close modal when clicking outside
    document.getElementById('passwordModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePasswordModal();
        }
    });
</script>
@endsection
