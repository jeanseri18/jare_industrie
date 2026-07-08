@extends('layouts.dg')

@section('title', 'Confirmation de dossier soldé')

@section('content')
<x-page-header title="Confirmation de dossier soldé">
    <x-slot:actions>
        <a href="{{ route('dg.attribution.index') }}" class="btn-secondary">
            <i class="fas fa-key me-1"></i> Attribuer un logement
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

@if(session('download_lettre_url'))
    <div class="alert alert-success d-flex align-items-center justify-content-between mb-4" role="alert">
        <div><i class="fas fa-file-pdf me-2"></i> Lettre définitive prête.</div>
        <a href="{{ session('download_lettre_url') }}" class="btn-primary btn-sm" target="_blank" rel="noopener">Ouvrir</a>
    </div>
@endif

<x-list-filters-card action="{{ route('dg.confirmation.index') }}" :reset-url="route('dg.confirmation.index')">
    <div>
        <label class="form-label">Nom client (ou réf. client)</label>
        <input type="text" name="client" class="form-control" placeholder="Nom ou référence..." value="{{ request('client') }}">
    </div>
    <div>
        <label class="form-label">Date (dernier paiement)</label>
        <input type="date" name="date" class="form-control" value="{{ request('date') }}">
    </div>
    <div>
        <label class="form-label">Statut</label>
        <select name="statut" class="form-select">
            <option value="">Tous</option>
            <option value="a_confirmer" {{ request('statut') === 'a_confirmer' ? 'selected' : '' }}>À confirmer</option>
            <option value="lettre_generee" {{ request('statut') === 'lettre_generee' ? 'selected' : '' }}>Lettre générée</option>
        </select>
    </div>
</x-list-filters-card>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Client</th>
            <th>Projet</th>
            <th>Montant total</th>
            <th>Statut</th>
            <th>Date dernier paiement</th>
            <th>Action</th>
        </tr>
    </x-slot:head>
    @forelse($souscriptions as $s)
        @php $vf = \App\Models\ValidationFinale::where('idsouscription', $s->id)->first(); @endphp
        <tr>
            <td>{{ optional($s->client)->nom_prenom }}</td>
            <td>{{ optional($s->projet)->nom }}</td>
            <td>{{ number_format($s->montant_total ?? (($s->prix_logement ?? 0) + \App\Models\FraisDossier::where('id_souscription', $s->id)->sum('montant')), 0, ',', ' ') }} FCFA</td>
            <td><span class="status-badge status-valide">Soldé</span></td>
            <td>{{ optional($s->dernier_paiement?->date_paiement)->format('d/m/y') }}</td>
            <td>
                <x-action-dropdown>
                    @if(!$s->attributionLot)
                        <li><a href="{{ route('dg.attribution.show', $s) }}" class="dropdown-item"><i class="fas fa-key"></i> Attribuer</a></li>
                    @elseif($vf)
                        <li><a href="{{ route('dg.souscriptions.lettre-definitive', $s) }}" class="dropdown-item" target="_blank"><i class="fas fa-file-download"></i> Générer la lettre</a></li>
                    @else
                        <li><a href="{{ route('dg.souscriptions.confirmation', $s) }}" class="dropdown-item"><i class="fas fa-check"></i> Confirmer</a></li>
                    @endif
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr><td colspan="6"><x-empty-state title="Aucun dossier soldé en attente" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$souscriptions" />
@endsection
