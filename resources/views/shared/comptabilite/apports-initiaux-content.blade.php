
<x-page-header title="Apports initiaux" />
<x-alert />

<div class="stats-grid mb-4">
    <x-stat-card label="Montant total" :value="number_format($apportsInitiaux->sum('montant'), 0, ',', ' ') . ' FCFA'" />
    <x-stat-card label="Montant payé" :value="number_format($apportsInitiaux->sum('montant_paye'), 0, ',', ' ') . ' FCFA'" />
    <x-stat-card label="Montant restant" :value="number_format($apportsInitiaux->sum('montant_reste'), 0, ',', ' ') . ' FCFA'" />
</div>

<x-filter-bar action="{{ route($indexRoute) }}">
    <div><label class="form-label">Recherche</label><input type="text" name="search" class="form-input" value="{{ request('search') }}"></div>
    <div><label class="form-label">Date début</label><input type="date" name="date_debut" class="form-input" value="{{ request('date_debut') }}"></div>
    <div><label class="form-label">Date fin</label><input type="date" name="date_fin" class="form-input" value="{{ request('date_fin') }}"></div>
</x-filter-bar>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Réf. client</th><th>Réf. souscription</th><th>Client</th><th>Projet</th>
            <th>Total</th><th>Payé</th><th>Restant</th><th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($apportsInitiaux as $paiement)
        @php
            $souscription = $paiement->souscription;
            $client = $souscription->client ?? null;
            $projet = $paiement->projet ?? ($souscription->projet ?? null);
            $montantRestant = (float) ($paiement->montant_reste ?? max(($paiement->montant ?? 0) - ($paiement->montant_paye ?? 0), 0));
        @endphp
        <tr>
            <td>{{ $client->ref_client ?? 'N/A' }}</td>
            <td>{{ $souscription->ref_souscription ?? 'N/A' }}</td>
            <td>{{ $client->nom_prenom ?? 'N/A' }}</td>
            <td>{{ $projet->nom ?? 'N/A' }}</td>
            <td>{{ number_format($paiement->montant ?? 0, 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format($paiement->montant_paye ?? 0, 0, ',', ' ') }} FCFA</td>
            <td>{{ number_format($montantRestant, 0, ',', ' ') }} FCFA</td>
            <td>
                <x-action-dropdown>
                    <li>
                        <a href="{{ route($paiementsRoute, ['souscription' => $souscription, 'type' => 'APPORT']) }}" class="dropdown-item">
                            <i class="fas fa-history"></i> Historique
                        </a>
                    </li>
                    @if($montantRestant > 0)
                        <li>
                            <a href="{{ route($paiementsRoute, ['souscription' => $souscription, 'type' => 'APPORT']) }}" class="dropdown-item">
                                <i class="fas fa-money-bill-wave"></i> Enregistrer un paiement
                            </a>
                        </li>
                    @endif
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr><td colspan="8"><x-empty-state title="Aucun apport initial" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$apportsInitiaux" />
