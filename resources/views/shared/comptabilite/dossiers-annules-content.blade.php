
<x-page-header title="Dossiers annulés" />
<x-alert />

@php
    $totalPayeAnnules = $dossiersAnnules->sum(fn ($d) => $d->paiements->where('statut', 'payé')->sum('montant'));
    $totalRembourseAnnules = $dossiersAnnules->sum(fn ($d) => $d->paiements->where('statut', 'rembourse')->sum('montant'));
@endphp

<div class="stats-grid mb-4">
    <x-stat-card label="Dossiers annulés" :value="$dossiersAnnules->total()" />
    <x-stat-card label="Montant payé" :value="number_format($totalPayeAnnules, 0, ',', ' ') . ' FCFA'" />
    <x-stat-card label="Montant remboursé" :value="number_format($totalRembourseAnnules, 0, ',', ' ') . ' FCFA'" />
</div>

<x-filter-bar action="{{ route($indexRoute) }}">
    <div class="md:col-span-4"><label class="form-label">Recherche</label><input type="text" name="search" class="form-input" value="{{ request('search') }}" placeholder="Client, référence..."></div>
</x-filter-bar>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Référence</th><th>Client</th><th>Projet</th><th>Payé</th><th>Remboursé</th><th>Reste</th><th>Action</th>
        </tr>
    </x-slot:head>
    @forelse($dossiersAnnules as $dossier)
        @php
            $totalPaye = $dossier->paiements->where('statut', 'payé')->sum('montant');
            $totalRembourse = $dossier->paiements->where('statut', 'rembourse')->sum('montant');
            $reste = $totalPaye - $totalRembourse;
        @endphp
        <tr>
            <td>{{ $dossier->ref_souscription }}</td>
            <td>{{ optional($dossier->client)->nom_prenom }}</td>
            <td>{{ optional($dossier->projet)->nom }}</td>
            <td>{{ number_format($totalPaye, 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format($totalRembourse, 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format($reste, 0, ',', ' ') }} FCFA</td>
            <td>
                @if($reste > 0)
                    <form action="{{ route($rembourserRoute, $dossier) }}" method="POST" class="card max-w-sm space-y-2">
                        @csrf
                        <input type="number" name="montant" class="form-input" max="{{ $reste }}" required placeholder="Montant">
                        <select name="mode" class="form-select" required>
                            <option value="ESPECES">Espèces</option>
                            <option value="VIREMENT">Virement</option>
                            <option value="CHEQUE">Chèque</option>
                        </select>
                        <textarea name="motif" class="form-input" rows="2" placeholder="Motif"></textarea>
                        <button class="btn-primary">Rembourser</button>
                    </form>
                @else
                    <x-status-badge status="sold" />
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="7"><x-empty-state title="Aucun dossier annulé" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$dossiersAnnules" />
