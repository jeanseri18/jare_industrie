@extends(request()->routeIs('comptable.*') ? 'layouts.comptable' : 'layouts.dg')

@section('title', 'Suivi paiements projet')

@section('content')
<x-page-header title="Suivi des paiements par projet" />
<x-alert />

<x-filter-bar action="{{ route(request()->routeIs('comptable.*') ? 'comptable.suivi-paiements-projet' : 'dg.suivi-paiements-projet') }}">
    <div><label class="form-label">Projet</label>
        <select name="projet" class="form-select">
            <option value="">Tous</option>
            @foreach($projets ?? [] as $projet)
                <option value="{{ $projet->id }}" @selected(request('projet') == $projet->id)>{{ $projet->nom }}</option>
            @endforeach
        </select>
    </div>
    <div><label class="form-label">Recherche</label><input type="text" name="search" class="form-input" value="{{ request('search') }}"></div>
</x-filter-bar>

@if(isset($souscriptions))
<x-data-table>
    <x-slot:head>
        <tr>
            <th>Réf.</th><th>Client</th><th>Projet</th><th>Total payé</th><th>Reste</th><th>Statut</th>
        </tr>
    </x-slot:head>
    @forelse($souscriptions as $s)
        <tr>
            <td>{{ $s->ref_souscription }}</td>
            <td>{{ $s->client->nom_prenom ?? 'N/A' }}</td>
            <td>{{ $s->projet->nom ?? 'N/A' }}</td>
            <td>{{ number_format($s->paiements->where('statut','payé')->sum('montant'), 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format(max(($s->valeur_souscription ?? 0) - $s->paiements->where('statut','payé')->sum('montant'), 0), 0, ',', ' ') }} FCFA</td>
            <td><x-status-badge :status="$s->statut" /></td>
        </tr>
    @empty
        <tr><td colspan="6"><x-empty-state /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$souscriptions" />
@endif
@endsection
