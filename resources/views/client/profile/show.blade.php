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

    .btn-edit {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
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
        position: relative;
        overflow: hidden;
    }

    .btn-edit::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .btn-edit:hover::before {
        width: 300px;
        height: 300px;
    }

    .btn-edit:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 89, 152, 0.4);
    }

    .alert {
        padding: 1rem 1.2rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.8rem;
        animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 2px solid rgba(16, 185, 129, 0.3);
        color: #059669;
    }

    .alert i {
        font-size: 1.3rem;
    }

    .profile-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.2s backwards;
        margin-bottom: 1.5rem;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card-header {
        padding: 1.5rem;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        border-bottom: 2px solid #e2e8f0;
    }

    .card-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .card-body {
        padding: 1.5rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
    }

    .info-item {
        margin-bottom: 1.2rem;
    }

    .info-label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 0.4rem;
    }

    .info-value {
        font-size: 0.95rem;
        color: #1e293b;
        font-weight: 500;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .status-badge.status-actif {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 2px solid rgba(16, 185, 129, 0.3);
    }

    .status-badge.status-inactif {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
        border: 2px solid rgba(239, 68, 68, 0.3);
    }

    .security-section {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.4s backwards;
    }

    .password-link {
        color: #3b5998;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .password-link:hover {
        color: #2d4373;
        text-decoration: underline;
    }
</style>

<div class="profile-container">
    <div class="profile-wrapper">
        <div class="page-header">
            <div class="page-title">
                <i class="fas fa-user-circle"></i>
                <h1>{{ __('Mes Informations') }}</h1>
            </div>
            <a href="{{ route('client.profile.edit') }}" class="btn-edit">
                <i class="fas fa-edit"></i>
                {{ __('Modifier mes informations') }}
            </a>
        </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <div class="info-grid">
                    <div class="profile-card">
                        <div class="card-header">
                            <h3>{{ __('Informations personnelles') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <span class="info-label">{{ __('Nom') }}</span>
                                <span class="info-value">{{ $client->nom }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('Prénom(s)') }}</span>
                                <span class="info-value">{{ $client->prenom }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('Email') }}</span>
                                <span class="info-value">{{ $client->email }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('Téléphone') }}</span>
                                <span class="info-value">{{ $client->telephone }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('Adresse') }}</span>
                                <span class="info-value">{{ $client->adresse }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('Type de client') }}</span>
                                <span class="info-value">
                                    @if($client->type_client == 'individuel')
                                        {{ __('Individuel') }}
                                    @elseif($client->type_client == 'mutuelle')
                                        {{ __('Mutuelle') }}
                                    @elseif($client->type_client == 'individuel-banque')
                                        {{ __('Individuel avec banque') }}
                                    @else
                                        {{ ucfirst($client->type_client) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="profile-card">
                        <div class="card-header">
                            <h3>{{ __('Informations de compte') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="info-item">
                                <span class="info-label">{{ __('Date d\'inscription') }}</span>
                                <span class="info-value">{{ $client->created_at->format('d/m/Y') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('Statut du compte') }}</span>
                                <span class="info-value">
                                    <span class="status-badge status-{{ $client->statut }}">
                                        {{ ucfirst($client->statut) }}
                                    </span>
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('Nombre de souscriptions') }}</span>
                                <span class="info-value">{{ $client->souscriptions->count() }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">{{ __('Documents téléchargés') }}</span>
                                <span class="info-value">{{ $client->documents->count() }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="security-section">
                    <div class="card-header">
                        <h3>{{ __('Sécurité') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-item">
                            <span class="info-label">{{ __('Mot de passe') }}</span>
                            <span class="info-value">********</span>
                            <a href="{{ route('client.password.change') }}" class="password-link">
                                {{ __('Changer le mot de passe') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection