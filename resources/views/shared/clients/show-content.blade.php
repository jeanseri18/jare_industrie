@php
    $souscriptionCount = $client->souscriptions->count();
@endphp

<div class="data-table-container show-hero">
    <div class="list-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h2 class="show-hero__title mb-1">{{ $client->nom_prenom }}</h2>
                <div class="show-hero__meta">
                    <span>Catégorie : {{ ucfirst($client->categorie_client ?? 'Non définie') }}</span>
                    <span>Référence : {{ $client->ref_client ?? 'Non définie' }}</span>
                    <span>Membre depuis {{ $client->created_at ? \Carbon\Carbon::parse($client->created_at)->format('d/m/Y') : '-' }}</span>
                </div>
            </div>
            @if($showEditButton ?? true)
                <a href="{{ $editRoute }}" class="btn-secondary">
                    <i class="fas fa-edit me-1"></i> Modifier
                </a>
            @endif
        </div>
    </div>
</div>

<div class="stats-grid">
    <x-stat-card label="Souscriptions" :value="$souscriptionCount" icon="fas fa-file-contract" icon-tone="black" footer="Total actif" />
    <x-stat-card label="Salaire mensuel" :value="number_format($client->salaire_mensuel ?? 0, 0, ',', ' ') . ' FCFA'" icon="fas fa-money-bill-wave" icon-tone="gray" />
    <x-stat-card label="Enfants" :value="$client->nombre_enfants ?? 0" icon="fas fa-child" icon-tone="gray" footer="Personnes à charge" />
    <x-stat-card label="Statut" :value="ucfirst($client->situation_matrimoniale ?? 'N/A')" icon="fas fa-user" icon-tone="black" />
</div>

<div class="show-detail-grid">
    <x-detail-section title="Informations personnelles" collapsible id="infoPerso">
        <x-detail-field label="Nom et prénom">{{ $client->nom_prenom }}</x-detail-field>
        <x-detail-field label="Date de naissance">
            {{ $client->date_naissance ? \Carbon\Carbon::parse($client->date_naissance)->format('d/m/Y') : 'Non définie' }}
        </x-detail-field>
        <x-detail-field label="Lieu de naissance">{{ $client->lieu_naissance ?? 'Non défini' }}</x-detail-field>
        <x-detail-field label="Nationalité">{{ $client->nationalite ?? 'Non définie' }}</x-detail-field>
        <x-detail-field label="Situation matrimoniale">{{ ucfirst($client->situation_matrimoniale ?? 'Non définie') }}</x-detail-field>
        <x-detail-field label="Nombre d'enfants">{{ $client->nombre_enfants ?? 0 }}</x-detail-field>
        <x-detail-field label="Ayant droit">{{ $client->ayant_droit ?? 'Non défini' }}</x-detail-field>
    </x-detail-section>

    <x-detail-section title="Informations de contact" collapsible id="infoContact">
        <x-detail-field label="Téléphone">
            @if($client->telephone)
                <a href="tel:{{ str_replace(' ', '', $client->telephone) }}" class="text-decoration-none">{{ $client->telephone }}</a>
            @else
                Non défini
            @endif
        </x-detail-field>
        <x-detail-field label="Email">
            @if($client->email)
                <a href="mailto:{{ $client->email }}" class="text-decoration-none">{{ $client->email }}</a>
            @else
                Non défini
            @endif
        </x-detail-field>
        <x-detail-field label="Salaire mensuel">{{ number_format($client->salaire_mensuel ?? 0, 0, ',', ' ') }} FCFA</x-detail-field>
        <x-detail-field label="Catégorie client">
            <span class="badge-custom badge-info">{{ ucfirst($client->categorie_client ?? 'Non définie') }}</span>
        </x-detail-field>
        <x-detail-field label="Mutuelle">{{ optional($client->mutuelle)->nom ?? 'Aucune' }}</x-detail-field>
        <x-detail-field label="Référence client">
            <span class="detail-field__value--mono">{{ $client->ref_client ?? 'Non définie' }}</span>
        </x-detail-field>
    </x-detail-section>
</div>

<x-detail-section title="Souscriptions" collapsible id="infoSouscriptions" class="mb-0">
    @if($souscriptionCount > 0)
        <x-data-table embedded>
            <x-slot:head>
                <tr>
                    <th>Référence</th>
                    <th>Programme</th>
                    <th>Type logement</th>
                    <th>Coût total</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </x-slot:head>
            @foreach($client->souscriptions as $souscription)
                <tr>
                    <td><strong>{{ $souscription->ref_souscription ?? 'Non définie' }}</strong></td>
                    <td>{{ $souscription->projet->nom ?? $souscription->nom_programme ?? $souscription->programme ?? 'Non défini' }}</td>
                    <td>{{ ucfirst($souscription->type_logement ?? 'Non défini') }}</td>
                    <td><strong>{{ number_format($souscription->prix_logement ?? $souscription->valeur_souscription ?? 0, 0, ',', ' ') }} FCFA</strong></td>
                    <td>
                        @if($souscription->statut == 'SOLD')
                            <span class="badge-custom badge-success">Soldé</span>
                        @elseif($souscription->statut == 'annulee')
                            <span class="badge-custom badge-danger">Annulée</span>
                        @else
                            <span class="badge-custom badge-warning">{{ ucfirst($souscription->statut ?? 'En cours') }}</span>
                        @endif
                    </td>
                    <td>{{ $souscription->created_at ? \Carbon\Carbon::parse($souscription->created_at)->format('d/m/Y') : 'Non définie' }}</td>
                    <td>
                        <x-action-dropdown>
                            <li>
                                <a href="{{ $souscriptionAction($souscription) }}" class="dropdown-item">
                                    <i class="fas fa-money-bill-wave"></i> {{ $souscriptionActionLabel }}
                                </a>
                            </li>
                        </x-action-dropdown>
                    </td>
                </tr>
            @endforeach
        </x-data-table>
    @else
        <div class="app-alert border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-800">
            Aucune souscription trouvée pour ce client.
        </div>
    @endif
</x-detail-section>
