@extends('layouts.comptable')

@section('title', 'Édition de Reçus')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title">Édition de Reçus</h2>
        </div>
    </div>

    <!-- Filtres -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('comptable.edition-recus') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Client</label>
                            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Nom, Prénom, Référence client...">
                        </div>
                        <div class="col-md-3">
                            <label for="date_debut" class="form-label">Date début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_fin" class="form-label">Date fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="row">
        <div class="col-12">
            <div class="data-table-container">
                <div class="card-header-custom">
                     <h5 class="card-title-custom"><i class="fas fa-print"></i> Liste des Paiements Validés</h5>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Référence Paiement</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Projet</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Mode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paiements as $paiement)
                            <tr>
                                <td>{{ $paiement->reference }}</td>
                                <td>{{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : 'N/A' }}</td>
                                <td>{{ $paiement->souscription->client->nom_prenom ?? 'N/A' }} <br> <small class="text-muted">{{ $paiement->souscription->client->ref_client ?? '' }}</small></td>
                                <td>{{ $paiement->souscription->projet->nom ?? 'N/A' }}</td>
                                <td>
                                    @if($paiement->type == 'FRAIS_DOSSIER')
                                        <span class="badge-custom badge-warning">Frais Dossier</span>
                                    @elseif($paiement->type == 'APPORT')
                                        <span class="badge-custom badge-success">Apport</span>
                                    @elseif($paiement->type == 'PROJET')
                                        <span class="badge-custom badge-primary">Projet</span>
                                    @else
                                        <span class="badge-custom badge-info">{{ ucfirst(str_replace('_', ' ', $paiement->type)) }}</span>
                                    @endif
                                </td>
                                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                <td>{{ ucfirst(str_replace('_', ' ', $paiement->mode)) }}</td>
                                <td>
                                    <a href="{{ route('comptable.paiements.recu', $paiement) }}" target="_blank" class="btn btn-sm btn-dark" title="Imprimer le reçu">
                                        <i class="fas fa-print"></i> Imprimer
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun paiement trouvé. Veuillez utiliser les filtres.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $paiements->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
