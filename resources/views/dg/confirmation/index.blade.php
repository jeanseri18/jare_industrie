@extends('layouts.dg')

@section('title', 'Confirmation de dossier soldé')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-check-circle me-2"></i>
            Confirmation de dossier soldé
        </div>
        <div>
            <a href="{{ route('dg.attribution.index') }}" class="btn btn-outline-primary"><i class="fas fa-key me-1"></i> Attribuer un logement</a>
            <a href="{{ route('dg.confirmation.index') }}" class="btn btn-primary"><i class="fas fa-check me-1"></i> Confirmer un dossier soldé</a>
        </div>
    </div>

    <div style="padding: 20px;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Projet</th>
                        <th>Montant total</th>
                        <th>Statut</th>
                        <th>Date dernier paiement</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($souscriptions as $s)
                        <tr>
                            <td>{{ optional($s->client)->nom_prenom }}</td>
                            <td>{{ optional($s->projet)->nom }}</td>
                            <td>{{ number_format($s->montant_total ?? (($s->prix_logement ?? 0) + \App\Models\FraisDossier::where('id_souscription', $s->id)->sum('montant')), 0, ',', ' ') }} FCFA</td>
                            <td>
                                <span class="badge-custom bg-success">Soldé</span>
                            </td>
                            <td>{{ optional($s->dernier_paiement?->date_paiement)->format('d/m/y') }}</td>
                            <td>
                                @php
                                    $vf = \App\Models\ValidationFinale::where('idsouscription', $s->id)->first();
                                @endphp
                                @if(!$s->attributionLot)
                                    <a href="{{ route('dg.attribution.show', $s) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-key me-1"></i> Attribuer
                                    </a>
                                @elseif($vf)
                                    <a href="{{ route('dg.souscriptions.lettre-definitive', $s) }}" class="btn btn-success btn-sm" target="_blank" rel="noopener">
                                        <i class="fas fa-file-download me-1"></i> Générer la lettre
                                    </a>
                                @else
                                    <a href="{{ route('dg.souscriptions.confirmation', $s) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye me-1"></i> Confirmer
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">Aucun dossier soldé en attente de confirmation pour le moment.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@if(session('download_lettre_url'))
    <div class="alert alert-success d-flex align-items-center justify-content-between" role="alert" style="margin: 15px 0;">
        <div>
            <i class="fas fa-file-pdf me-2"></i>
            Lettre définitive prête.
        </div>
        <a href="{{ session('download_lettre_url') }}" class="btn btn-success btn-sm" target="_blank" rel="noopener">
            Ouvrir
        </a>
    </div>
@endif
@endsection
