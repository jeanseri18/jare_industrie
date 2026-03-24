@extends('layouts.client')

@section('content')
<style>
    .dashboard-container { padding: 0; animation: fadeIn 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Welcome Section */
    .welcome-section { background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%); border-radius: 16px; padding: 2rem; color: white; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(59, 89, 152, 0.3); }
    .welcome-title { font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
    .welcome-subtitle { font-size: 1rem; opacity: 0.9; }

    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-card { background: white; border-radius: 16px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-bottom: 4px solid #3b5998; }
    .stat-label { color: #64748b; font-size: 0.9rem; font-weight: 600; text-transform: uppercase; margin-bottom: 0.5rem; }
    .stat-value { font-size: 1.8rem; font-weight: 800; color: #1e293b; }

    /* Main Grid */
    .main-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; }

    /* Section Cards */
    .section-card { background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 2rem; overflow: hidden; }
    .section-header { padding: 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    .section-title { font-size: 1.1rem; font-weight: 700; color: #1e293b; margin: 0; }
    .section-body { padding: 1.5rem; }

    /* Notifications List */
    .notif-item { display: flex; gap: 1rem; padding: 1rem; border-radius: 12px; transition: background 0.2s; margin-bottom: 1rem; border-left: 3px solid transparent; }
    .notif-item:hover { background: #f8fafc; }
    .notif-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
    .notif-content { flex: 1; }
    .notif-title { font-weight: 700; font-size: 0.95rem; color: #1e293b; margin-bottom: 0.2rem; }
    .notif-msg { font-size: 0.85rem; color: #64748b; line-height: 1.4; }
    .notif-time { font-size: 0.75rem; color: #94a3b8; margin-top: 0.4rem; }

    .icon-blue { background: #eef2ff; color: #4f46e5; }
    .icon-green { background: #ecfdf5; color: #10b981; }
    .icon-purple { background: #f5f3ff; color: #8b5cf6; }
    .icon-orange { background: #fff7ed; color: #f59e0b; }

    /* Progress Steps */
    .progress-track { display: flex; justify-content: space-between; position: relative; margin: 2rem 0; padding: 0 1rem; }
    .progress-track::before { content: ''; position: absolute; top: 15px; left: 0; right: 0; height: 2px; background: #e2e8f0; z-index: 1; }
    .step-item { position: relative; z-index: 2; text-align: center; width: 60px; }
    .step-dot { width: 32px; height: 32px; border-radius: 50%; background: #e2e8f0; border: 4px solid white; margin: 0 auto 0.5rem; transition: all 0.3s; }
    .step-item.active .step-dot { background: #3b5998; box-shadow: 0 0 0 4px rgba(59, 89, 152, 0.2); }
    .step-item.completed .step-dot { background: #10b981; }
    .step-label { font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; }
    .step-item.active .step-label { color: #3b5998; }

    /* Document List */
    .doc-item { display: flex; align-items: center; justify-content: space-between; padding: 1rem; background: #f8fafc; border-radius: 12px; margin-bottom: 0.8rem; }
    .doc-info { display: flex; align-items: center; gap: 1rem; }
    .doc-icon { font-size: 1.5rem; color: #ef4444; }
    .doc-name { font-weight: 600; font-size: 0.9rem; }

    @media (max-width: 1024px) { .main-grid { grid-template-columns: 1fr; } }
</style>

<div class="dashboard-container">
    <div class="welcome-section">
        <h1 class="welcome-title">Tableau de bord Client</h1>
        <p class="welcome-subtitle">Suivi de vos dossiers : {{ $user->prenom }} {{ $user->nom }}</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Mes Souscriptions</div>
            <div class="stat-value">{{ $stats['total_souscriptions'] }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Versé</div>
            <div class="stat-value">{{ number_format($stats['total_paye'], 0, ',', ' ') }} <small class="fs-6">FCFA</small></div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Lots Attribués</div>
            <div class="stat-value">{{ $stats['nb_attributions'] }}</div>
        </div>
    </div>

    <div class="main-grid">
        <div class="left-col">
            <!-- État d'avancement (pour la dernière souscription) -->
            @if($souscriptions->count() > 0)
                @php $lastS = $souscriptions->first(); @endphp
                <div class="section-card">
                    <div class="section-header">
                        <h2 class="section-title">État d'avancement : {{ $lastS->ref_souscription }}</h2>
                        <span class="badge bg-primary">{{ $lastS->projet->nom ?? 'Projet' }}</span>
                    </div>
                    <div class="section-body">
                        <div class="progress-track">
                            <div class="step-item completed">
                                <div class="step-dot"></div>
                                <div class="step-label">Saisie</div>
                            </div>
                            <div class="step-item {{ $lastS->fraisDossier && $lastS->fraisDossier->montant_reste == 0 ? 'completed' : 'active' }}">
                                <div class="step-dot"></div>
                                <div class="step-label">Frais OK</div>
                            </div>
                            <div class="step-item {{ $lastS->apportInitial && $lastS->apportInitial->montant_reste == 0 ? 'completed' : ($lastS->fraisDossier && $lastS->fraisDossier->montant_reste == 0 ? 'active' : '') }}">
                                <div class="step-dot"></div>
                                <div class="step-label">Apport OK</div>
                            </div>
                            <div class="step-item {{ $lastS->statut == 'SOLD' ? 'completed' : '' }}">
                                <div class="step-dot"></div>
                                <div class="step-label">Soldé</div>
                            </div>
                            <div class="step-item {{ $lastS->attributionLot ? 'completed' : '' }}">
                                <div class="step-dot"></div>
                                <div class="step-label">Attribué</div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="small text-muted">Bien souscrit</div>
                                    <div class="fw-bold">{{ $lastS->type_logement }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="p-3 bg-light rounded-3">
                                    <div class="small text-muted">Lot / Villa</div>
                                    <div class="fw-bold">{{ $lastS->attributionLot ? 'Lot ' . $lastS->attributionLot->lot . ' (Villa ' . $lastS->attributionLot->numero_villa . ')' : 'En attente d\'attribution' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Mes Documents -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">Mes Documents Officiels</h2>
                </div>
                <div class="section-body">
                    @if($souscriptions->count() > 0)
                        @foreach($souscriptions as $s)
                            @if($s->statut == 'SOLD')
                                <div class="doc-item">
                                    <div class="doc-info">
                                        <i class="fas fa-file-pdf doc-icon"></i>
                                        <div>
                                            <div class="doc-name">Lettre Définitive d'Attribution - {{ $s->ref_souscription }}</div>
                                            <small class="text-muted">Générée le {{ $s->updated_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a>
                                </div>
                            @endif
                            @if($s->attributionLot)
                                <div class="doc-item">
                                    <div class="doc-info">
                                        <i class="fas fa-file-pdf doc-icon"></i>
                                        <div>
                                            <div class="doc-name">Attestation de Réservation - {{ $s->ref_souscription }}</div>
                                            <small class="text-muted">Générée le {{ $s->attributionLot->created_at->format('d/m/Y') }}</small>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-sm btn-outline-primary"><i class="fas fa-download"></i></a>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <p class="text-center text-muted py-4">Aucun document disponible pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="right-col">
            <!-- Notifications Récentes -->
            <div class="section-card">
                <div class="section-header">
                    <h2 class="section-title">Notifications Récentes</h2>
                    <a href="{{ route('client.notifications') }}" class="small text-decoration-none">Voir tout</a>
                </div>
                <div class="section-body p-0">
                    @forelse($notifications as $notif)
                        <div class="notif-item px-3 py-3 border-bottom">
                            <div class="notif-icon icon-{{ $notif->color }}">
                                <i class="{{ $notif->icon }}"></i>
                            </div>
                            <div class="notif-content">
                                <div class="notif-title">{{ $notif->titre }}</div>
                                <div class="notif-msg">{{ $notif->message }}</div>
                                <div class="notif-time">{{ $notif->relative }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fs-1 text-muted opacity-25"></i>
                            <p class="mt-2 text-muted">Aucune notification</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Support Rapide -->
            <div class="section-card bg-primary text-white">
                <div class="section-body text-center py-4">
                    <i class="fas fa-headset fs-1 mb-3"></i>
                    <h4>Besoin d'aide ?</h4>
                    <p class="small opacity-75">Contactez notre service client pour toute question sur votre dossier.</p>
                    <a href="mailto:support@jarel.ci" class="btn btn-light btn-sm w-100 fw-bold">Nous écrire</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
