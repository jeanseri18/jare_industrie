@extends('layouts.client')

@section('title', 'Mes Documents')

@section('content')
<style>
    .dashboard-container { padding: 0; animation: fadeIn 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* Welcome Section */
    .welcome-section { background: linear-gradient(135deg, #3b5998 0%, #2d4373 100%); border-radius: 16px; padding: 2rem; color: white; margin-bottom: 2rem; box-shadow: 0 10px 30px rgba(59, 89, 152, 0.3); }
    .welcome-title { font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem; }
    .welcome-subtitle { font-size: 1rem; opacity: 0.9; }

    /* Document Card */
    .doc-card { background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 1.5rem; overflow: hidden; transition: all 0.3s ease; border: 1px solid #f1f5f9; display: flex; flex-direction: column; }
    .doc-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-color: #3b5998; }
    
    .doc-header { padding: 1.5rem; display: flex; align-items: center; gap: 1rem; border-bottom: 1px solid #f1f5f9; background: #f8fafc; }
    .doc-icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .icon-attribution { background: #e0f2fe; color: #0284c7; }
    .icon-definitive { background: #fef3c7; color: #d97706; }
    
    .doc-body { padding: 1.5rem; flex-grow: 1; }
    .doc-title { font-weight: 700; color: #1e293b; margin-bottom: 0.5rem; font-size: 1rem; }
    .doc-desc { font-size: 0.85rem; color: #64748b; line-height: 1.5; margin-bottom: 1rem; }
    
    .doc-footer { padding: 1.25rem 1.5rem; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; }
    
    .download-btn { background: #3b5998; color: white; border: none; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 700; font-size: 0.85rem; transition: all 0.2s; }
    .download-btn:hover { background: #2d4373; color: white; transform: scale(1.05); }

    .empty-state { text-align: center; padding: 4rem 2rem; background: white; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
</style>

<div class="dashboard-container">
    <div class="welcome-section">
        <h1 class="welcome-title">Mes Documents</h1>
        <p class="welcome-subtitle">Accédez et téléchargez vos documents officiels en toute sécurité</p>
    </div>

    <div class="row">
        @php $hasDocs = false; @endphp
        
        @foreach($souscriptions as $s)
            <!-- Lettre Définitive -->
            @if($s->statut == 'SOLD')
                @php $hasDocs = true; @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="doc-card h-100">
                        <div class="doc-header">
                            <div class="doc-icon-box icon-definitive">
                                <i class="fas fa-file-signature"></i>
                            </div>
                            <div class="doc-meta">
                                <span class="badge bg-warning text-dark small">OFFICIEL</span>
                            </div>
                        </div>
                        <div class="doc-body">
                            <h5 class="doc-title">Lettre Définitive d'Attribution</h5>
                            <p class="doc-desc">Document certifiant votre propriété pleine et entière pour le dossier <strong>{{ $s->ref_souscription }}</strong>.</p>
                            <div class="small text-muted">
                                <i class="fas fa-building me-1"></i> {{ $s->projet->nom ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="doc-footer">
                            <span class="small text-muted">{{ $s->updated_at->format('d/m/Y') }}</span>
                            <a href="#" class="download-btn text-decoration-none">
                                <i class="fas fa-download me-1"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Attestation de Réservation -->
            @if($s->attributionLot)
                @php $hasDocs = true; @endphp
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="doc-card h-100">
                        <div class="doc-header">
                            <div class="doc-icon-box icon-attribution">
                                <i class="fas fa-map-marked-alt"></i>
                            </div>
                            <div class="doc-meta">
                                <span class="badge bg-info text-white small">RÉSERVATION</span>
                            </div>
                        </div>
                        <div class="doc-body">
                            <h5 class="doc-title">Attestation de Réservation</h5>
                            <p class="doc-desc">Détails de votre lot : <strong>{{ $s->attributionLot->lot }}</strong> (Îlot {{ $s->attributionLot->ilot }}). Dossier {{ $s->ref_souscription }}.</p>
                            <div class="small text-muted">
                                <i class="fas fa-building me-1"></i> {{ $s->projet->nom ?? 'N/A' }}
                            </div>
                        </div>
                        <div class="doc-footer">
                            <span class="small text-muted">{{ $s->attributionLot->created_at->format('d/m/Y') }}</span>
                            <a href="#" class="download-btn text-decoration-none">
                                <i class="fas fa-download me-1"></i> PDF
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach

        @if(!$hasDocs)
            <div class="col-12">
                <div class="empty-state">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3 opacity-25"></i>
                    <h3>Aucun document</h3>
                    <p class="text-muted">Vos documents officiels seront disponibles dès validation de vos dossiers.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
