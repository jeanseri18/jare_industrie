@extends('layouts.dg')

@section('title', 'Mutuelles')

@section('content')
<x-page-header title="Liste des mutuelles">
    <x-slot:actions>
        <a href="{{ route('dg.mutuelles.create') }}" class="btn-primary">
            <i class="fas fa-plus me-1"></i> Créer une mutuelle
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

<x-data-table>
    <x-slot:head>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Code</th>
            <th>Contact</th>
            <th>Site web</th>
            <th>Projet associé</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($mutuelles as $mutuelle)
        <tr>
            <td>{{ $mutuelle->id }}</td>
            <td>{{ $mutuelle->nom }}</td>
            <td>{{ $mutuelle->code }}</td>
            <td>
                @if($mutuelle->nom_contact)
                    <div>{{ $mutuelle->nom_contact }}</div>
                    @if($mutuelle->telephone_contact)
                        <small class="text-muted">{{ $mutuelle->telephone_contact }}</small>
                    @endif
                @else
                    <span class="text-muted">Aucun contact</span>
                @endif
            </td>
            <td>
                @if($mutuelle->site_web)
                    <a href="{{ $mutuelle->site_web }}" target="_blank" class="text-primary">
                        <i class="fas fa-external-link-alt"></i> Visiter
                    </a>
                @else
                    <span class="text-muted">Non défini</span>
                @endif
            </td>
            <td>
                @if($mutuelle->projet)
                    <span class="badge-custom badge-info">{{ $mutuelle->projet->nom }}</span>
                @else
                    <span class="text-muted">Aucun projet</span>
                @endif
            </td>
            <td>
                <span class="badge-custom {{ $mutuelle->est_active ? 'badge-success' : 'badge-danger' }}">
                    {{ $mutuelle->est_active ? 'Active' : 'Inactive' }}
                </span>
            </td>
            <td>
                <x-action-dropdown>
                    <li>
                        <a href="{{ route('dg.mutuelles.edit', $mutuelle) }}" class="dropdown-item">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('dg.mutuelles.destroy', $mutuelle) }}" method="POST" class="action-dropdown-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette mutuelle ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="fas fa-trash"></i> Supprimer
                            </button>
                        </form>
                    </li>
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr><td colspan="8"><x-empty-state title="Aucune mutuelle" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$mutuelles" />
@endsection
