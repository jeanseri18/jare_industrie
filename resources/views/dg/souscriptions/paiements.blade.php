@extends('layouts.dg')

@section('title', 'Paiements de la Souscription ' . $souscription->ref_souscription)

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title">Paiements de la Souscription {{ $souscription->ref_souscription }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dg.dashboard') }}">Tableau de bord</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('dg.clients.show', $souscription->client_id) }}">Détails du client</a></li>
                    <li class="breadcrumb-item active">Paiements de la souscription</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Informations de la souscription -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Informations de la souscription</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Référence Client:</strong> {{ $souscription->client->ref_client ?? 'N/A' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Référence Souscription:</strong> {{ $souscription->ref_souscription }}
                        </div>
                        <div class="col-md-3">
                            <strong>Client:</strong> {{ $souscription->client->nom_prenom ?? 'N/A' }}
                        </div>
                        <div class="col-md-3">
                            <strong>Projet:</strong> {{ $souscription->projet->nom ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <strong>Prix du logement:</strong> {{ number_format($souscription->prix_logement, 0, ',', ' ') }} FCFA
                        </div>
                        <div class="col-md-3">
                            <strong>Montant total payé:</strong> {{ number_format($paiements->where('statut', 'payé')->sum('montant'), 0, ',', ' ') }} FCFA
                        </div>
                        <div class="col-md-3">
                            <strong>Montant restant:</strong> {{ number_format(max($souscription->prix_logement - $paiements->where('statut', 'payé')->sum('montant'), 0), 0, ',', ' ') }} FCFA
                        </div>
                        <div class="col-md-3">
                            <strong>Mode de paiement:</strong> {{ $souscription->mode_paiement ?? 'Non défini' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('dg.souscriptions.paiements', $souscription) }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Référence, type, montant...">
                        </div>
                        <div class="col-md-3">
                            <label for="type" class="form-label">Type de paiement</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Tous les types</option>
                                <option value="FRAIS_DOSSIER" {{ request('type') == 'FRAIS_DOSSIER' ? 'selected' : '' }}>Frais de dossier</option>
                                <option value="APPORT" {{ request('type') == 'APPORT' ? 'selected' : '' }}>Apport</option>
                                <option value="PROJET" {{ request('type') == 'PROJET' ? 'selected' : '' }}>Projet</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="statut" class="form-label">Statut</label>
                            <select class="form-select" id="statut" name="statut">
                                <option value="">Tous</option>
                                <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
                                <option value="payé" {{ request('statut') == 'payé' ? 'selected' : '' }}>Payé</option>
                                <option value="annulé" {{ request('statut') == 'annulé' ? 'selected' : '' }}>Annulé</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_debut" class="form-label">Date début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                   value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_fin" class="form-label">Date fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                   value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                            <a href="{{ route('dg.souscriptions.paiements', $souscription) }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Table des paiements -->
    <div class="row">
        <div class="col-12">
            <div class="data-table-container">
                <div class="card-header-custom d-flex justify-content-between align-items-center">
                    <h5 class="card-title-custom mb-0">
                        <i class="fas fa-list"></i>
                        Historique des paiements
                    </h5>
                    <a href="{{ route('comptable.souscriptions.etat-versements', $souscription) }}" target="_blank" class="btn btn-primary btn-sm">
                        <i class="fas fa-print"></i> Imprimer l'état des versements
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Mode</th>
                                <th>Comptable</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paiements as $paiement)
                            <tr>
                                <td>{{ $paiement->reference }}</td>
                                <td>
                                    @if($paiement->type == 'FRAIS_DOSSIER')
                                        <span class="badge bg-secondary">Frais de dossier</span>
                                    @elseif($paiement->type == 'APPORT')
                                        <span class="badge bg-warning text-dark">Apport</span>
                                    @elseif($paiement->type == 'PROJET')
                                        <span class="badge bg-info text-dark">Projet</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $paiement->type }}</span>
                                    @endif
                                </td>
                                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @if($paiement->statut == 'en_attente')
                                        <span class="badge bg-warning text-dark">En Attente</span>
                                    @elseif($paiement->statut == 'payé')
                                        <span class="badge bg-success">Payé</span>
                                    @elseif($paiement->statut == 'annulé')
                                        <span class="badge bg-danger">Annulé</span>
                                    @endif
                                </td>
                                <td>{{ ucfirst($paiement->mode) }}</td>
                                <td>{{ $paiement->comptable->name ?? 'Non assigné' }}</td>
                                <td>
                                    <div class="btn-group">
                                        @if($paiement->statut == 'payé')
                                            <a href="{{ route('comptable.paiements.recu', $paiement) }}" target="_blank" class="btn btn-sm btn-dark" title="Imprimer le reçu">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun paiement trouvé pour cette souscription</td>
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
