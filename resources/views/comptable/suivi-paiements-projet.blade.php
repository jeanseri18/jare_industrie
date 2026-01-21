@extends('layouts.comptable')

@section('title', 'Suivi des Paiements Projet')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title">Suivi des Paiements Projet</h2>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('comptable.suivi-paiements-projet') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Référence client, nom client, projet...">
                        </div>
                        <div class="col-md-3">
                            <label for="projet" class="form-label">Projet</label>
                            <select class="form-select" id="projet" name="projet">
                                <option value="">Tous les projets</option>
                                @foreach($projets as $projet)
                                    <option value="{{ $projet->id }}" {{ request('projet') == $projet->id ? 'selected' : '' }}>{{ $projet->nom }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="mode_paiement" class="form-label">Mode de paiement</label>
                            <select class="form-select" id="mode_paiement" name="mode_paiement">
                                <option value="">Tous</option>
                                <option value="comptant" {{ request('mode_paiement') == 'comptant' ? 'selected' : '' }}>Comptant</option>
                                <option value="mensuel" {{ request('mode_paiement') == 'mensuel' ? 'selected' : '' }}>Mensuel</option>
                                <option value="trimestriel" {{ request('mode_paiement') == 'trimestriel' ? 'selected' : '' }}>Trimestriel</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="date_debut" class="form-label">Date début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                   value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-2">
                            <label for="date_fin" class="form-label">Date fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                   value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                            <a href="{{ route('comptable.suivi-paiements-projet') }}" class="btn btn-secondary">
                                <i class="fas fa-undo"></i> Réinitialiser
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total En Attente</span>
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($souscriptions->sum(function($souscription) { return $souscription->paiements->where('statut', 'en_attente')->sum('montant'); }), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Payé</span>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($souscriptions->sum(function($souscription) { return $souscription->paiements->where('statut', 'payé')->sum('montant'); }), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Annulé</span>
                    <div class="stat-icon icon-red">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($souscriptions->sum(function($souscription) { return $souscription->paiements->where('statut', 'annulé')->sum('montant'); }), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Total Général</span>
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($souscriptions->sum(function($souscription) { return $souscription->paiements->sum('montant'); }), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>

    <!-- Table des souscriptions -->
    <div class="row">
        <div class="col-12">
            <div class="data-table-container">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title-custom">
                            <i class="fas fa-list"></i>
                            Liste des Souscriptions
                        </h5>    </div><div>
                        <div class="btn-group" role="group" aria-label="Filtre Statut">
                            <a class="btn {{ request('statut') === 'en_attente' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('comptable.suivi-paiements-projet', array_filter(['search' => request('search'), 'projet' => request('projet'), 'mode_paiement' => request('mode_paiement'), 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin'), 'statut' => 'en_attente'])) }}">En attente</a>
                            <a class="btn {{ request('statut') === 'en_cours' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('comptable.suivi-paiements-projet', array_filter(['search' => request('search'), 'projet' => request('projet'), 'mode_paiement' => request('mode_paiement'), 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin'), 'statut' => 'en_cours'])) }}">En cours</a>
                            <a class="btn {{ in_array(request('statut'), ['regle','payé']) ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('comptable.suivi-paiements-projet', array_filter(['search' => request('search'), 'projet' => request('projet'), 'mode_paiement' => request('mode_paiement'), 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin'), 'statut' => 'regle'])) }}">Réglé</a>
                            <a class="btn {{ in_array(request('statut'), ['annule','annulé']) ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('comptable.suivi-paiements-projet', array_filter(['search' => request('search'), 'projet' => request('projet'), 'mode_paiement' => request('mode_paiement'), 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin'), 'statut' => 'annule'])) }}">Annulé</a>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table-custom">
                        <thead>
                            <tr>
                                <th>Référence Client</th>
                                <th>Référence Souscription</th>
                                <th>Nom du Client</th>
                                <th>Projet</th>
                                <th>Prix Logement</th>
                                <th>Montant Payé</th>
                                <th>Montant Restant</th>
                                <th>Moyen de Paiement</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($souscriptions as $souscription)
                            @php
                                $totalPaye = $souscription->paiements->where('statut', 'payé')->sum('montant');
                                $montantRestant = $souscription->prix_logement - $totalPaye;
                                $dernierPaiement = $souscription->paiements->sortByDesc('date_paiement')->first();
                            @endphp
                            <tr>
                                <td>{{ $souscription->client->ref_client ?? 'N/A' }}</td>
                                <td>{{ $souscription->ref_souscription }}</td>
                                <td>{{ $souscription->client->nom_prenom ?? 'N/A' }}</td>
                                <td>{{ $souscription->projet->nom ?? 'N/A' }}</td>
                                <td>{{ number_format($souscription->prix_logement, 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($totalPaye, 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($montantRestant, 0, ',', ' ') }} FCFA</td>
                                <td>{{ $souscription->mode_paiement ?? 'Non défini' }}</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#paiementModal{{ $souscription->id }}" title="Effectuer un paiement">
                                            <i class="fas fa-money-bill-wave"></i> Payer
                                        </button>
                                        <a href="{{ route('comptable.paiements.souscription', $souscription) }}" class="btn btn-sm btn-info" title="Voir les paiements">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center">Aucune souscription trouvée</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $souscriptions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour les paiements -->
@foreach($souscriptions as $souscription)
<div class="modal fade" id="paiementModal{{ $souscription->id }}" tabindex="-1" role="dialog" aria-labelledby="paiementModalLabel{{ $souscription->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paiementModalLabel{{ $souscription->id }}">Effectuer un paiement - {{ $souscription->ref_souscription }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('comptable.paiements.create', $souscription) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="type_paiement{{ $souscription->id }}">Type de paiement</label>
                        <select class="form-control" id="type_paiement{{ $souscription->id }}" name="type" required>
                            <option value="">Sélectionner le type</option>
                            <option value="FRAIS_DOSSIER">Frais de dossier</option>
                            <option value="APPORT">Apport</option>
                            <option value="PROJET">Projet</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="montant{{ $souscription->id }}">Montant</label>
                        <input type="number" class="form-control" id="montant{{ $souscription->id }}" name="montant" min="1" max="{{ $montantRestant }}" required>
                    </div>
                    <div class="form-group">
                        <label for="mode{{ $souscription->id }}">Mode de paiement</label>
                        <select class="form-control" id="mode{{ $souscription->id }}" name="mode" required>
                            <option value="">Sélectionner le mode</option>
                            <option value="ESPECES">ESPECES</option>
                            <option value="VIREMENT">VIREMENT</option>
                            <option value="MOBILE_MONEY">MOBILE_MONEY</option>
                            <option value="TEMPERAMENT">TEMPERAMENT</option>
                            <option value="CREDIT_BANCAIRE">CREDIT_BANCAIRE</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date_paiement{{ $souscription->id }}">Date de paiement</label>
                        <input type="date" class="form-control" id="date_paiement{{ $souscription->id }}" name="date_paiement" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label for="preuve_paiement{{ $souscription->id }}">Preuve du paiement</label>
                        <div class="upload-box" onclick="document.getElementById('preuve_paiement{{ $souscription->id }}').click()">
                            <div class="upload-title">Téléverser un fichier</div>
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <div class="upload-text">joindre un document en PDF, JPEG, PNG Taille maximale 10 Mo</div>
                            <!-- Aperçu du fichier sélectionné -->
                            <div class="upload-preview mt-2" style="display:none"></div>
                        </div>
                        <input type="file" class="hidden-file-input" id="preuve_paiement{{ $souscription->id }}" name="preuve_paiement" accept=".pdf,.jpg,.jpeg,.png">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Retour</button>
                    <button type="submit" class="btn btn-primary">Confirmer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection

@push('scripts')
<script>
(function() {
  function formatBytes(bytes) {
    if (bytes === 0) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
  }

  document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.hidden-file-input').forEach(function(input) {
      input.addEventListener('change', function() {
        const file = this.files && this.files[0] ? this.files[0] : null;
        const container = this.closest('.form-group');
        const uploadBox = container ? container.querySelector('.upload-box') : null;
        const preview = uploadBox ? uploadBox.querySelector('.upload-preview') : null;
        if (!file || !preview) return;
        preview.style.display = 'block';
        preview.innerHTML = '';

        if (file.type && file.type.startsWith('image/')) {
          const img = document.createElement('img');
          img.style.maxWidth = '100%';
          img.style.maxHeight = '200px';
          img.alt = file.name;
          const reader = new FileReader();
          reader.onload = function(e) { img.src = e.target.result; };
          reader.readAsDataURL(file);
          preview.appendChild(img);
        } else {
          const info = document.createElement('div');
          info.className = 'text-muted';
          info.textContent = file.name + ' (' + formatBytes(file.size) + ')';
          preview.appendChild(info);
        }
      });
    });
  });
})();
</script>
@endpush