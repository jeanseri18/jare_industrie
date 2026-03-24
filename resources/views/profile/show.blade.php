@extends($layout)

@section('title', 'Mon Profil')

@section('content')
<style>
    .profile-header {
        background: linear-gradient(135deg, #003d82 0%, #0056b3 100%);
        color: white;
        padding: 3rem 2rem;
        border-radius: 20px;
        margin-bottom: -4rem;
        position: relative;
        z-index: 1;
    }
    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        border: none;
        position: relative;
        z-index: 2;
        overflow: hidden;
    }
    .avatar-wrapper {
        width: 120px;
        height: 120px;
        background: #f8fafc;
        border: 5px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: -60px auto 1rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    .info-label {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    .info-value {
        font-size: 1rem;
        color: #1e293b;
        font-weight: 600;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 1.25rem;
    }
    .security-card {
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    .security-card:focus-within {
        border-color: #003d82;
        box-shadow: 0 0 0 4px rgba(0, 61, 130, 0.05);
    }
    .btn-update {
        background: #003d82;
        border: none;
        padding: 0.8rem;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s ease;
    }
    .btn-update:hover {
        background: #002457;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(0, 61, 130, 0.2);
    }
    .form-control-custom {
        padding: 0.8rem 1rem;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    .form-control-custom:focus {
        background: white;
        border-color: #003d82;
        box-shadow: none;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
 
    <div class="row justify-content-center px-3">
        <br>
        <br>
        <br>
        <br>
        <br>
        <div class="col-lg-10">
            <div class="row g-4">
                <!-- Infos Card -->
                <div class="col-md-5">
                    <div class="profile-card p-4">
                   
                        <div class="text-center mb-4">
                            <h3 class="fw-bold text-dark mb-1">{{ $user->prenom }} {{ $user->nom }}</h3>
                            <span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2" style="background: rgba(0, 61, 130, 0.1);">
                                <i class="fas fa-shield-alt me-1"></i> {{ strtoupper(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>

                        <div class="mt-4">
                            <div class="info-label">Adresse Email</div>
                            <div class="info-value">{{ $user->email }}</div>

                            <div class="info-label">Téléphone</div>
                            <div class="info-value">{{ $user->telephone ?? 'Non renseigné' }}</div>

                            <div class="info-label">Localisation</div>
                            <div class="info-value">{{ $user->adresse ?? 'Non renseigné' }}</div>
                        </div>

                        <div class="alert alert-warning border-0 rounded-4 mt-4 mb-0 d-flex align-items-center gap-3">
                            <i class="fas fa-info-circle fs-4"></i>
                            <p class="small mb-0">Seul l'administrateur peut modifier ces informations.</p>
                        </div>
                    </div>
                </div>

                <!-- Security Card -->
                <div class="col-md-7">
                    <div class="profile-card p-4">
                        <h4 class="fw-bold mb-4 d-flex align-items-center gap-2">
                            <i class="fas fa-lock text-warning"></i>
                            Changer le mot de passe
                        </h4>

                        @if(session('success'))
                            <div class="alert alert-success border-0 rounded-4 mb-4">
                                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Mot de passe actuel</label>
                                <input type="password" name="current_password" class="form-control form-control-custom @error('current_password') is-invalid @enderror" required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold small text-muted">Nouveau mot de passe</label>
                                <input type="password" name="password" class="form-control form-control-custom @error('password') is-invalid @enderror" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold small text-muted">Confirmer le nouveau mot de passe</label>
                                <input type="password" name="password_confirmation" class="form-control form-control-custom" required>
                            </div>

                            <button type="submit" class="btn btn-primary btn-update w-100 text-white">
                                <i class="fas fa-save me-2"></i> Mettre à jour la sécurité
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
