@extends('layouts.chef_commercial')

@section('title', 'Souscriptions à corriger - Chef Commercial')

@section('content')
<x-page-header title="Souscriptions à corriger" />
<x-alert />

<div class="stats-grid mb-4">
    <x-stat-card label="Total à corriger" :value="$totalEnAttente" icon="fas fa-exclamation-triangle" icon-tone="red" />
    @foreach($souscriptionsParOperateur as $stat)
        <x-stat-card
            :label="$stat->operateur->name ?? 'Opérateur'"
            :value="$stat->total"
            icon="fas fa-user"
            icon-tone="gray"
        />
    @endforeach
</div>

<x-list-filters-card action="{{ route('chef_commercial.souscriptions.corrigees') }}" :reset-url="route('chef_commercial.souscriptions.corrigees')">
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
            <th>Date de création</th>
            <th>Statut</th>
            <th>Correction</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($souscriptionsACorriger as $souscription)
        <tr>
            <td>{{ $souscription->operateur->name ?? 'Non défini' }}</td>
            <td>{{ $souscription->client->ref_client ?? 'N/A' }}</td>
            <td>{{ $souscription->client->nom_prenom ?? $souscription->nom_prenom ?? 'N/A' }}</td>
            <td>{{ $souscription->projet->nom ?? $souscription->programme }}</td>
            <td>{{ $souscription->created_at->format('d/m/Y') }}</td>
            <td>{{ ucfirst($souscription->statut ?? '') }}</td>
            <td>{{ ($souscription->statut_correction ?? 'pas_corrige') === 'corrige' ? 'Corrigée' : 'Non corrigée' }}</td>
            <td>
                <x-action-dropdown>
                    <li>
                        <a class="dropdown-item" href="{{ route('chef_commercial.souscriptions.edit', ['souscription' => $souscription->id]) }}">
                            <i class="fas fa-edit"></i> Corriger
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

<x-pagination :paginator="$souscriptionsACorriger" />
@endsection
