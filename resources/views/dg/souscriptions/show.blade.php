@extends('layouts.dg')

@section('title', 'Détails de la Souscription')

@section('content')
@php
    $totalPaye = $souscription->paiements->where('statut', 'payé')->sum('montant');

    $fraisAttendu = (float) ($souscription->frais_souscription ?? ($souscription->fraisDossier->montant ?? 0));
    $fraisPaye = (float) ($souscription->fraisDossier->montant_paye ?? 0);

    $apportAttendu = (float) ($souscription->apport_initial ?? ($souscription->apportInitial->montant ?? 0));
    $apportPaye = (float) ($souscription->apportInitial->montant_paye ?? 0);

    $prixLogement = (float) ($souscription->prix_logement ?? 0);
    $duGlobal = $prixLogement + $fraisAttendu;
    $resteGlobal = max($duGlobal - $totalPaye, 0);
    $pourcentage = $duGlobal > 0 ? round(($totalPaye / $duGlobal) * 100, 1) : 0;
    $progressWidth = min($pourcentage, 100);
@endphp

<x-page-header :title="'Souscription ' . $souscription->ref_souscription" subtitle="Détails de la souscription">
    <x-slot:actions>
        <a href="{{ route('dg.souscriptions.paiements', $souscription) }}" class="btn-secondary">
            <i class="fas fa-money-bill-wave me-1"></i> Voir paiements
        </a>
        <a href="{{ route('dg.souscriptions.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour à la liste
        </a>
    </x-slot:actions>
</x-page-header>

<x-alert />

