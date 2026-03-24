@extends('layouts.comptable')

@section('title', 'Gestion des Clients')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-users me-2"></i>
            Gestion des Clients
        </div>
    </div>
    
    <div class="p-3 bg-light border-bottom">
        <form action="{{ route('comptable.clients.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Recherche globale (Nom, Code, Email, Tel)</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Saisir un nom, code, email ou tel..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Date de création</label>
                <input type="date" name="date_creation" class="form-control" value="{{ request('date_creation') }}">
            </div>
            <div class="col-md-5 d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-filter me-2"></i>Filtrer
                </button>
                <a href="{{ route('comptable.clients.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-sync-alt me-2"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    <div style="padding: 20px;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom et Prénom</th>
                        <th>Date de Naissance</th>
                        <th>Nationalité</th>
                        <th>Catégorie</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                    <tr>
                        <td>{{ $client->id }}</td>
                        <td>{{ $client->nom_prenom }}</td>
                        <td>{{ $client->date_naissance ? \Carbon\Carbon::parse($client->date_naissance)->format('d/m/Y') : '' }}</td>
                        <td>{{ $client->nationalite }}</td>
                        <td>
                            <span class="badge-custom badge-info">
                                {{ ucfirst($client->categorie_client) }}
                            </span>
                        </td>
                        <td>{{ $client->telephone }}</td>
                        <td>{{ $client->email }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('comptable.clients.show', $client) }}" class="btn btn-outline-primary btn-sm" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">Aucun client trouvé</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $clients->links() }}
        </div>
    </div>
</div>
@endsection
