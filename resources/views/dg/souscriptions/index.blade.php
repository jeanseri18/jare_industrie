@extends('layouts.dg')

@section('title', 'Gestion des Souscriptions')

@section('content')
<x-page-header title="Gestion des Souscriptions">
    <x-slot:actions>
        <a href="{{ route('dg.souscriptions.create') }}" class="btn-primary">
            <i class="fas fa-plus me-1"></i> Ajouter une souscription
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

<x-filter-bar action="{{ route('dg.souscriptions.index') }}">
    <div>
        <label for="statut" class="form-label">Statut</label>
        <select class="form-select" id="statut" name="statut">
            <option value="" {{ request('statut')=='' ? 'selected' : '' }}>Tous</option>
            <option value="en_attente" {{ request('statut')=='en_attente' ? 'selected' : '' }}>En attente</option>
            <option value="en_cours" {{ request('statut')=='en_cours' ? 'selected' : '' }}>En cours</option>
            <option value="soldé" {{ request('statut')=='soldé' ? 'selected' : '' }}>Soldé</option>
            <option value="annulee" {{ request('statut')=='annulee' ? 'selected' : '' }}>Annulée</option>
        </select>
    </div>
</x-filter-bar>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Référence</th>
            <th>Client</th>
            <th>Projet</th>
            <th>Type Logement</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($souscriptions as $souscription)
        <tr>
            <td>{{ $souscription->ref_souscription }}</td>
            <td>{{ optional($souscription->client)->nom_prenom }}</td>
            <td>{{ optional($souscription->projet)->nom }}</td>
            <td>{{ $souscription->type_logement }}</td>
            <td>{{ $souscription->created_at->format('d/m/Y') }}</td>
            <td>
                @if($souscription->statut == 'annulee')
                    <span class="status-badge status-corriger">Annulée</span>
                @elseif($souscription->statut == 'valide')
                    <span class="status-badge status-valide">Validée</span>
                @elseif($souscription->statut == 'en_attente')
                    <span class="status-badge status-attente">En attente</span>
                @else
                    <span class="status-badge status-attente">{{ $souscription->statut }}</span>
                @endif
            </td>
            <td>
                <x-action-dropdown>
                    <li>
                        <a href="{{ route('dg.souscriptions.show', $souscription) }}" class="dropdown-item">
                            <i class="fas fa-eye"></i> Voir détails
                        </a>
                    </li>
                    @if($souscription->statut != 'annulee')
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('dg.souscriptions.annuler', $souscription) }}" method="POST" class="action-dropdown-form" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette souscription ?')">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fas fa-times-circle"></i> Annuler
                                </button>
                            </form>
                        </li>
                    @endif
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr><td colspan="7"><x-empty-state title="Aucune souscription trouvée" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$souscriptions" />
@endsection
