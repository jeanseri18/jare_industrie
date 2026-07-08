@extends('layouts.dg')

@section('title', 'Gestion des Clients')

@section('content')
<x-page-header title="Gestion des clients">
    <x-slot:actions>
        <a href="{{ route('dg.clients.export.pdf', request()->query()) }}" class="btn-secondary">
            <i class="fas fa-file-pdf me-1"></i> PDF
        </a>
        <a href="{{ route('dg.clients.export.excel', request()->query()) }}" class="btn-secondary">
            <i class="fas fa-file-excel me-1"></i> Excel
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

<x-list-filters-card action="{{ route('dg.clients.index') }}" :reset-url="route('dg.clients.index')">
    <div>
        <label class="form-label">Recherche (nom, code, email, tel)</label>
        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
    </div>
    <div>
        <label class="form-label">Date de création</label>
        <input type="date" name="date_creation" class="form-control" value="{{ request('date_creation') }}">
    </div>
    <div>
        <label class="form-label">Projet</label>
        <select class="form-select" name="projet_id">
            <option value="">Tous</option>
            @foreach($projets as $projet)
                <option value="{{ $projet->id }}" {{ (string) request('projet_id') === (string) $projet->id ? 'selected' : '' }}>{{ $projet->nom }}</option>
            @endforeach
        </select>
    </div>
    <div>
        <label class="form-label">Mutuelle</label>
        <select class="form-select" name="mutuelle_id">
            <option value="">Toutes</option>
            @foreach($mutuelles as $mutuelle)
                <option value="{{ $mutuelle->id }}" {{ (string) request('mutuelle_id') === (string) $mutuelle->id ? 'selected' : '' }}>{{ $mutuelle->nom }}</option>
            @endforeach
        </select>
    </div>
</x-list-filters-card>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>ID</th>
            <th>Nom et prénom</th>
            <th>Date de naissance</th>
            <th>Nationalité</th>
            <th>Catégorie</th>
            <th>Mutuelle</th>
            <th>Projet</th>
            <th>Téléphone</th>
            <th>Email</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($clients as $client)
        <tr>
            <td>{{ $client->id }}</td>
            <td>{{ $client->nom_prenom }}</td>
            <td>{{ $client->date_naissance ? \Carbon\Carbon::parse($client->date_naissance)->format('d/m/Y') : '' }}</td>
            <td>{{ $client->nationalite }}</td>
            <td><span class="badge-custom badge-info">{{ ucfirst($client->categorie_client) }}</span></td>
            <td>{{ $client->mutuelle?->nom ?? '-' }}</td>
            <td>{{ $client->lastSouscription?->projet?->nom ?? '-' }}</td>
            <td>{{ $client->telephone }}</td>
            <td>{{ $client->email }}</td>
            <td>
                <x-action-dropdown>
                    <li><a href="{{ route('dg.clients.show', $client) }}" class="dropdown-item"><i class="fas fa-eye"></i> Voir</a></li>
                    <li><a href="{{ route('dg.clients.edit', $client) }}" class="dropdown-item"><i class="fas fa-edit"></i> Modifier</a></li>
                    <li><a href="{{ route('dg.clients.password.edit', $client) }}" class="dropdown-item"><i class="fas fa-key"></i> Mot de passe</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('dg.clients.destroy', $client) }}" method="POST" class="action-dropdown-form" onsubmit="return confirm('Supprimer ce client ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash"></i> Supprimer</button>
                        </form>
                    </li>
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr><td colspan="10"><x-empty-state title="Aucun client" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$clients" />
@endsection
