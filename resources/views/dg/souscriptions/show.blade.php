@extends('layouts.dg')

@section('title', 'Détails de la Souscription')

@section('content')
<style>
    .card-header-toggle {
        cursor: pointer;
        user-select: none;
    }
    .toggle-icon {
        transition: transform 0.3s ease;
    }
    .collapsed .toggle-icon {
        transform: rotate(-90deg);
    }
    .info-label {
        color: #6c757d;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .info-value {
        font-weight: 600;
        color: #2d3748;
    }
    .stat-box {
        padding: 15px;
        border-radius: 10px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
    }
    /* Correction CSS pour les cartes et conteneurs */
    .card-custom {
        background: white;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    .card-body-custom {
        padding: 24px;
    }
    .badge-custom {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
</style>

<div class="container-fluid">
    <div class="card-header-custom mb-4 d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-file-contract me-2"></i>
            Détails de la Souscription : <span class="text-primary">{{ $souscription->ref_souscription }}</span>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dg.souscriptions.paiements', $souscription) }}" class="btn btn-info text-white">
                <i class="fas fa-money-bill-wave me-1"></i> Voir Paiements
            </a>
            <a href="{{ route('dg.souscriptions.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Retour
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Résumé financier rapide -->
    <div class="row mb-4">
        @php
            $totalPaye = $souscription->paiements->where('statut', 'payé')->sum('amount'); // Si le champ s'appelle amount ou montant, vérifions. Dans les fichiers précédents c'était montant.
            $totalPaye = $souscription->paiements->where('statut', 'payé')->sum('montant');
            
            // Frais de dossier - On récupère le montant défini à la souscription si possible, ou via la relation
            $fraisAttendu = (float)($souscription->frais_souscription ?? ($souscription->fraisDossier->montant ?? 0));
            $fraisPaye = (float)($souscription->fraisDossier->montant_paye ?? 0);
            
            // Apport Initial
            $apportAttendu = (float)($souscription->apport_initial ?? ($souscription->apportInitial->montant ?? 0));
            $apportPaye = (float)($souscription->apportInitial->montant_paye ?? 0);
            
            $prixLogement = (float)($souscription->prix_logement ?? 0);
            
            // LE CALCUL CORRECT DU TOTAL DÛ : Prix Logement + Frais de Dossier
            // L'apport initial n'est PAS ajouté en plus, car il fait partie du prix du logement.
            $duGlobal = $prixLogement + $fraisAttendu;
            
            $resteGlobal = max($duGlobal - $totalPaye, 0);
            $pourcentage = $duGlobal > 0 ? round(($totalPaye / $duGlobal) * 100, 1) : 0;
            
            // Limiter à 100% pour l'affichage de la barre si besoin, mais garder le vrai taux pour le texte
            $progressWidth = min($pourcentage, 100);
        @endphp
        <div class="col-md-3">
            <div class="stat-box">
                <div class="info-label text-primary">Montant Total Dû</div>
                <div class="fs-4 fw-bold">{{ number_format($duGlobal, 0, ',', ' ') }} <small class="text-muted small-text">FCFA</small></div>
                <div class="text-muted small">Logement + Frais</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="info-label text-success">Total Encaissé</div>
                <div class="fs-4 fw-bold">{{ number_format($totalPaye, 0, ',', ' ') }} <small class="text-muted small-text">FCFA</small></div>
                <div class="text-muted small">Tout type confondu</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="info-label text-danger">Reste à Payer</div>
                <div class="fs-4 fw-bold">{{ number_format($resteGlobal, 0, ',', ' ') }} <small class="text-muted small-text">FCFA</small></div>
                <div class="text-muted small">Solde restant</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-box">
                <div class="info-label text-info">Progression</div>
                <div class="d-flex align-items-center gap-2">
                    <div class="fs-4 fw-bold">{{ $pourcentage }}%</div>
                    <div class="progress flex-grow-1" style="height: 8px;">
                        <div class="progress-bar bg-info" role="progressbar" data-width="{{ $progressWidth }}"></div>
                    </div>
                </div>
                <div class="text-muted small">Taux de recouvrement</div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Informations Client & Souscription -->
        <div class="col-lg-8">
            <div class="card-custom mb-4">
                <div class="card-header-custom card-header-toggle d-flex justify-content-between align-items-center" 
                     data-bs-toggle="collapse" data-bs-target="#infoGenerales">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2"></i> Informations Générales & Contrat</h5>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div id="infoGenerales" class="collapse show">
                    <div class="card-body-custom">
                        <div class="row">
                            <div class="col-md-6 mb-3 border-end">
                                <h6 class="text-muted mb-3 fw-bold"><i class="fas fa-user me-1"></i> Client</h6>
                                <div class="mb-2">
                                    <div class="info-label">Nom Complet</div>
                                    <div class="info-value">{{ optional($souscription->client)->nom_prenom }}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Référence Client</div>
                                    <div class="info-value text-primary">{{ optional($souscription->client)->ref_client }}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Téléphone / Email</div>
                                    <div class="info-value">{{ optional($souscription->client)->telephone }} / {{ optional($souscription->client)->email }}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Catégorie</div>
                                    <div class="info-value badge bg-light text-dark border">{{ ucfirst($souscription->categorie_client ?? 'N/A') }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-muted mb-3 fw-bold"><i class="fas fa-file-contract me-1"></i> Contrat</h6>
                                <div class="mb-2">
                                    <div class="info-label">Projet / Programme</div>
                                    <div class="info-value">{{ optional($souscription->projet)->nom }}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Type de Logement</div>
                                    <div class="info-value">{{ $souscription->type_logement }}</div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Durée du contrat</div>
                                    <div class="info-value">{{ $souscription->duree_contrat_mois ?? 'N/A' }} Mois</div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Période</div>
                                    <div class="info-value">
                                        Du {{ $souscription->date_debut ? $souscription->date_debut->format('d/m/Y') : '-' }} 
                                        au {{ $souscription->date_fin ? $souscription->date_fin->format('d/m/Y') : '-' }}
                                    </div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Valeur du logement</div>
                                    <div class="info-value text-primary fw-bold">{{ number_format($souscription->prix_logement, 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="mb-2">
                                    <div class="info-label">Mode de Paiement</div>
                                    <div class="info-value badge bg-light text-dark border">{{ $souscription->mode_paiement ?? 'Non défini' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails Financiers Spécifiques -->
            <div class="row">
                <!-- Frais de Dossier -->
                <div class="col-md-6 mb-4">
                    <div class="card-custom h-100">
                        <div class="card-header-custom card-header-toggle d-flex justify-content-between align-items-center" 
                             data-bs-toggle="collapse" data-bs-target="#infoFrais">
                            <h5 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i> Frais de Dossier</h5>
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </div>
                        <div id="infoFrais" class="collapse show">
                            <div class="card-body-custom">
                                <div class="mb-3">
                                    <div class="info-label">Montant Attendu</div>
                                    <div class="fs-5 fw-bold text-primary">{{ number_format($fraisAttendu, 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="mb-3">
                                    <div class="info-label">Montant Encaissé</div>
                                    <div class="fs-5 fw-bold text-success">{{ number_format($fraisPaye, 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="mb-0">
                                    <div class="info-label">Statut des frais</div>
                                    @if($fraisPaye >= $fraisAttendu && $fraisAttendu > 0)
                                        <span class="badge bg-success">SOLDE</span>
                                    @else
                                        <span class="badge bg-warning text-dark">EN ATTENTE</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Apport Initial -->
                <div class="col-md-6 mb-4">
                    <div class="card-custom h-100">
                        <div class="card-header-custom card-header-toggle d-flex justify-content-between align-items-center" 
                             data-bs-toggle="collapse" data-bs-target="#infoApport">
                            <h5 class="mb-0"><i class="fas fa-hand-holding-usd me-2"></i> Apport Initial</h5>
                            <i class="fas fa-chevron-down toggle-icon"></i>
                        </div>
                        <div id="infoApport" class="collapse show">
                            <div class="card-body-custom">
                                <div class="mb-3">
                                    <div class="info-label">Montant Attendu</div>
                                    <div class="fs-5 fw-bold text-primary">{{ number_format($apportAttendu, 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="mb-3">
                                    <div class="info-label">Montant Encaissé</div>
                                    <div class="fs-5 fw-bold text-success">{{ number_format($apportPaye, 0, ',', ' ') }} FCFA</div>
                                </div>
                                <div class="mb-0">
                                    <div class="info-label">Statut de l'apport</div>
                                    @if($apportPaye >= $apportAttendu && $apportAttendu > 0)
                                        <span class="badge bg-success">SOLDE</span>
                                    @else
                                        <span class="badge bg-warning text-dark">EN ATTENTE</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Détails de l'Attribution -->
            <div class="card-custom mb-4">
                <div class="card-header-custom card-header-toggle d-flex justify-content-between align-items-center" 
                     data-bs-toggle="collapse" data-bs-target="#infoAttribution">
                    <h5 class="mb-0"><i class="fas fa-home me-2"></i> Logement & Attribution</h5>
                    <i class="fas fa-chevron-down toggle-icon"></i>
                </div>
                <div id="infoAttribution" class="collapse show">
                    <div class="card-body-custom">
                        @if($souscription->attributionLot)
                            <div class="row text-center">
                                <div class="col-md-3 border-end">
                                    <div class="info-label">Îlot</div>
                                    <div class="fs-5 fw-bold text-success">{{ $souscription->attributionLot->ilot }}</div>
                                </div>
                                <div class="col-md-3 border-end">
                                    <div class="info-label">Lot</div>
                                    <div class="fs-5 fw-bold text-success">{{ $souscription->attributionLot->lot }}</div>
                                </div>
                                <div class="col-md-3 border-end">
                                    <div class="info-label">Villa N°</div>
                                    <div class="fs-5 fw-bold text-success">{{ $souscription->attributionLot->numero_villa }}</div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-label">Superficie</div>
                                    <div class="fs-5 fw-bold text-success">{{ $souscription->attributionLot->superficie }} m²</div>
                                </div>
                            </div>
                            <div class="mt-3 p-2 bg-light rounded border-start border-success border-4">
                                <small class="text-muted d-block">Observations internes :</small>
                                <span>{{ $souscription->attributionLot->observations_internes ?? 'Aucune observation' }}</span>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Aucun lot n'a encore été attribué à cette souscription.</p>
                                @if($souscription->statut != 'annulee')
                                    <a href="{{ route('dg.attribution.index') }}" class="btn btn-sm btn-primary">
                                        Effectuer une attribution
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions & Status -->
        <div class="col-lg-4">
            <div class="card-custom mb-4">
                <div class="card-header-custom">
                    <h5 class="mb-0">Statut & Documents</h5>
                </div>
                <div class="card-body-custom">
                    <div class="mb-4 text-center">
                        <div class="info-label">Statut Actuel</div>
                        <div class="mt-2">
                            @if($souscription->statut == 'SOLD')
                                <span class="badge bg-success p-2 w-100 fs-6"><i class="fas fa-check-circle me-1"></i> SOLDÉ</span>
                            @elseif($souscription->statut == 'APPORT_OK')
                                <span class="badge bg-info p-2 w-100 fs-6"><i class="fas fa-money-bill me-1"></i> APPORT OK</span>
                            @elseif($souscription->statut == 'FRAIS_OK')
                                <span class="badge bg-primary p-2 w-100 fs-6"><i class="fas fa-file-invoice me-1"></i> FRAIS OK</span>
                            @elseif($souscription->statut == 'annulee')
                                <span class="badge bg-danger p-2 w-100 fs-6"><i class="fas fa-times-circle me-1"></i> ANNULÉE</span>
                            @else
                                <span class="badge bg-warning p-2 w-100 fs-6 text-dark"><i class="fas fa-clock me-1"></i> {{ strtoupper($souscription->statut ?? 'EN COURS') }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('dg.souscriptions.fiche-souscription', $souscription) }}" class="btn btn-outline-secondary" target="_blank">
                            <i class="fas fa-file-pdf me-2 text-danger"></i> Fiche de souscription
                        </a>

                        @if($souscription->attributionLot)
                            <a href="{{ route('dg.souscriptions.attestation', $souscription) }}" class="btn btn-outline-secondary" target="_blank">
                                <i class="fas fa-file-pdf me-2 text-danger"></i> Attestation Réservation
                            </a>
                        @endif
                        
                        @if($souscription->statut == 'SOLD')
                             <a href="{{ route('dg.souscriptions.confirmation', $souscription) }}" class="btn btn-success">
                                <i class="fas fa-certificate me-2"></i> Validation Finale
                            </a>
                        @endif

                        <hr>

                        @if($souscription->statut == 'annulee')
                            <form action="{{ route('dg.souscriptions.reactiver', $souscription) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-undo me-2"></i> Réactiver le dossier
                                </button>
                            </form>
                        @endif

                        @if($souscription->statut != 'annulee')
                            <form action="{{ route('dg.souscriptions.annuler', $souscription) }}" method="POST" onsubmit="return confirm('Attention ! L\'annulation est irréversible. Confirmer ?');">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash-alt me-2"></i> Annuler le dossier
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Historique rapide -->
            <div class="card-custom">
                <div class="card-header-custom">
                    <h5 class="mb-0"><i class="fas fa-history me-2"></i> Chronologie</h5>
                </div>
                <div class="card-body-custom">
                    <div class="small">
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Créé le :</span>
                            <span class="fw-bold">{{ $souscription->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Par :</span>
                            <span class="fw-bold">{{ $souscription->operateur->name ?? 'Système' }}</span>
                        </div>
                        @if($souscription->paiements->where('statut', 'payé')->count() > 0)
                        <div class="mb-0 d-flex justify-content-between">
                            <span class="text-muted">Dernier paiement :</span>
                            <span class="fw-bold text-success">{{ $souscription->paiements->where('statut', 'payé')->max('date_paiement')?->format('d/m/Y') ?? 'N/A' }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.progress-bar[data-width]').forEach(function (el) {
            const v = el.getAttribute('data-width');
            if (v !== null && v !== '') {
                el.style.width = String(v) + '%';
            }
        });
    });
</script>
@endpush
