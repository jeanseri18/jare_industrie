@extends('layouts.dg')

@section('title', 'Attribution de logement')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-key me-2"></i>
            Attribution de logement
        </div>
        <div>
            <a href="{{ route('dg.attribution.index') }}" class="btn btn-primary"><i class="fas fa-key me-1"></i> Attribuer un logement</a>
            <a href="{{ route('dg.confirmation.index') }}" class="btn btn-outline-primary"><i class="fas fa-check me-1"></i> Confirmer un dossier soldé</a>
        </div>
    </div>

    <div style="padding: 20px;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Projet</th>
                        <th>Type logement</th>
                        <th>Date validation</th>
                        <th>Statut attribution</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($souscriptions as $s)
                        @php
                            $estAttribue = $s->attributionLot !== null;
                        @endphp
                        <tr>
                            <td>{{ optional($s->client)->nom_prenom }}</td>
                            <td>{{ optional($s->projet)->nom }}</td>
                            <td>{{ $s->type_logement }}</td>
                            <td>{{ optional($s->date_fin)->format('d/m/y') }}</td>
                            <td>
                                <span class="badge {{ $estAttribue ? 'bg-success' : 'bg-secondary' }}">
                                    {{ $estAttribue ? 'Attribué' : 'Non attribué' }}
                                </span>
                            </td>
                            <td>
                                @if($estAttribue)
                                    <div class="btn-group">
                                        <a href="{{ route('dg.souscriptions.show', $s) }}" class="btn btn-outline-info btn-sm"><i class="fas fa-eye me-1"></i> Voir</a>
                                        <a href="{{ route('dg.souscriptions.attestation', $s) }}" class="btn btn-outline-secondary btn-sm" title="Ouvrir l'attestation de réservation" target="_blank" rel="noopener"><i class="fas fa-file-pdf"></i></a>
                                    </div>
                                @else
                                    <a href="{{ route('dg.attribution.show', $s) }}" class="btn btn-info btn-sm"><i class="fas fa-key me-1"></i> Attribuer</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucune souscription en attente d'attribution pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
    @if(session('download_attestation_url'))
        <div class="alert alert-success d-flex align-items-center justify-content-between" role="alert" style="margin: 15px 0;">
            <div>
                <i class="fas fa-file-pdf me-2"></i>
                Attestation de réservation prête.
            </div>
            <a href="{{ session('download_attestation_url') }}" class="btn btn-success btn-sm" target="_blank" rel="noopener">
                Ouvrir
            </a>
        </div>
    @endif
@endsection
