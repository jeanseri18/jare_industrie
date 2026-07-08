@extends('layouts.chef_commercial')

@section('title', 'Souscriptions corrigées - Chef Commercial')

@section('content')
<x-page-header title="Souscriptions corrigées" />
<x-alert />

<div class="stats-grid mb-4">
    <x-stat-card label="Total corrigées" :value="$totalCorrigees" icon="fas fa-check" icon-tone="gray" />
</div>

<x-list-filters-card action="{{ route('chef_commercial.souscriptions.corrige') }}" :reset-url="route('chef_commercial.souscriptions.corrige')">
    <div>
        <label class="form-label">Nom client</label>
        <input type="text" name="nom" class="form-control" placeholder="Nom client" value="{{ request('nom') }}">
    </div>
    <div>
        <label class="form-label">Code souscription</label>
        <input type="text" name="code" class="form-control" placeholder="Code souscription" value="{{ request('code') }}">
    </div>
    <div>
        <label class="form-label">N° client</label>
        <input type="text" name="num_client" class="form-control" placeholder="N° client" value="{{ request('num_client') }}">
    </div>
    <div>
        <label class="form-label">Correction</label>
        <select name="correction" class="form-select">
            <option value="">Tous</option>
            <option value="pas_corrige" {{ request('correction') === 'pas_corrige' ? 'selected' : '' }}>Non corrigée</option>
            <option value="corrige" {{ request('correction') === 'corrige' ? 'selected' : '' }}>Corrigée</option>
        </select>
    </div>
    <div>
        <label class="form-label">Date début</label>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div>
        <label class="form-label">Date fin</label>
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
</x-list-filters-card>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Operatrice</th>
            <th>N° client</th>
            <th>Nom client</th>
            <th>Projet</th>
            <th>Date de correction</th>
            <th>Statut</th>
            <th>Correction</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($souscriptionsCorrigees as $souscription)
        <tr>
            <td>{{ $souscription->operateur->name ?? 'Non défini' }}</td>
            <td>{{ $souscription->client->ref_client ?? 'N/A' }}</td>
            <td>{{ $souscription->client->nom_prenom ?? $souscription->nom_prenom ?? 'N/A' }}</td>
            <td>{{ $souscription->projet->nom ?? $souscription->programme }}</td>
            <td>{{ $souscription->updated_at?->format('d/m/Y') ?? '' }}</td>
            <td>{{ ucfirst($souscription->statut ?? '') }}</td>
            <td>{{ ($souscription->statut_correction ?? '') === 'corrige' ? 'Corrigée' : 'Non corrigée' }}</td>
            <td>
                <x-action-dropdown>
                    <li>
                        <a class="dropdown-item" href="{{ route('chef_commercial.souscriptions.edit', ['souscription' => $souscription->id]) }}">
                            <i class="fas fa-eye"></i> Voir
                        </a>
                    </li>
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8" class="text-center text-muted py-4">Aucune souscription trouvée</td>
        </tr>
    @endforelse
</x-data-table>

<x-pagination :paginator="$souscriptionsCorrigees" />
@endsection
