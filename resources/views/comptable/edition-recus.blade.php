@extends('layouts.comptable')

@section('title', 'Édition de Reçus')

@section('content')
<x-page-header title="Édition de Reçus" />
<x-alert />

<x-list-filters-card action="{{ route('comptable.edition-recus') }}" :reset-url="route('comptable.edition-recus')">
    <div>
        <label for="search" class="form-label">Client</label>
        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Nom, Prénom, Référence client...">
    </div>
    <div>
        <label for="date_debut" class="form-label">Date début</label>
        <input type="date" class="form-control" id="date_debut" name="date_debut" value="{{ request('date_debut') }}">
    </div>
    <div>
        <label for="date_fin" class="form-label">Date fin</label>
        <input type="date" class="form-control" id="date_fin" name="date_fin" value="{{ request('date_fin') }}">
    </div>
</x-list-filters-card>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Référence Paiement</th>
            <th>Date</th>
            <th>Client</th>
            <th>Projet</th>
            <th>Type</th>
            <th>Montant</th>
            <th>Mode</th>
            <th>Action</th>
        </tr>
    </x-slot:head>
    @forelse($paiements as $paiement)
    <tr>
        <td>{{ $paiement->reference }}</td>
        <td>{{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : 'N/A' }}</td>
        <td>{{ $paiement->souscription->client->nom_prenom ?? 'N/A' }} <br> <small class="text-muted">{{ $paiement->souscription->client->ref_client ?? '' }}</small></td>
        <td>{{ $paiement->souscription->projet->nom ?? 'N/A' }}</td>
        <td>
            @if($paiement->type == 'FRAIS_DOSSIER')
                <span class="badge-custom badge-warning">Frais Dossier</span>
            @elseif($paiement->type == 'APPORT')
                <span class="badge-custom badge-success">Apport</span>
            @elseif($paiement->type == 'PROJET')
                <span class="badge-custom badge-primary">Projet</span>
            @else
                <span class="badge-custom badge-info">{{ ucfirst(str_replace('_', ' ', $paiement->type)) }}</span>
            @endif
        </td>
        <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
        <td>{{ ucfirst(str_replace('_', ' ', $paiement->mode)) }}</td>
        <td>
            <x-action-dropdown>
                <li>
                    <a href="{{ route('comptable.paiements.recu', $paiement) }}" target="_blank" class="dropdown-item">
                        <i class="fas fa-print"></i> Imprimer le reçu
                    </a>
                </li>
            </x-action-dropdown>
        </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center">Aucun paiement trouvé. Veuillez utiliser les filtres.</td></tr>
    @endforelse
</x-data-table>

<x-pagination :paginator="$paiements" />
@endsection
