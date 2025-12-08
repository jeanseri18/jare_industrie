@extends('layouts.client')

@section('content')
<style>
    .profile-container {
        padding: 2rem 1.5rem;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .profile-wrapper {
        max-width: 1200px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem 2rem;
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .page-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-title h1 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1e293b;
    }

    .page-title i {
        font-size: 1.8rem;
        color: #3b5998;
    }

    .btn-cancel {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        padding: 0.8rem 1.5rem;
        background: rgba(100, 116, 139, 0.1);
        color: #64748b;
        border: 2px solid rgba(100, 116, 139, 0.2);
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #64748b;
        color: white;
        border-color: #64748b;
    }

    .alert-success {
        padding: 1rem 1.2rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid rgba(16, 185, 129, 0.3);
        color: #059669;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.2s backwards;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-header {
        padding: 2rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 2px solid #e2e8f0;
    }

    .card-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }

    .card-body {
        padding: 2rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-size: 0.9rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .form-control {
        width: 100%;
        padding: 0.8rem 1rem;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: #f9fafb;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b5998;
        background: white;
        box-shadow: 0 0 0 3px rgba(59, 89, 152, 0.1);
    }

    .form-control.error {
        border-color: #ef4444;
        background: rgba(239, 68, 68, 0.05);
    }

    .error-message {
        font-size: 0.8rem;
        color: #dc2626;
        margin-top: 0.3rem;
        font-weight: 500;
    }

    .form-actions {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-primary {
        padding: 0.8rem 1.5rem;
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.95rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(59, 89, 152, 0.3);
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 89, 152, 0.4);
    }
</style>

<div class="profile-container">
    <div class="profile-wrapper">
        <div class="page-header">
            <div class="page-title">
                <i class="fas fa-user-edit"></i>
                <h1>{{ __('Modifier mes informations') }}</h1>
            </div>
            <a href="{{ route('client.profile') }}" class="btn-cancel">
                <i class="fas fa-arrow-left"></i>
                {{ __('Annuler') }}
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-card">
            <div class="card-header">
                <h2 class="card-title">{{ __('Modifier vos informations personnelles') }}</h2>
            </div>
            <div class="card-body">

                <form method="POST" action="{{ route('client.profile.update') }}">
                    @csrf
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label for="nom" class="form-label">{{ __('Nom') }} <span class="required">*</span></label>
                            <input type="text" name="nom" id="nom" value="{{ old('nom', $client->nom) }}" 
                                   class="form-control @error('nom') error @enderror" required>
                            @error('nom')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="prenom" class="form-label">{{ __('Prénom(s)') }} <span class="required">*</span></label>
                            <input type="text" name="prenom" id="prenom" value="{{ old('prenom', $client->prenom) }}" 
                                   class="form-control @error('prenom') error @enderror" required>
                            @error('prenom')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label">{{ __('Email') }} <span class="required">*</span></label>
                            <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}" 
                                   class="form-control @error('email') error @enderror" required readonly>
                            @error('email')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="telephone" class="form-label">{{ __('Téléphone') }} <span class="required">*</span></label>
                            <input type="tel" name="telephone" id="telephone" value="{{ old('telephone', $client->telephone) }}" 
                                   class="form-control @error('telephone') error @enderror" required>
                            @error('telephone')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="adresse" class="form-label">{{ __('Adresse') }} <span class="required">*</span></label>
                            <textarea name="adresse" id="adresse" rows="3" 
                                      class="form-control @error('adresse') error @enderror" required>{{ old('adresse', $client->adresse) }}</textarea>
                            @error('adresse')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i>
                            {{ __('Enregistrer les modifications') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection