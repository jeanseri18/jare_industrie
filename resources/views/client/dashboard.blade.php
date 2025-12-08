@extends('layouts.client')

@section('content')
<style>
    .dashboard-container {
        padding: 2rem 1.5rem;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .dashboard-header {
        margin-bottom: 2rem;
        animation: fadeInUp 0.6s ease-out;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .welcome-card {
        background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%);
        border-radius: 16px;
        padding: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(59, 89, 152, 0.3);
        position: relative;
        overflow: hidden;
    }

    .welcome-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -20%;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .welcome-content {
        position: relative;
        z-index: 1;
    }

    .welcome-title {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }

    .welcome-date {
        margin-top: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        opacity: 0.8;
        font-size: 0.9rem;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.8rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out backwards;
        cursor: pointer;
    }

    .stat-card:nth-child(1) { animation-delay: 0.1s; }
    .stat-card:nth-child(2) { animation-delay: 0.2s; }
    .stat-card:nth-child(3) { animation-delay: 0.3s; }
    .stat-card:nth-child(4) { animation-delay: 0.4s; }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        transition: width 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    }

    .stat-card:hover::before {
        width: 100%;
        opacity: 0.05;
    }

    .stat-card.blue::before { background: #3b82f6; }
    .stat-card.green::before { background: #10b981; }
    .stat-card.purple::before { background: #8b5cf6; }
    .stat-card.orange::before { background: #f59e0b; }

    .stat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        transition: all 0.3s ease;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .stat-icon.blue {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }

    .stat-icon.green {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .stat-icon.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }

    .stat-icon.orange {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }

    .stat-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .stat-badge.blue {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    .stat-badge.green {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .stat-badge.purple {
        background: rgba(139, 92, 246, 0.1);
        color: #7c3aed;
    }

    .stat-badge.orange {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .stat-body h3 {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .stat-footer {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.85rem;
        color: #64748b;
        margin-top: 1rem;
    }

    .stat-trend {
        display: flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.6rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .stat-trend.up {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .stat-trend.down {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    /* Content Grid */
    .content-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Recent Activity Card */
    .activity-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.5s backwards;
    }

    .card-header {
        padding: 1.5rem;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.8rem;
    }

    .card-title i {
        color: #3b5998;
    }

    .view-all-link {
        color: #3b5998;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .view-all-link:hover {
        color: #2d4373;
        transform: translateX(3px);
    }

    .activity-list {
        max-height: 500px;
        overflow-y: auto;
    }

    .activity-list::-webkit-scrollbar {
        width: 6px;
    }

    .activity-list::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .activity-list::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    .activity-item {
        padding: 1.5rem;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .activity-item:last-child {
        border-bottom: none;
    }

    .activity-item:hover {
        background: #f8fafc;
        transform: translateX(5px);
    }

    .activity-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.8rem;
    }

    .activity-title {
        font-weight: 600;
        color: #3b5998;
        font-size: 0.95rem;
        margin-bottom: 0.3rem;
    }

    .activity-subtitle {
        color: #64748b;
        font-size: 0.85rem;
    }

    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .status-badge.success {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
    }

    .status-badge.warning {
        background: rgba(245, 158, 11, 0.1);
        color: #d97706;
    }

    .status-badge.danger {
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }

    .status-badge.info {
        background: rgba(59, 130, 246, 0.1);
        color: #2563eb;
    }

    .activity-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 0.8rem;
        font-size: 0.85rem;
    }

    .activity-amount {
        font-weight: 700;
        color: #1e293b;
    }

    .activity-date {
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .empty-state {
        padding: 3rem;
        text-align: center;
        color: #94a3b8;
    }

    .empty-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    /* Quick Actions Card */
    .quick-actions-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        overflow: hidden;
        animation: fadeInUp 0.6s ease-out 0.6s backwards;
    }

    .actions-list {
        padding: 1rem;
    }

    .action-button {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: #f8fafc;
        border: 2px solid transparent;
        border-radius: 12px;
        margin-bottom: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        color: inherit;
    }

    .action-button:hover {
        background: white;
        border-color: #3b5998;
        transform: translateX(5px);
        box-shadow: 0 4px 12px rgba(59, 89, 152, 0.15);
    }

    .action-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        transition: all 0.3s ease;
    }

    .action-button:hover .action-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .action-content h4 {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.2rem;
        font-size: 0.9rem;
    }

    .action-content p {
        font-size: 0.8rem;
        color: #64748b;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 640px) {
        .dashboard-container {
            padding: 1rem;
        }

        .welcome-card {
            padding: 1.5rem;
        }

        .welcome-title {
            font-size: 1.4rem;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .stat-value {
            font-size: 1.6rem;
        }
    }
</style>

<div class="dashboard-container">
    <!-- Welcome Card -->
    <div class="dashboard-header">
        <div class="welcome-card">
            <div class="welcome-content">
                <h1 class="welcome-title">Bienvenue, {{ Auth::user()->prenom }} {{ Auth::user()->nom }} ! 👋</h1>
                <p class="welcome-subtitle">Voici un aperçu de votre activité et de vos souscriptions</p>
                <div class="welcome-date">
                    <i class="fas fa-calendar"></i>
                    <span>{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <!-- Souscriptions -->
        <div class="stat-card blue">
            <div class="stat-header">
                <div class="stat-icon blue">
                    <i class="fas fa-file-contract"></i>
                </div>
                <span class="stat-badge blue">Total</span>
            </div>
            <div class="stat-body">
                <h3>Mes Souscriptions</h3>
                <div class="stat-value">{{ $souscriptions->count() }}</div>
                <div class="stat-footer">
                    <span class="stat-trend up">
                        <i class="fas fa-arrow-up"></i>
                        +12%
                    </span>
                    <span>vs mois dernier</span>
                </div>
            </div>
        </div>

        <!-- Documents -->
        <div class="stat-card green">
            <div class="stat-header">
                <div class="stat-icon green">
                    <i class="fas fa-folder-open"></i>
                </div>
                <span class="stat-badge green">Actifs</span>
            </div>
            <div class="stat-body">
                <h3>Mes Documents</h3>
                <div class="stat-value">{{ $documents->count() }}</div>
                <div class="stat-footer">
                    <span class="stat-trend up">
                        <i class="fas fa-arrow-up"></i>
                        +8%
                    </span>
                    <span>nouveaux documents</span>
                </div>
            </div>
        </div>

        <!-- Paiements -->
        <div class="stat-card purple">
            <div class="stat-header">
                <div class="stat-icon purple">
                    <i class="fas fa-credit-card"></i>
                </div>
                <span class="stat-badge purple">Total</span>
            </div>
            <div class="stat-body">
                <h3>Mes Paiements</h3>
                <div class="stat-value">{{ $paiements->count() }}</div>
                <div class="stat-footer">
                    <span class="stat-trend up">
                        <i class="fas fa-arrow-up"></i>
                        +5%
                    </span>
                    <span>ce mois-ci</span>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="stat-card orange">
            <div class="stat-header">
                <div class="stat-icon orange">
                    <i class="fas fa-bell"></i>
                </div>
                <span class="stat-badge orange">Nouveau</span>
            </div>
            <div class="stat-body">
                <h3>Notifications</h3>
                <div class="stat-value">{{ $notifications->count() }}</div>
                <div class="stat-footer">
                    <span class="stat-trend down">
                        <i class="fas fa-arrow-down"></i>
                        -3%
                    </span>
                    <span>non lues</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Grid -->
    <div class="content-grid">
        <!-- Recent Souscriptions -->
        <div class="activity-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-history"></i>
                    Dernières Souscriptions
                </h3>
                <a href="#" class="view-all-link">
                    Voir tout <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <div class="activity-list">
                @forelse($souscriptions->take(5) as $souscription)
                    <div class="activity-item">
                        <div class="activity-header">
                            <div>
                                <div class="activity-title">
                                    Souscription #{{ $souscription->numero_souscription }}
                                </div>
                                <div class="activity-subtitle">
                                    {{ $souscription->projet->nom ?? 'Projet non défini' }}
                                </div>
                            </div>
                            <span class="status-badge 
                                @if($souscription->statut == 'validee') success
                                @elseif($souscription->statut == 'refusee') danger
                                @elseif($souscription->statut == 'annulee') danger
                                @else warning
                                @endif">
                                {{ ucfirst($souscription->statut) }}
                            </span>
                        </div>
                        <div class="activity-meta">
                            <span class="activity-amount">
                                {{ number_format($souscription->montant_total, 0, ',', ' ') }} F CFA
                            </span>
                            <span class="activity-date">
                                <i class="fas fa-clock"></i>
                                {{ $souscription->date_souscription->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state">
                        <div class="empty-icon">
                            <i class="fas fa-inbox"></i>
                        </div>
                        <p>Aucune souscription trouvée</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions-card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-bolt"></i>
                    Actions Rapides
                </h3>
            </div>
            <div class="actions-list">
                <a href="{{ route('client.documents') }}" class="action-button">
                    <div class="action-icon" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white;">
                        <i class="fas fa-file-upload"></i>
                    </div>
                    <div class="action-content">
                        <h4>Télécharger un document</h4>
                        <p>Ajouter un nouveau document</p>
                    </div>
                </a>

                <a href="{{ route('client.paiements') }}" class="action-button">
                    <div class="action-icon" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <div class="action-content">
                        <h4>Effectuer un paiement</h4>
                        <p>Gérer mes paiements</p>
                    </div>
                </a>

                <a href="{{ route('client.profile') }}" class="action-button">
                    <div class="action-icon" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white;">
                        <i class="fas fa-user-edit"></i>
                    </div>
                    <div class="action-content">
                        <h4>Modifier mon profil</h4>
                        <p>Mettre à jour mes infos</p>
                    </div>
                </a>

                <a href="{{ route('client.notifications') }}" class="action-button">
                    <div class="action-icon" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white;">
                        <i class="fas fa-envelope-open"></i>
                    </div>
                    <div class="action-content">
                        <h4>Voir mes notifications</h4>
                        <p>{{ $notifications->count() }} non lues</p>
                    </div>
                </a>

                <a href="#" class="action-button">
                    <div class="action-icon" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="action-content">
                        <h4>Contacter le support</h4>
                        <p>Besoin d'aide ?</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection