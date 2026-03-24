@extends('layouts.dg')

@section('title', 'Détails du Client')

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
</style>

<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            Détails du Client
        </div>
        <a href="{{ route('dg.clients.index') }}" class="btn btn-secondary">
            Retour à la liste
        </a>
    </div>
    
    <div style="padding: 20px;">
        <div class="card-custom mb-4">
            <div class="card-body-custom">
                <div class="row align-items-center">
                    <div class="col">
                        <h2 class="mb-1">{{ $client->nom_prenom }}</h2>
                        <div class="text-muted small d-flex gap-3 flex-wrap">
                            <span>Catégorie: {{ ucfirst($client->categorie_client ?? 'Non définie') }}</span>
                            <span>Référence: {{ $client->ref_client ?? 'Non définie' }}</span>
                            <span>Membre depuis {{ $client->created_at ? \Carbon\Carbon::parse($client->created_at)->format('d/m/Y') : '-' }}</span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('dg.clients.edit', $client) }}" class="btn btn-outline-secondary">
                            Modifier
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card-custom">
                    <div class="card-body-custom">
                        <div class="text-muted small mb-1 text-uppercase fw-semibold">Souscriptions</div>
                        <div class="fs-3 fw-bold">{{ $client->souscriptions->count() }}</div>
                        <div class="text-muted small mt-1">Total actif</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card-custom">
                    <div class="card-body-custom">
                        <div class="text-muted small mb-1 text-uppercase fw-semibold">Salaire Mensuel</div>
                        <div class="fs-4 fw-bold">{{ number_format($client->salaire_mensuel ?? 0, 0, ',', ' ') }} <span class="text-muted fs-6">FCFA</span></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card-custom">
                    <div class="card-body-custom">
                        <div class="text-muted small mb-1 text-uppercase fw-semibold">Enfants</div>
                        <div class="fs-3 fw-bold">{{ $client->nombre_enfants ?? 0 }}</div>
                        <div class="text-muted small mt-1">Personnes à charge</div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card-custom">
                    <div class="card-body-custom">
                        <div class="text-muted small mb-1 text-uppercase fw-semibold">Statut</div>
                        <div class="fw-bold">{{ ucfirst($client->situation_matrimoniale ?? 'N/A') }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="card-custom mb-3">
                    <div class="card-header-custom card-header-toggle d-flex justify-content-between align-items-center" 
                         data-bs-toggle="collapse" data-bs-target="#infoPerso">
                        <h5 class="mb-0">Informations Personnelles</h5>
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </div>
                    <div id="infoPerso" class="collapse show">
                        <div class="card-body-custom">
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Nom et Prénom</div>
                                <div>{{ $client->nom_prenom }}</div>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Date de Naissance</div>
                                <div>{{ $client->date_naissance ? \Carbon\Carbon::parse($client->date_naissance)->format('d/m/Y') : 'Non définie' }}</div>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Lieu de Naissance</div>
                                <div>{{ $client->lieu_naissance ?? 'Non défini' }}</div>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Nationalité</div>
                                <div>{{ $client->nationalite ?? 'Non définie' }}</div>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Situation Matrimoniale</div>
                                <div>{{ ucfirst($client->situation_matrimoniale ?? 'Non définie') }}</div>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Nombre d'Enfants</div>
                                <div>{{ $client->nombre_enfants ?? 0 }}</div>
                            </div>
                            <div class="mb-0">
                                <div class="text-muted small mb-1">Ayant Droit</div>
                                <div>{{ $client->ayant_droit ?? 'Non défini' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card-custom mb-3">
                    <div class="card-header-custom card-header-toggle d-flex justify-content-between align-items-center"
                         data-bs-toggle="collapse" data-bs-target="#infoContact">
                        <h5 class="mb-0">Informations de Contact</h5>
                        <i class="fas fa-chevron-down toggle-icon"></i>
                    </div>
                    <div id="infoContact" class="collapse show">
                        <div class="card-body-custom">
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Téléphone</div>
                                <div>
                                    @if($client->telephone)
                                        <a href="tel:{{ str_replace(' ', '', $client->telephone) }}" class="text-decoration-none">{{ $client->telephone }}</a>
                                    @else
                                        Non défini
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Email</div>
                                <div>
                                    @if($client->email)
                                        <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
                                    @else
                                        Non défini
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Salaire Mensuel</div>
                                <div class="fw-semibold">{{ number_format($client->salaire_mensuel ?? 0, 0, ',', ' ') }} FCFA</div>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Catégorie Client</div>
                                <span class="badge-custom badge-info">
                                    {{ ucfirst($client->categorie_client ?? 'Non définie') }}
                                </span>
                            </div>
                            <div class="mb-3 pb-2 border-bottom">
                                <div class="text-muted small mb-1">Mutuelle</div>
                                <div>{{ optional($client->mutuelle)->nom ?? 'Aucune' }}</div>
                            </div>
                            <div class="mb-0">
                                <div class="text-muted small mb-1">Référence Client</div>
                                <div class="font-monospace bg-light px-2 py-1 rounded">{{ $client->ref_client ?? 'Non définie' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-custom">
            <div class="card-header-custom card-header-toggle d-flex justify-content-between align-items-center"
                 data-bs-toggle="collapse" data-bs-target="#infoSouscriptions">
                <h5 class="mb-0">Souscriptions</h5>
                <i class="fas fa-chevron-down toggle-icon"></i>
            </div>
            <div id="infoSouscriptions" class="collapse show">
                <div class="card-body-custom">
                    @if($client->souscriptions->count() > 0)
                        <div class="table-responsive">
                            <table class="table-custom">
                                <thead>
                                    <tr>
                                        <th>Référence</th>
                                        <th>Programme</th>
                                        <th>Type Logement</th>
                                        <th>Coût Total</th>
                                        <th>Statut</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($client->souscriptions as $souscription)
                                    <tr>
                                        <td><strong>{{ $souscription->ref_souscription ?? 'Non définie' }}</strong></td>
                                        <td>{{ $souscription->projet->nom ?? $souscription->nom_programme ?? $souscription->programme ?? 'Non défini' }}</td>
                                        <td>{{ ucfirst($souscription->type_logement ?? 'Non défini') }}</td>
                                        <td><strong>{{ number_format($souscription->prix_logement ?? $souscription->valeur_souscription ?? 0, 0, ',', ' ') }} FCFA</strong></td>
                                        <td>
                                            @if($souscription->statut == 'SOLD')
                                                <span class="badge-custom badge-success">Soldé</span>
                                            @elseif($souscription->statut == 'annulee')
                                                <span class="badge-custom badge-danger">Annulée</span>
                                            @else
                                                <span class="badge-custom badge-warning">{{ ucfirst($souscription->statut ?? 'En cours') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $souscription->created_at ? \Carbon\Carbon::parse($souscription->created_at)->format('d/m/Y') : 'Non définie' }}</td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('dg.souscriptions.paiements', $souscription) }}" class="btn btn-sm btn-info text-white" title="Voir les paiements">
                                                    <i class="fas fa-money-bill-wave"></i> Paiements
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Aucune souscription trouvée pour ce client.
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('dg.clients.edit', $client) }}" class="btn btn-warning">
                Modifier le Client
            </a>
            <a href="{{ route('dg.clients.index') }}" class="btn btn-secondary ms-2">
                Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection
