@extends('layouts.dg')

@section('title', 'Paiements — ' . $souscription->ref_souscription)

@section('content')
@php
    $pourcentage = $duGlobal > 0 ? round(($totalPaye / $duGlobal) * 100, 1) : 0;
    $hasFilters = request()->hasAny(['search', 'type', 'statut', 'date_debut', 'date_fin']);
@endphp

<x-page-header :title="'Paiements — ' . $souscription->ref_souscription" :subtitle="($souscription->client->nom_prenom ?? 'Client') . ' · ' . ($souscription->projet->nom ?? 'Projet')">
    <x-slot:actions>
        <a href="{{ route('dg.souscriptions.show', $souscription) }}" class="btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Fiche souscription
        </a>
        <a href="{{ route('dg.souscriptions.etat-versements', $souscription) }}" target="_blank" class="btn-primary">
            <i class="fas fa-print me-1"></i> État des versements
        </a>
    </x-slot:actions>
</x-page-header>

<x-alert />

<div class="stats-grid mb-4">
    <x-stat-card
        label="Montant total dû"
        :value="number_format($duGlobal, 0, ',', ' ') . ' FCFA'"
        icon="fas fa-coins"
        icon-tone="black"
        footer="Logement + frais"
    />
    <x-stat-card
        label="Montant encaissé"
        :value="number_format($totalPaye, 0, ',', ' ') . ' FCFA'"
        icon="fas fa-money-bill-wave"
        icon-tone="gray"
        footer="Paiements validés"
    />
    <x-stat-card
        label="Montant restant"
        :value="number_format($resteGlobal, 0, ',', ' ') . ' FCFA'"
        icon="fas fa-hourglass-half"
        icon-tone="red"
        footer="Solde à recouvrer"
    />
    <div class="stat-card stat-card--progress">
        <div class="stat-header">
            <span class="stat-title">Progression</span>
            <div class="stat-icon icon-gray">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="stat-value">{{ $pourcentage }}%</div>
        <div class="progress">
            <div class="progress-bar bg-secondary" role="progressbar" data-width="{{ min($pourcentage, 100) }}"></div>
        </div>
        <div class="stat-footer">Taux de recouvrement</div>
    </div>
</div>

<x-detail-section title="Informations de la souscription" collapsible id="infoSouscriptionPaiements" icon="fas fa-file-invoice">
    <div class="row g-3">
        <div class="col-md-6 col-lg-3">
            <x-detail-field label="Référence client">
                <span class="detail-field__value--mono">{{ $souscription->client->ref_client ?? 'N/A' }}</span>
            </x-detail-field>
        </div>
        <div class="col-md-6 col-lg-3">
            <x-detail-field label="Référence souscription">
                <span class="detail-field__value--mono">{{ $souscription->ref_souscription }}</span>
            </x-detail-field>
        </div>
        <div class="col-md-6 col-lg-3">
            <x-detail-field label="Client">{{ $souscription->client->nom_prenom ?? 'N/A' }}</x-detail-field>
        </div>
        <div class="col-md-6 col-lg-3">
            <x-detail-field label="Projet">{{ $souscription->projet->nom ?? 'N/A' }}</x-detail-field>
        </div>
        <div class="col-md-6 col-lg-3">
            <x-detail-field label="Prix du logement">{{ number_format($prixLogement, 0, ',', ' ') }} FCFA</x-detail-field>
        </div>
        <div class="col-md-6 col-lg-3">
            <x-detail-field label="Frais de dossier">{{ number_format($fraisAttendu, 0, ',', ' ') }} FCFA</x-detail-field>
        </div>
        <div class="col-md-6 col-lg-3">
            <x-detail-field label="Mode de paiement">{{ $souscription->mode_paiement ?? 'Non défini' }}</x-detail-field>
        </div>
        <div class="col-md-6 col-lg-3">
            <x-detail-field label="Statut dossier">
                <span class="badge-custom badge-info">{{ strtoupper($souscription->statut ?? 'En cours') }}</span>
            </x-detail-field>
        </div>
    </div>
</x-detail-section>

