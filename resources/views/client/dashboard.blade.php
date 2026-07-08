@extends('layouts.client')

@section('title', 'Tableau de bord')

@section('content')
<x-page-header
    title="Tableau de bord client"
    :subtitle="'Suivi de vos dossiers : ' . $user->prenom . ' ' . $user->nom"
/>
<x-alert />

<div class="stats-grid stats-grid--compact stats-grid--client mb-4">
    <x-stat-card label="Mes souscriptions" :value="$stats['total_souscriptions']" icon="fas fa-file-contract" iconTone="black" footer="Dossiers enregistrés" />
    <x-stat-card label="Total versé" :value="number_format($stats['total_paye'], 0, ',', ' ') . ' FCFA'" icon="fas fa-money-bill-wave" iconTone="gray" footer="Montant payé" />
    <x-stat-card label="Lots attribués" :value="$stats['nb_attributions']" icon="fas fa-map-marked-alt" iconTone="red" footer="Attributions confirmées" />
</div>

<div class="row">
    <div class="col-12">
        @if($souscriptions->count() > 0)
            @php $lastS = $souscriptions->first(); @endphp
            <div class="data-table-container mb-4">
                <div class="card-header-custom">
                    <h3 class="card-title-custom">État d'avancement : {{ $lastS->ref_souscription }}</h3>
                    <span class="badge-custom badge-info">{{ $lastS->projet->nom ?? 'Projet' }}</span>
                </div>
                <div class="list-body p-4">
                    @php
                        $steps = [
                            ['label' => 'Saisie', 'done' => true],
                            ['label' => 'Frais OK', 'done' => $lastS->fraisDossier && $lastS->fraisDossier->montant_reste == 0],
                            ['label' => 'Apport OK', 'done' => $lastS->apportInitial && $lastS->apportInitial->montant_reste == 0],
                            ['label' => 'Soldé', 'done' => $lastS->statut == 'SOLD'],
                            ['label' => 'Attribué', 'done' => (bool) $lastS->attributionLot],
                        ];
                    @endphp
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach($steps as $step)
                            <span class="status-badge {{ $step['done'] ? 'status-valide' : 'status-attente' }}">{{ $step['label'] }}</span>
                        @endforeach
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <div class="small text-muted">Bien souscrit</div>
                                <div class="fw-bold">{{ $lastS->type_logement }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3">
                                <div class="small text-muted">Lot / Villa</div>
                                <div class="fw-bold">
                                    {{ $lastS->attributionLot ? 'Lot ' . $lastS->attributionLot->lot . ' (Villa ' . $lastS->attributionLot->numero_villa . ')' : 'En attente d\'attribution' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="data-table-container mb-4">
            <div class="card-header-custom">
                <h3 class="card-title-custom">Mes documents officiels</h3>
            </div>
            @php $hasDocs = false; @endphp
            @if($souscriptions->count() > 0)
                <x-data-table embedded>
                    <x-slot:head>
                        <tr>
                            <th>Document</th>
                            <th>Référence</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </x-slot:head>
                    @foreach($souscriptions as $s)
                        @if($s->statut == 'SOLD')
                            @php $hasDocs = true; @endphp
                            <tr>
                                <td>Lettre définitive d'attribution</td>
                                <td>{{ $s->ref_souscription }}</td>
                                <td>{{ $s->updated_at->format('d/m/Y') }}</td>
                                <td><a href="#" class="btn-secondary btn-sm"><i class="fas fa-download me-1"></i> PDF</a></td>
                            </tr>
                        @endif
                        @if($s->attributionLot)
                            @php $hasDocs = true; @endphp
                            <tr>
                                <td>Attestation de réservation</td>
                                <td>{{ $s->ref_souscription }}</td>
                                <td>{{ $s->attributionLot->created_at->format('d/m/Y') }}</td>
                                <td><a href="#" class="btn-secondary btn-sm"><i class="fas fa-download me-1"></i> PDF</a></td>
                            </tr>
                        @endif
                    @endforeach
                    @if(!$hasDocs)
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">Aucun document disponible pour le moment.</td>
                        </tr>
                    @endif
                </x-data-table>
            @else
                <div class="list-body">
                    <x-empty-state title="Aucun document" message="Vos documents officiels seront disponibles dès validation de vos dossiers." />
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
