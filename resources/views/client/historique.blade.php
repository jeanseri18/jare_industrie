@extends('layouts.client')

@section('title', 'Historique de paiement')

@section('content')
<x-page-header title="Historique de paiement" subtitle="Consultez l'historique complet de tous vos paiements" />
<x-alert />

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Type de paiement</th>
            <th>Montant</th>
            <th>Date</th>
            <th>Statut</th>
        </tr>
    </x-slot:head>
    @forelse($paiements as $paiement)
        <tr>
            <td>
                @if($paiement->type == 'frais_dossier')
                    Frais de dossier
                @elseif($paiement->type == 'apport_initial')
                    Apport initial
                @elseif($paiement->type == 'paiement_projet')
                    Paiement projet
                @else
                    {{ ucfirst(str_replace('_', ' ', $paiement->type)) }}
                @endif
            </td>
            <td><strong>{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong></td>
            <td>{{ $paiement->date_paiement ? $paiement->date_paiement->format('d/m/Y') : 'N/A' }}</td>
            <td>
                @if(in_array(strtolower($paiement->statut), ['soldé', 'solde', 'payé']))
                    <span class="status-badge status-valide">Soldé</span>
                @elseif(in_array(strtolower($paiement->statut), ['en attente', 'en_attente']))
                    <span class="status-badge status-attente">En attente</span>
                @else
                    <span class="status-badge status-corriger">{{ $paiement->statut }}</span>
                @endif
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="4">
                <x-empty-state title="Aucun paiement" message="Vous n'avez pas encore effectué de paiement." />
            </td>
        </tr>
    @endforelse
</x-data-table>

<div class="mt-3">
    <x-pagination :paginator="$paiements" />
</div>
@endsection
