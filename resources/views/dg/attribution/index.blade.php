@extends('layouts.dg')

@section('title', 'Attribution de logement')

@section('content')
<x-page-header title="Attribution de logement">
    <x-slot:actions>
        <a href="{{ route('dg.confirmation.index') }}" class="btn-secondary">
            <i class="fas fa-check me-1"></i> Confirmer un dossier soldé
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

@if(session('download_attestation_url'))
    <div class="alert alert-success d-flex align-items-center justify-content-between mb-4" role="alert">
        <div><i class="fas fa-file-pdf me-2"></i> Attestation de réservation prête.</div>
        <a href="{{ session('download_attestation_url') }}" class="btn-primary btn-sm" target="_blank" rel="noopener">Ouvrir</a>
    </div>
@endif

<x-list-filters-card action="{{ route('dg.attribution.index') }}" :reset-url="route('dg.attribution.index')">
    <div>
        <label class="form-label">Nom client (ou réf. client)</label>
        <input type="text" name="client" class="form-control" placeholder="Nom ou référence..." value="{{ request('client') }}">
    </div>
    <div>
        <label class="form-label">Date</label>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
    </div>
    <div>
        <label class="form-label">Statut</label>
        <select name="statut" class="form-select">
            <option value="">Tous</option>
            <option value="non_attribue" {{ request('statut') === 'non_attribue' ? 'selected' : '' }}>Non attribué</option>
            <option value="attribue" {{ request('statut') === 'attribue' ? 'selected' : '' }}>Attribué</option>
        </select>
    </div>
</x-list-filters-card>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Client</th>
            <th>Projet</th>
            <th>Type logement</th>
            <th>Date validation</th>
            <th>Statut attribution</th>
            <th>Action</th>
        </tr>
    </x-slot:head>
    @forelse($souscriptions as $s)
        @php $estAttribue = $s->attributionLot !== null; @endphp
        <tr>
            <td>{{ optional($s->client)->nom_prenom }}</td>
            <td>{{ optional($s->projet)->nom }}</td>
            <td>{{ $s->type_logement }}</td>
            <td>{{ optional($s->date_fin)->format('d/m/y') }}</td>
            <td>
                <span class="status-badge {{ $estAttribue ? 'status-valide' : 'status-attente' }}">
                    {{ $estAttribue ? 'Attribué' : 'Non attribué' }}
                </span>
            </td>
            <td>
                @if($estAttribue)
                    <x-action-dropdown>
                        <li><a href="{{ route('dg.souscriptions.show', $s) }}" class="dropdown-item"><i class="fas fa-eye"></i> Voir</a></li>
                        <li><a href="{{ route('dg.souscriptions.attestation', $s) }}" class="dropdown-item" target="_blank"><i class="fas fa-file-pdf"></i> Attestation PDF</a></li>
                    </x-action-dropdown>
                @else
                    <x-action-dropdown>
                        <li><a href="{{ route('dg.attribution.show', $s) }}" class="dropdown-item"><i class="fas fa-key"></i> Attribuer</a></li>
                    </x-action-dropdown>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="6"><x-empty-state title="Aucune souscription en attente d'attribution" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$souscriptions" />
@endsection