<x-list-filters-card action="{{ route('dg.souscriptions.paiements', $souscription) }}" :reset-url="route('dg.souscriptions.paiements', $souscription)">
    <div>
        <label for="search" class="form-label">Recherche</label>
        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Référence, montant…">
    </div>
    <div>
        <label for="type" class="form-label">Type</label>
        <select class="form-select" id="type" name="type">
            <option value="">Tous</option>
            <option value="FRAIS_DOSSIER" @selected(request('type') === 'FRAIS_DOSSIER')>Frais de dossier</option>
            <option value="APPORT" @selected(request('type') === 'APPORT')>Apport initial</option>
            <option value="PROJET" @selected(request('type') === 'PROJET')>Échéance projet</option>
        </select>
    </div>
    <div>
        <label for="statut" class="form-label">Statut</label>
        <select class="form-select" id="statut" name="statut">
            <option value="">Tous</option>
            <option value="en_attente" @selected(request('statut') === 'en_attente')>En attente</option>
            <option value="payé" @selected(request('statut') === 'payé')>Payé</option>
            <option value="annulé" @selected(request('statut') === 'annulé')>Annulé</option>
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

@if($hasFilters)
    <p class="text-muted small mb-3">
        <i class="fas fa-filter me-1"></i> Filtres actifs — {{ $paiements->total() }} résultat(s)
    </p>
@endif

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
            <th class="text-end">Actions</th>
        </tr>
    </x-slot:head>
    @forelse($paiements as $paiement)
        <tr>
            <td><span class="detail-field__value--mono">{{ $paiement->reference }}</span></td>
            <td>
                @if($paiement->type === 'FRAIS_DOSSIER')
                    <span class="badge-custom badge-warning">Frais de dossier</span>
                @elseif($paiement->type === 'APPORT')
                    <span class="badge-custom badge-success">Apport initial</span>
                @elseif($paiement->type === 'PROJET')
                    <span class="badge-custom badge-info">Échéance projet</span>
                @else
                    <span class="badge-custom badge-secondary">{{ $paiement->type }}</span>
                @endif
            </td>
            <td><strong>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong></td>
            <td>{{ $paiement->date_paiement?->format('d/m/Y') ?? '—' }}</td>
            <td>
                @if($paiement->statut === 'en_attente')
                    <span class="status-badge status-attente">En attente</span>
                @elseif($paiement->statut === 'payé')
                    <span class="status-badge status-valide">Payé</span>
                @elseif($paiement->statut === 'annulé')
                    <span class="status-badge status-corriger">Annulé</span>
                @else
                    <span class="status-badge status-attente">{{ $paiement->statut }}</span>
                @endif
            </td>
            <td>{{ str_replace('_', ' ', ucfirst(strtolower($paiement->mode ?? ''))) }}</td>
            <td>{{ $paiement->comptable->name ?? '—' }}</td>
            <td class="text-end">
                <x-action-dropdown>
                    @if(!empty($paiement->preuve_paiement))
                        <li>
                            <a href="{{ \Illuminate\Support\Facades\Storage::url($paiement->preuve_paiement) }}" target="_blank" rel="noopener" class="dropdown-item">
                                <i class="fas fa-paperclip"></i> Voir la preuve
                            </a>
                        </li>
                    @endif
                    @if($paiement->statut === 'payé')
                        <li>
                            <a href="{{ route('dg.paiements.recu', $paiement) }}" target="_blank" class="dropdown-item">
                                <i class="fas fa-print"></i> Imprimer le reçu
                            </a>
                        </li>
                    @endif
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="8">
                <x-empty-state
                    :title="$hasFilters ? 'Aucun paiement ne correspond aux filtres' : 'Aucun paiement enregistré'"
                    :message="$hasFilters ? 'Modifiez ou réinitialisez les filtres pour élargir la recherche.' : 'Les paiements enregistrés par la comptabilité apparaîtront ici.'"
                />
            </td>
        </tr>
    @endforelse
</x-data-table>

@if($paiements->hasPages())
    <x-pagination :paginator="$paiements" />
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.progress-bar[data-width]').forEach(function (el) {
            const v = el.getAttribute('data-width');
            if (v !== null && v !== '') {
                el.style.width = String(v) + '%';
            }
        });
    });
</script>
@endpush
