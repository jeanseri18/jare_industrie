@extends('layouts.dg')

@section('title', 'Biens Immobiliers - ' . $projet->nom)

@section('content')
<x-page-header :title="'Biens immobiliers — ' . $projet->nom">
    <x-slot:actions>
        <a href="{{ route('dg.projets.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Retour aux projets
        </a>
        <a href="{{ route('dg.projets.biens.create', $projet) }}" class="btn-primary">
            <i class="fas fa-plus me-1"></i> Nouveau bien
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

<x-data-table>
    <x-slot:head>
        <tr>
            <th>ID</th>
            <th>Titre</th>
            <th>Type</th>
            <th>Prix</th>
            <th>Frais souscription</th>
            <th>Apport (%)</th>
            <th>Apport initial</th>
            <th>Surface</th>
            <th>Pièces</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($biens as $bien)
        <tr>
            <td>{{ $bien->id }}</td>
            <td>{{ $bien->titre }}</td>
            <td><span class="badge-custom badge-info">{{ ucfirst($bien->type) }}</span></td>
            <td>{{ number_format($bien->prix, 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format($bien->frais_souscription, 0, ',', ' ') }} FCFA</td>
            <td>{{ $bien->pourcentage_apport }}%</td>
            <td>{{ $bien->apport_initial ? number_format($bien->apport_initial, 0, ',', ' ') . ' FCFA' : 'N/A' }}</td>
            <td>{{ $bien->surface_habitable ?? 'N/A' }} m²{{ $bien->surface_total ? ' / '.$bien->surface_total.' m²' : '' }}</td>
            <td>{{ $bien->nbre_piece ?? 'N/A' }}</td>
            <td>
                <x-action-dropdown>
                    <li><a href="{{ route('dg.biens.edit', $bien) }}" class="dropdown-item"><i class="fas fa-edit"></i> Modifier</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('dg.biens.destroy', $bien) }}" method="POST" class="action-dropdown-form" onsubmit="return confirm('Supprimer ce bien ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash"></i> Supprimer</button>
                        </form>
                    </li>
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr><td colspan="10"><x-empty-state title="Aucun bien immobilier" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$biens" />
@endsection
