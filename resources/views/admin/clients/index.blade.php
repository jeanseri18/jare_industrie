@extends('layouts.admin')

@section('title', 'Clients')

@section('content')
<x-page-header title="Liste des clients" />
<x-alert />

<x-filter-bar action="{{ route('admin.clients.index') }}">
    <div class="md:col-span-5">
        <label class="form-label">Recherche</label>
        <input type="text" name="search" class="form-input" placeholder="Nom, email, téléphone..." value="{{ request('search') }}">
    </div>
</x-filter-bar>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($clients as $client)
        <tr>
            <td>{{ $client->id }}</td>
            <td>{{ $client->name }}</td>
            <td>{{ $client->email }}</td>
            <td>{{ $client->telephone ?? '—' }}</td>
            <td>{{ optional($client->created_at)->format('d/m/Y') }}</td>
            <td>
                <x-action-dropdown>
                    <li>
                        <a href="{{ route('admin.clients.password.edit', $client) }}" class="dropdown-item">
                            <i class="fas fa-key"></i> Modifier le mot de passe
                        </a>
                    </li>
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr><td colspan="6"><x-empty-state title="Aucun client trouvé" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$clients" />
@endsection
