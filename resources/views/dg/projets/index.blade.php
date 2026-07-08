@extends('layouts.dg')

@section('title', 'Gestion des Projets')

@section('content')
<x-page-header title="Gestion des Projets">
    <x-slot:actions>
        <a href="{{ route('dg.projets.create') }}" class="btn-primary">
            <i class="fas fa-plus me-1"></i> Nouveau projet
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

<x-data-table>
    <x-slot:head>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Localisation</th>
            <th>Superficie</th>
            <th>Biens / Lots</th>
            <th>Souscriptions actives</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($projets as $projet)
        <tr>
            <td>{{ $projet->id }}</td>
            <td>{{ $projet->nom }}</td>
            <td>{{ $projet->localisation ?? 'Non définie' }}</td>
            <td>{{ $projet->superficie ?? 'N/A' }} m²</td>
            <td>
                <div class="small text-muted">
                    <div><i class="fas fa-building me-1"></i> {{ $projet->bien_immobiliers_count }} bien(s)</div>
                    <div><i class="fas fa-map-marked-alt me-1"></i> {{ $projet->projet_lots_count }} lot(s)</div>
                </div>
            </td>
            <td>
                <span class="badge-custom badge-info">
                    {{ $projet->souscriptions()->whereIn('statut', ['valide', 'ATTRIBUE', 'SOLD'])->count() }}
                </span>
            </td>
            <td>
                <span class="badge-custom badge-{{ $projet->est_actif ? 'success' : 'danger' }}">
                    {{ $projet->est_actif ? 'Actif' : 'Inactif' }}
                </span>
            </td>
            <td>
                <x-action-dropdown>
                    <li>
                        <a href="{{ route('dg.projets.lots.index', $projet) }}" class="dropdown-item">
                            <i class="fas fa-map-marked-alt"></i> Îlots & lots ({{ $projet->projet_lots_count }})
                        </a>
                    </li>
                    @if($projet->projet_lots_count > 0)
                        <li>
                            <a href="{{ route('dg.projets.biens.index', $projet) }}" class="dropdown-item">
                                <i class="fas fa-building"></i> Voir les biens ({{ $projet->bien_immobiliers_count }})
                            </a>
                        </li>
                    @else
                        <li>
                            <span class="dropdown-item text-muted disabled" title="Créez d’abord des lots pour ce projet.">
                                <i class="fas fa-building"></i> Voir les biens (indisponible)
                            </span>
                        </li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a href="{{ route('dg.projets.edit', $projet) }}" class="dropdown-item">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('dg.projets.destroy', $projet) }}" method="POST" class="action-dropdown-form" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
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
        <tr><td colspan="8"><x-empty-state title="Aucun projet" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$projets" />
@endsection
