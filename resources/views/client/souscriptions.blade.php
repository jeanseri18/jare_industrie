@extends('layouts.client')

@section('title', 'Mes Souscriptions')

@section('content')
<x-page-header title="Mes souscriptions" subtitle="Suivez l'état de vos dossiers immobiliers" />
<x-alert />

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Référence</th>
            <th>Projet</th>
            <th>Type logement</th>
            <th>Avancement</th>
            <th>Date</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($souscriptions as $s)
        @php
            $totalPaye = $s->paiements->where('statut', 'payé')->sum('montant');
            $pourcentage = $s->prix_logement > 0 ? round(($totalPaye / $s->prix_logement) * 100) : 0;
        @endphp
        <tr>
            <td><strong>{{ $s->ref_souscription }}</strong></td>
            <td>{{ $s->projet->nom ?? '—' }}</td>
            <td>{{ $s->type_logement }}</td>
            <td style="min-width: 140px;">
                <div class="d-flex justify-content-between small mb-1">
                    <span>{{ $pourcentage }}%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar progress-red" style="width: {{ $pourcentage }}%"></div>
                </div>
            </td>
            <td>{{ $s->created_at->format('d/m/Y') }}</td>
            <td>
                @if($s->statut == 'SOLD')
                    <span class="status-badge status-valide">Soldé</span>
                @elseif($s->statut == 'annulee')
                    <span class="status-badge status-corriger">Annulée</span>
                @else
                    <span class="status-badge status-attente">En cours</span>
                @endif
            </td>
            <td>
                <a href="{{ route('client.historique') }}" class="btn-secondary btn-sm">
                    <i class="fas fa-history me-1"></i> Paiements
                </a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7">
                <x-empty-state title="Aucune souscription" message="Vous n'avez pas encore de souscription enregistrée." />
            </td>
        </tr>
    @endforelse
</x-data-table>

<div class="mt-3">
    <x-pagination :paginator="$souscriptions" />
</div>
@endsection
