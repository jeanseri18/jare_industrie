@extends('layouts.comptable')

@section('title', 'Paiements de la Souscription ' . $souscription->numero_dossier)

@section('content')
<x-page-header :title="'Paiements de la souscription ' . $souscription->ref_souscription">
    <x-slot:actions>
        <a href="{{ route('comptable.souscriptions.etat-versements', $souscription) }}" target="_blank" class="btn-primary">
            <i class="fas fa-print me-1"></i> Imprimer l'état des versements
        </a>
    </x-slot:actions>
</x-page-header>

<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('comptable.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item"><a href="{{ route('comptable.suivi-paiements-projet') }}">Suivi des paiements projet</a></li>
        <li class="breadcrumb-item active">Paiements de la souscription</li>
    </ol>
</nav>

<x-alert />

<div class="data-table-container mb-4">
    <div class="list-body">
        <h3 class="text-base font-semibold text-slate-900 mb-3">Informations de la souscription</h3>
        <div class="row">
            <div class="col-md-3"><strong>Référence Client:</strong> {{ $souscription->client->ref_client ?? 'N/A' }}</div>
            <div class="col-md-3"><strong>Référence Souscription:</strong> {{ $souscription->ref_souscription }}</div>
            <div class="col-md-3"><strong>Client:</strong> {{ $souscription->client->nom_prenom ?? 'N/A' }}</div>
            <div class="col-md-3"><strong>Projet:</strong> {{ $souscription->projet->nom ?? 'N/A' }}</div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3"><strong>Prix du logement:</strong> {{ number_format($souscription->prix_logement, 0, ',', ' ') }} FCFA</div>
            <div class="col-md-3"><strong>Montant total payé:</strong> {{ number_format($paiements->where('statut', 'payé')->sum('montant'), 0, ',', ' ') }} FCFA</div>
            <div class="col-md-3"><strong>Montant restant:</strong> {{ number_format($souscription->prix_logement - $paiements->where('statut', 'payé')->sum('montant'), 0, ',', ' ') }} FCFA</div>
            <div class="col-md-3"><strong>Mode de paiement:</strong> {{ $souscription->mode_paiement ?? 'Non défini' }}</div>
        </div>
        <div class="row mt-3">
            <div class="col-md-3">
                <strong>Apport initial:</strong>
                @if(!empty($souscription->apport_initial_paye_par_client))
                    Oui
                @else
                    Non applicable
                @endif
            </div>
        </div>
    </div>
</div>

<x-list-filters-card action="{{ route('comptable.paiements.souscription', $souscription) }}" :reset-url="route('comptable.paiements.souscription', $souscription)">
    <div>
        <label for="search" class="form-label">Recherche</label>
        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Référence, type, montant...">
    </div>
    <div>
        <label for="type" class="form-label">Type de paiement</label>
        <select class="form-select" id="type" name="type">
            <option value="">Tous les types</option>
            <option value="FRAIS_DOSSIER" {{ request('type') == 'FRAIS_DOSSIER' ? 'selected' : '' }}>Frais de dossier</option>
            <option value="APPORT" {{ request('type') == 'APPORT' ? 'selected' : '' }}>Apport</option>
            <option value="PROJET" {{ request('type') == 'PROJET' ? 'selected' : '' }}>Projet</option>
        </select>
    </div>
    <div>
        <label for="statut" class="form-label">Statut</label>
        <select class="form-select" id="statut" name="statut">
            <option value="">Tous</option>
            <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En Attente</option>
            <option value="payé" {{ request('statut') == 'payé' ? 'selected' : '' }}>Payé</option>
            <option value="annulé" {{ request('statut') == 'annulé' ? 'selected' : '' }}>Annulé</option>
        </select>
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
            <th>Référence</th>
            <th>Type</th>
            <th>Montant</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Mode</th>
            <th>Comptable</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($paiements as $paiement)
    <tr>
        <td>{{ $paiement->reference }}</td>
        <td>
            @if($paiement->type == 'mensualite')
                <span class="badge badge-info">Mensualité</span>
            @elseif($paiement->type == 'acompte')
                <span class="badge badge-primary">Acompte</span>
            @elseif($paiement->type == 'FRAIS_DOSSIER')
                <span class="badge badge-secondary">Frais de dossier</span>
            @elseif($paiement->type == 'APPORT')
                <span class="badge badge-warning">Apport</span>
            @elseif($paiement->type == 'PROJET')
                <span class="badge badge-info">Projet</span>
            @else
                <span class="badge badge-secondary">{{ $paiement->type }}</span>
            @endif
        </td>
        <td>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
        <td>{{ $paiement->date_paiement->format('d/m/Y') }}</td>
        <td>
            @if($paiement->statut == 'en_attente')
                <span class="badge badge-warning">En Attente</span>
            @elseif($paiement->statut == 'payé')
                <span class="badge badge-success">Payé</span>
            @elseif($paiement->statut == 'annulé')
                <span class="badge badge-danger">Annulé</span>
            @endif
        </td>
        <td>{{ ucfirst($paiement->mode) }}</td>
        <td>{{ $paiement->comptable->name ?? 'Non assigné' }}</td>
        <td>
            <x-action-dropdown>
                @if(!empty($paiement->preuve_paiement))
                    <li>
                        <a href="{{ \Illuminate\Support\Facades\Storage::url($paiement->preuve_paiement) }}" target="_blank" rel="noopener" class="dropdown-item">
                            <i class="fas fa-paperclip"></i> Voir la preuve
                        </a>
                    </li>
                @endif
                @if($paiement->statut == 'payé')
                    <li>
                        <a href="{{ route('comptable.paiements.recu', $paiement) }}" target="_blank" class="dropdown-item">
                            <i class="fas fa-print"></i> Imprimer le reçu
                        </a>
                    </li>
                @endif
                @if($paiement->statut == 'en_attente')
                    <li>
                        <form action="{{ route('comptable.paiements.valider', $paiement) }}" method="POST" class="action-dropdown-form">
                            @csrf
                            <button type="submit" class="dropdown-item"><i class="fas fa-check"></i> Valider</button>
                        </form>
                    </li>
                    <li>
                        <form action="{{ route('comptable.paiements.annuler', $paiement) }}" method="POST" class="action-dropdown-form">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-times"></i> Annuler</button>
                        </form>
                    </li>
                @endif
            </x-action-dropdown>
        </td>
    </tr>
    @empty
    <tr><td colspan="8" class="text-center">Aucun paiement trouvé pour cette souscription</td></tr>
    @endforelse
</x-data-table>

<x-pagination :paginator="$paiements" />
@endsection
