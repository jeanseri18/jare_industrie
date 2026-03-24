@extends('layouts.dg')

@section('title', 'Gestion des Souscriptions')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-file-contract me-2"></i>
            Gestion des Souscriptions
        </div>
        <div>
            <a href="{{ route('dg.souscriptions.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Ajouter une souscription
            </a>
        </div>
    </div>

    <div style="padding: 20px;">
        <form method="GET" action="{{ route('dg.souscriptions.index') }}" class="row g-3 mb-3">
            <div class="col-md-3">
                <label for="statut" class="form-label">Statut</label>
                <select class="form-select" id="statut" name="statut">
                    <option value="" {{ request('statut')=='' ? 'selected' : '' }}>Tous</option>
                    <option value="en_attente" {{ request('statut')=='en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="en_cours" {{ request('statut')=='en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="soldé" {{ request('statut')=='soldé' ? 'selected' : '' }}>Soldé</option>
                    <option value="annulee" {{ request('statut')=='annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <button type="submit" class="btn btn-primary"><i class="fas fa-filter me-1"></i> Filtrer</button>
                <a href="{{ route('dg.souscriptions.index') }}" class="btn btn-outline-secondary ms-2"><i class="fas fa-undo me-1"></i> Réinitialiser</a>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Projet</th>
                        <th>Type Logement</th>
                        <th>Date</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($souscriptions as $souscription)
                        <tr>
                            <td>{{ $souscription->ref_souscription }}</td>
                            <td>{{ optional($souscription->client)->nom_prenom }}</td>
                            <td>{{ optional($souscription->projet)->nom }}</td>
                            <td>{{ $souscription->type_logement }}</td>
                            <td>{{ $souscription->created_at->format('d/m/Y') }}</td>
                            <td>
                                @if($souscription->statut == 'annulee')
                                    <span class="badge bg-danger">Annulée</span>
                                @elseif($souscription->statut == 'valide')
                                    <span class="badge bg-success">Validée</span>
                                @elseif($souscription->statut == 'en_attente')
                                    <span class="badge bg-warning">En attente</span>
                                @else
                                    <span class="badge bg-secondary">{{ $souscription->statut }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('dg.souscriptions.show', $souscription) }}" class="btn btn-outline-info btn-sm" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($souscription->statut != 'annulee')
                                        <form action="{{ route('dg.souscriptions.annuler', $souscription) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette souscription ?');" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Annuler la souscription">
                                                <i class="fas fa-times-circle"></i> Annuler
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Aucune souscription trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="mt-3">
                {{ $souscriptions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