<div class="stats-grid">
    <x-stat-card
        label="Montant total dû"
        :value="number_format($duGlobal, 0, ',', ' ') . ' FCFA'"
        icon="fas fa-coins"
        icon-tone="black"
        footer="Logement + frais"
    />
    <x-stat-card
        label="Montant encaissé"
        :value="number_format($totalPaye, 0, ',', ' ') . ' FCFA'"
        icon="fas fa-money-bill-wave"
        icon-tone="gray"
        footer="Tout type confondu"
    />
    <x-stat-card
        label="Montant restant"
        :value="number_format($resteGlobal, 0, ',', ' ') . ' FCFA'"
        icon="fas fa-hourglass-half"
        icon-tone="red"
        footer="Solde restant"
    />
    <div class="stat-card stat-card--progress">
        <div class="stat-header">
            <span class="stat-title">Progression</span>
            <div class="stat-icon icon-gray">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="stat-value">{{ $pourcentage }}%</div>
        <div class="progress">
            <div class="progress-bar bg-secondary" role="progressbar" data-width="{{ $progressWidth }}"></div>
        </div>
        <div class="stat-footer">Taux de recouvrement</div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <x-detail-section title="Informations générales & contrat" collapsible id="infoGenerales" icon="fas fa-id-card">
            <div class="row">
                <div class="col-md-6 mb-3 border-end">
                    <h6 class="text-muted mb-3 fw-bold"><i class="fas fa-user me-1"></i> Client</h6>
                    <x-detail-field label="Nom complet">{{ optional($souscription->client)->nom_prenom }}</x-detail-field>
                    <x-detail-field label="Référence client">
                        <span class="text-primary">{{ optional($souscription->client)->ref_client }}</span>
                    </x-detail-field>
                    <x-detail-field label="Téléphone / email">
                        {{ optional($souscription->client)->telephone }} / {{ optional($souscription->client)->email }}
                    </x-detail-field>
                    <x-detail-field label="Catégorie">
                        <span class="badge-custom badge-info">{{ ucfirst($souscription->categorie_client ?? 'N/A') }}</span>
                    </x-detail-field>
                </div>
                <div class="col-md-6 mb-3">
                    <h6 class="text-muted mb-3 fw-bold"><i class="fas fa-file-contract me-1"></i> Contrat</h6>
                    <x-detail-field label="Projet / programme">{{ optional($souscription->projet)->nom }}</x-detail-field>
                    <x-detail-field label="Type de logement">{{ $souscription->type_logement }}</x-detail-field>
                    <x-detail-field label="Durée du contrat">{{ $souscription->duree_contrat_mois ?? 'N/A' }} mois</x-detail-field>
                    <x-detail-field label="Période">
                        Du {{ $souscription->date_debut ? $souscription->date_debut->format('d/m/Y') : '-' }}
                        au {{ $souscription->date_fin ? $souscription->date_fin->format('d/m/Y') : '-' }}
                    </x-detail-field>
                    <x-detail-field label="Valeur du logement">
                        <strong class="text-primary">{{ number_format($souscription->prix_logement, 0, ',', ' ') }} FCFA</strong>
                    </x-detail-field>
                    <x-detail-field label="Mode de paiement">{{ $souscription->mode_paiement ?? 'Non défini' }}</x-detail-field>
                </div>
            </div>
        </x-detail-section>

        <div class="show-detail-grid">
            <x-detail-section title="Frais de dossier" collapsible id="infoFrais" icon="fas fa-file-invoice-dollar">
                <x-detail-field label="Montant attendu">
                    <strong class="text-primary">{{ number_format($fraisAttendu, 0, ',', ' ') }} FCFA</strong>
                </x-detail-field>
                <x-detail-field label="Montant encaissé">
                    <strong class="text-success">{{ number_format($fraisPaye, 0, ',', ' ') }} FCFA</strong>
                </x-detail-field>
                <x-detail-field label="Statut des frais">
                    @if($fraisPaye >= $fraisAttendu && $fraisAttendu > 0)
                        <span class="badge-custom badge-success">Soldé</span>
                    @else
                        <span class="badge-custom badge-warning">En attente</span>
                    @endif
                </x-detail-field>
            </x-detail-section>

            <x-detail-section title="Apport initial" collapsible id="infoApport" icon="fas fa-hand-holding-usd">
                <x-detail-field label="Montant attendu">
                    <strong class="text-primary">{{ number_format($apportAttendu, 0, ',', ' ') }} FCFA</strong>
                </x-detail-field>
                <x-detail-field label="Montant encaissé">
                    <strong class="text-success">{{ number_format($apportPaye, 0, ',', ' ') }} FCFA</strong>
                </x-detail-field>
                <x-detail-field label="Statut de l'apport">
                    @if($apportPaye >= $apportAttendu && $apportAttendu > 0)
                        <span class="badge-custom badge-success">Soldé</span>
                    @else
                        <span class="badge-custom badge-warning">En attente</span>
                    @endif
                </x-detail-field>
            </x-detail-section>
        </div>

        <x-detail-section title="Logement & attribution" collapsible id="infoAttribution" icon="fas fa-home">
            @if($souscription->attributionLot)
                <div class="row text-center g-2">
                    <div class="col-6 col-md border-end">
                        <x-detail-field label="Îlot">
                            <strong class="text-success">{{ $souscription->attributionLot->ilot }}</strong>
                        </x-detail-field>
                    </div>
                    <div class="col-6 col-md border-end">
                        <x-detail-field label="Lot">
                            <strong class="text-success">{{ $souscription->attributionLot->lot }}</strong>
                        </x-detail-field>
                    </div>
                    <div class="col-6 col-md border-end">
                        <x-detail-field label="Villa n°">
                            <strong class="text-success">{{ $souscription->attributionLot->numero_villa }}</strong>
                        </x-detail-field>
                    </div>
                    <div class="col-6 col-md border-end">
                        <x-detail-field label="Superficie (m²)">
                            <strong class="text-success">{{ $souscription->attributionLot->superficie }} m²</strong>
                        </x-detail-field>
                    </div>
                    <div class="col-6 col-md">
                        <x-detail-field label="Surface bâtie (m²)">
                            <strong class="text-success">
                                {{ $souscription->attributionLot->surface_batie ?? '—' }}@if($souscription->attributionLot->surface_batie !== null) m²@endif
                            </strong>
                        </x-detail-field>
                    </div>
                </div>
                <div class="mt-3 p-3 bg-light rounded border-start border-success border-4">
                    <small class="text-muted d-block">Observations internes :</small>
                    <span>{{ $souscription->attributionLot->observations_internes ?? 'Aucune observation' }}</span>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Aucun lot n'a encore été attribué à cette souscription.</p>
                    @if($souscription->statut != 'annulee')
                        <a href="{{ route('dg.attribution.index') }}" class="btn-primary btn-sm">
                            Effectuer une attribution
                        </a>
                    @endif
                </div>
            @endif
        </x-detail-section>
    </div>

    <div class="col-lg-4">
        <x-detail-section title="Statut & documents">
            <div class="mb-4 text-center">
                <div class="detail-field__label text-uppercase">Statut actuel</div>
                <div class="mt-2">
                    @if($souscription->statut == 'SOLD')
                        <span class="badge-custom badge-success w-100 d-block py-2"><i class="fas fa-check-circle me-1"></i> Soldé</span>
                    @elseif($souscription->statut == 'APPORT_OK')
                        <span class="badge-custom badge-info w-100 d-block py-2"><i class="fas fa-money-bill me-1"></i> Apport OK</span>
                    @elseif($souscription->statut == 'FRAIS_OK')
                        <span class="badge-custom badge-info w-100 d-block py-2"><i class="fas fa-file-invoice me-1"></i> Frais OK</span>
                    @elseif($souscription->statut == 'annulee')
                        <span class="badge-custom badge-danger w-100 d-block py-2"><i class="fas fa-times-circle me-1"></i> Annulée</span>
                    @else
                        <span class="badge-custom badge-warning w-100 d-block py-2"><i class="fas fa-clock me-1"></i> {{ strtoupper($souscription->statut ?? 'En cours') }}</span>
                    @endif
                </div>
            </div>

            <div class="show-actions-grid">
                <a href="{{ route('dg.souscriptions.fiche-souscription', $souscription) }}" class="btn-secondary" target="_blank">
                    <i class="fas fa-file-pdf me-2 text-danger"></i> Fiche de souscription
                </a>

                <a href="{{ \Illuminate\Support\Facades\URL::signedRoute('public.souscriptions.contrat-reservation', ['souscription' => $souscription->id]) }}" class="btn-secondary" target="_blank">
                    <i class="fas fa-file-contract me-2 text-primary"></i> Contrat de réservation
                </a>

                @if($souscription->attributionLot)
                    <a href="{{ route('dg.souscriptions.attestation', $souscription) }}" class="btn-secondary" target="_blank">
                        <i class="fas fa-file-pdf me-2 text-danger"></i> Attestation réservation
                    </a>
                @endif

                @if($souscription->validationFinale)
                    <a href="{{ route('dg.souscriptions.lettre-definitive', $souscription) }}" class="btn-secondary" target="_blank" rel="noopener">
                        <i class="fas fa-file-pdf me-2 text-danger"></i> Lettre définitive
                    </a>
                @endif

                @if($souscription->statut == 'SOLD')
                    <a href="{{ route('dg.souscriptions.confirmation', $souscription) }}" class="btn-primary">
                        <i class="fas fa-certificate me-2"></i> Validation finale
                    </a>
                @endif

                <hr class="my-2">

                @if($souscription->statut == 'annulee')
                    <form action="{{ route('dg.souscriptions.reactiver', $souscription) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-undo me-2"></i> Réactiver le dossier
                        </button>
                    </form>
                @endif

                @if($souscription->statut != 'annulee')
                    <form action="{{ route('dg.souscriptions.annuler', $souscription) }}" method="POST" onsubmit="return confirm('Attention ! L\'annulation est irréversible. Confirmer ?');">
                        @csrf
                        <button type="submit" class="btn-secondary text-danger">
                            <i class="fas fa-trash-alt me-2"></i> Annuler le dossier
                        </button>
                    </form>
                @endif
            </div>
        </x-detail-section>

        <x-detail-section title="Chronologie" icon="fas fa-history" class="mt-4">
            <div class="show-meta-row">
                <span class="show-meta-row__label">Créé le</span>
                <span class="show-meta-row__value">{{ $souscription->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="show-meta-row">
                <span class="show-meta-row__label">Par</span>
                <span class="show-meta-row__value">{{ $souscription->operateur->name ?? 'Système' }}</span>
            </div>
            @if($souscription->paiements->where('statut', 'payé')->count() > 0)
                <div class="show-meta-row">
                    <span class="show-meta-row__label">Dernier paiement</span>
                    <span class="show-meta-row__value text-success">{{ $souscription->paiements->where('statut', 'payé')->max('date_paiement')?->format('d/m/Y') ?? 'N/A' }}</span>
                </div>
            @endif
        </x-detail-section>
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
