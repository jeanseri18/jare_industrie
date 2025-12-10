@extends('layouts.dg')

@section('title', 'Gestion des Clients')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-users me-2"></i>
            Gestion des Clients
        </div>
        <!-- <a href="{{ route('dg.clients.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Client
        </a> -->
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