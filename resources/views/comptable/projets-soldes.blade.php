@extends('layouts.comptable')

@section('title', 'Projets Soldés')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title">Projets Soldés</h2>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Projets Soldés</span>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $projetsSoldes->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Montant Total</span>
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($projetsSoldes->sum('montant'), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Moyenne par Projet</span>
                    <div class="stat-icon icon-purple">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <div class="stat-value">{{ $projetsSoldes->count() > 0 ? number_format($projetsSoldes->sum('montant') / $projetsSoldes->count(), 0, ',', ' ') : 0 }} FCFA</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Ce Mois</span>
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-calendar"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($projetsSoldes->where('valide_at', '>=', now()->startOfMonth())->sum('montant'), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('comptable.projets-soldes') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Référence, client, projet...">
                        </div>
                        <div class="col-md-3">
                            <label for="projet" class="form-label">Projet</label>
                            <select class="form-select" id="projet" name="projet">
                                <option value="">Tous les projets</option>
                                @foreach($projets as $projet)
                                    <option value="{{ $projet->id }}" {{ request('projet') == $projet->id ? 'selected' : '' }}>{{ $projet->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="type" class="form-label">Type de paiement</label>
                            <select class="form-select" id="type" name="type">
                                <option value="">Tous</option>
                                <option value="mensualite" {{ request('type') == 'mensualite' ? 'selected' : '' }}>Mensualité</option>
                                <option value="acompte" {{ request('type') == 'acompte' ? 'selected' : '' }}>Acompte</option>
                                <option value="apport_initial" {{ request('type') == 'apport_initial' ? 'selected' : '' }}>Apport initial</option>
                                <option value="frais_dossier" {{ request('type') == 'frais_dossier' ? 'selected' : '' }}>Frais de dossier</option>
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
                            <a href="{{ route('comptable.projets-soldes') }}" class="btn btn-secondary">
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
                <div class="card-header-custom">
                    <h5 class="card-title-custom">
                        <i class="fas fa-list"></i>
                        Liste des Projets Soldés
                    </h5>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Référence</th>
                                <th>Client</th>
                                <th>Projet</th>
                                <th>Type</th>
                                <th>Montant</th>
                                <th>Date de Paiement</th>
                                <th>Date de Validation</th>
                                <th>Comptable</th>
                                <th>Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($projetsSoldes as $paiement)
                            <tr>
                                <td>{{ $paiement->reference }}</td>
                                <td>{{ $paiement->souscription->client->nom_prenom ?? 'N/A' }}</td>
                                <td>{{ $paiement->souscription->projet->nom ?? 'N/A' }}</td>
                                <td>
                                    @if($paiement->type == 'mensualite')
                                        <span class="badge badge-info">Mensualité</span>
                                    @elseif($paiement->type == 'acompte')
                                        <span class="badge badge-primary">Acompte</span>
                                    @elseif($paiement->type == 'apport_initial')
                                        <span class="badge badge-success">Apport Initial</span>
                                    @elseif($paiement->type == 'frais_dossier')
                                        <span class="badge badge-warning">Frais Dossier</span>
                                    @else
                                        <span class="badge badge-secondary">{{ ucfirst($paiement->type) }}</span>
                                    @endif
                                </td>
                                <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                                <td>{{ $paiement->valide_at ? $paiement->valide_at->format('d/m/Y H:i') : 'Non validé' }}</td>
                                <td>{{ $paiement->comptable->name ?? 'Non assigné' }}</td>
                                <td>
                                    <span class="badge badge-success">
                                        <i class="fas fa-check"></i> Soldé
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Aucun projet soldé trouvé</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $projetsSoldes->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection