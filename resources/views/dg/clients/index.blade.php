@extends('layouts.dg')

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
        <form action="{{ route('dg.clients.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Recherche globale (Nom, Code, Email, Tel)</label>
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Saisir un nom, code, email ou tel..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Date de création</label>
                <input type="date" name="date_creation" class="form-control" value="{{ request('date_creation') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Projet</label>
                <select class="form-select" name="projet_id">
                    <option value="">Tous</option>
                    @foreach($projets as $projet)
                        <option value="{{ $projet->id }}" {{ (string)request('projet_id') === (string)$projet->id ? 'selected' : '' }}>
                            {{ $projet->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Mutuelle</label>
                <select class="form-select" name="mutuelle_id">
                    <option value="">Toutes</option>
                    @foreach($mutuelles as $mutuelle)
                        <option value="{{ $mutuelle->id }}" {{ (string)request('mutuelle_id') === (string)$mutuelle->id ? 'selected' : '' }}>
                            {{ $mutuelle->nom }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-12 d-flex gap-2 mt-2">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="fas fa-filter me-2"></i>Filtrer
                </button>
                <a href="{{ route('dg.clients.index') }}" class="btn btn-outline-secondary px-4">
                    <i class="fas fa-sync-alt me-2"></i>Réinitialiser
                </a>
                <a href="{{ route('dg.clients.export.pdf', request()->query()) }}" class="btn btn-outline-danger px-4 ms-auto">
                    <i class="fas fa-file-pdf me-2"></i>Exporter PDF
                </a>
                <a href="{{ route('dg.clients.export.excel', request()->query()) }}" class="btn btn-outline-success px-4">
                    <i class="fas fa-file-excel me-2"></i>Exporter Excel
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
                        <th>Mutuelle</th>
                        <th>Projet</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $client)
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
                        <td>{{ $client->mutuelle?->nom ?? '-' }}</td>
                        <td>{{ $client->lastSouscription?->projet?->nom ?? '-' }}</td>
                        <td>{{ $client->telephone }}</td>
                        <td>{{ $client->email }}</td>
                        <td>
                            <div class="btn-group-custom">
                                <a href="{{ route('dg.clients.show', $client) }}" class="btn btn-info btn-sm" title="Voir les détails">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('dg.clients.edit', $client) }}" class="btn btn-warning btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('dg.clients.password.edit', $client) }}" class="btn btn-outline-warning btn-sm" title="Modifier le mot de passe">
                                    <i class="fas fa-key"></i>
                                </a>
                                
                                <form action="{{ route('dg.clients.destroy', $client) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ?')" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $clients->links() }}
        </div>
    </div>
</div>
@endsection
