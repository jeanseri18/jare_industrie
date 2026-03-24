@extends('layouts.client')

@section('title', 'Mes Souscriptions')

@section('content')
<style>
    .dashboard-container { padding: 0; animation: fadeIn 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Welcome Section */
    .welcome-section { background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%); border-radius: 16px; padding: 2rem; color: white; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(59, 89, 152, 0.3); }
    .welcome-title { font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
    .welcome-subtitle { font-size: 1rem; opacity: 0.9; }

    /* Subscription Card */
    .subscription-card { background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 1.5rem; overflow: hidden; transition: transform 0.3s ease; border: 1px solid #f1f5f9; }
    .subscription-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
    
    .card-header-custom { padding: 1.25rem 1.5rem; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: #f8fafc; }
    .ref-badge { background: #eef2ff; color: #3b5998; padding: 0.4rem 0.8rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; }
    
    .card-body-custom { padding: 1.5rem; }
    
    .prog-label { font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; margin-bottom: 0.5rem; display: block; }
    .prog-val { font-weight: 800; color: #1e293b; }
    
    .status-pill { padding: 0.4rem 0.8rem; border-radius: 50px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; }
    .status-sold { background: #ecfdf5; color: #059669; }
    .status-pending { background: #fff7ed; color: #d97706; }
    .status-canceled { background: #fef2f2; color: #dc2626; }

    .project-info { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; }
    .project-icon { width: 48px; height: 48px; background: #f1f5f9; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #3b5998; font-size: 1.25rem; }
    .project-details h5 { margin: 0; font-weight: 700; color: #1e293b; }
    .project-details p { margin: 0; color: #64748b; font-size: 0.85rem; }

    .empty-state { text-align: center; padding: 4rem 2rem; background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
</style>

<div class="dashboard-container">
    <div class="welcome-section">
        <h1 class="welcome-title">Mes Souscriptions</h1>
        <p class="welcome-subtitle">Suivez l'état de vos dossiers immobiliers chez Jarel Industries</p>
    </div>

    <div class="row">
        @forelse($souscriptions as $s)
            <div class="col-12">
                <div class="subscription-card">
                    <div class="card-header-custom">
                        <span class="ref-badge">{{ $s->ref_souscription }}</span>
                        <span class="status-pill {{ $s->statut == 'SOLD' ? 'status-sold' : ($s->statut == 'annulee' ? 'status-canceled' : 'status-pending') }}">
                            {{ $s->statut == 'SOLD' ? 'Soldé' : ($s->statut == 'annulee' ? 'Annulée' : 'En cours') }}
                        </span>
                    </div>
                    <div class="card-body-custom">
                        <div class="row align-items-center">
                            <div class="col-lg-4">
                                <div class="project-info">
                                    <div class="project-icon">
                                        <i class="fas fa-building"></i>
                                    </div>
                                    <div class="project-details">
                                        <h5>{{ $s->projet->nom ?? 'Projet Jarel' }}</h5>
                                        <p>{{ $s->type_logement }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-5">
                                @php
                                    $totalPaye = $s->paiements->where('statut', 'payé')->sum('montant');
                                    $pourcentage = $s->prix_logement > 0 ? round(($totalPaye / $s->prix_logement) * 100) : 0;
                                @endphp
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-end mb-2">
                                        <span class="prog-label">Avancement financier</span>
                                        <span class="prog-val">{{ $pourcentage }}%</span>
                                    </div>
                                    <div class="progress" style="height: 10px; border-radius: 10px; background: #f1f5f9;">
                                        <div class="progress-bar bg-primary" style="width: {{ $pourcentage }}%"></div>
                                    </div>
                                    <div class="mt-2 small text-muted">
                                        {{ number_format($totalPaye, 0, ',', ' ') }} / {{ number_format($s->prix_logement, 0, ',', ' ') }} FCFA
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 text-lg-end">
                                <div class="small text-muted mb-1">Date de souscription</div>
                                <div class="fw-bold">{{ $s->created_at->format('d/m/Y') }}</div>
                                <div class="mt-3">
                                    <a href="{{ route('client.historique') }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                        <i class="fas fa-history me-1"></i> Voir paiements
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-file-contract fa-4x text-muted mb-3 opacity-25"></i>
                    <h3>Aucune souscription</h3>
                    <p class="text-muted">Vous n'avez pas encore de souscription enregistrée.</p>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $souscriptions->links() }}
    </div>
</div>
@endsection
