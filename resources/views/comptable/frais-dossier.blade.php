@extends('layouts.comptable')

@section('title', 'Frais de Dossier')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="page-title">Frais de Dossier</h2>
        </div>
    </div>

    <!-- Barre de recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('comptable.frais-dossier') }}" class="row g-3">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Client, projet...">
                        </div>
                        <!-- Suppression du filtre Statut -->
                        <div class="col-md-3">
                            <label for="date_debut" class="form-label">Date début</label>
                            <input type="date" class="form-control" id="date_debut" name="date_debut" 
                                   value="{{ request('date_debut') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_fin" class="form-label">Date fin</label>
                            <input type="date" class="form-control" id="date_fin" name="date_fin" 
                                   value="{{ request('date_fin') }}">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                            <a href="{{ route('comptable.frais-dossier') }}" class="btn btn-secondary">
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
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Montant Total</span>
                    <div class="stat-icon icon-blue">
                        <i class="fas fa-calculator"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($fraisDossier->sum('montant'), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Montant Payé</span>
                    <div class="stat-icon icon-green">
                        <i class="fas fa-check"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($fraisDossier->sum('montant_paye'), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-header">
                    <span class="stat-title">Montant Restant</span>
                    <div class="stat-icon icon-orange">
                        <i class="fas fa-clock"></i>
                    </div>
                </div>
                <div class="stat-value">{{ number_format($fraisDossier->sum('montant_reste'), 0, ',', ' ') }} FCFA</div>
            </div>
        </div>
    </div>

    <!-- Table des enregistrements -->
    <div class="row">
        <div class="col-12">
            <div class="data-table-container">
                <div class="card-header-custom">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title-custom">
                            <i class="fas fa-list"></i>
                            Liste des Frais de Dossier
                        </h5>
                    </div><div>
                        <div class="btn-group" role="group" aria-label="Filtre Statut">
                            <a class="btn {{ request('statut') === 'en_attente' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('comptable.frais-dossier', array_filter(['search' => request('search'), 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin'), 'statut' => 'en_attente'])) }}">En attente</a>
                            <a class="btn {{ request('statut') === 'en_cours' ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('comptable.frais-dossier', array_filter(['search' => request('search'), 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin'), 'statut' => 'en_cours'])) }}">En cours</a>
                            <a class="btn {{ in_array(request('statut'), ['regle','payé']) ? 'btn-primary' : 'btn-outline-primary' }}" href="{{ route('comptable.frais-dossier', array_filter(['search' => request('search'), 'date_debut' => request('date_debut'), 'date_fin' => request('date_fin'), 'statut' => 'regle'])) }}">Réglé</a>
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
                                <th>Montant Total</th>
                                <th>Montant Payé</th>
                                <th>Montant Restant</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fraisDossier as $paiement)
                            @php
                                $souscription = $paiement->souscription;
                                $client = $souscription->client ?? null;
                                $projet = $paiement->projet ?? ($souscription->projet ?? null);
                                $montantTotal = (float) ($paiement->montant ?? 0);
                                $montantPaye = (float) ($paiement->montant_paye ?? 0);
                                $montantRestant = (float) ($paiement->montant_reste ?? max($montantTotal - $montantPaye, 0));
                            @endphp
                            <tr>
                                <td>{{ $client->ref_client ?? 'N/A' }}</td>
                                <td>{{ $souscription->ref_souscription ?? 'N/A' }}</td>
                                <td>{{ $client->nom_prenom ?? 'N/A' }}</td>
                                <td>{{ $projet->nom ?? 'N/A' }}</td>
                                <td>{{ number_format($montantTotal, 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($montantPaye, 0, ',', ' ') }} FCFA</td>
                                <td>{{ number_format($montantRestant, 0, ',', ' ') }} FCFA</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#validerModal{{ $paiement->id }}" title="Enregistrer un paiement">
                                            <i class="fas fa-money-bill"></i> Payer
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">Aucun frais de dossier trouvé</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $fraisDossier->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals pour enregistrer un paiement -->
@foreach($fraisDossier as $paiement)
<div class="modal fade" id="validerModal{{ $paiement->id }}" tabindex="-1" role="dialog" aria-labelledby="validerModalLabel{{ $paiement->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="validerModalLabel{{ $paiement->id }}">Payer - Frais de Dossier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('comptable.frais-dossier.payer', $paiement) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Informations</label>
                        <p><strong>Client:</strong> {{ $paiement->souscription->client->nom_prenom ?? 'N/A' }}</p>
                        <p><strong>Montant restant:</strong> {{ number_format($paiement->montant_reste ?? 0, 0, ',', ' ') }} FCFA</p>
                        <p><strong>Montant à payer:</strong></p>
                        <input type="number" name="montant" class="form-control" min="1" step="1" placeholder="Saisir le montant" required>
                    </div>
                    <div class="form-group mt-3">
                        <label for="mode{{ $paiement->id }}">Mode de paiement</label>
                        <select class="form-control" id="mode{{ $paiement->id }}" name="mode" required>
                            <option value="">Sélectionner le mode</option>
                            <option value="ESPECES">ESPECES</option>
                            <option value="VIREMENT">VIREMENT</option>
                            <option value="MOBILE_MONEY">MOBILE_MONEY</option>
                            <option value="TEMPERAMENT">TEMPERAMENT</option>
                            <option value="CREDIT_BANCAIRE">CREDIT_BANCAIRE</option>
                        </select>
                    </div>
                    <div class="form-group mt-3">
                        <label for="preuve_paiement{{ $paiement->id }}">Preuve du paiement</label>
                        <div class="upload-box" onclick="document.getElementById('preuve_paiement{{ $paiement->id }}').click()">
                            <div class="upload-title">Téléverser un fichier</div>
                            <i class="fas fa-cloud-upload-alt upload-icon"></i>
                            <div class="upload-text">joindre un document en PDF, JPEG, PNG Taille maximale 10 Mo</div>
                            <!-- Aperçu du fichier sélectionné -->
                            <div class="upload-preview mt-2" style="display:none"></div>
                        </div>
                        <input type="file" class="hidden-file-input" id="preuve_paiement{{ $paiement->id }}" name="preuve_paiement" accept=".pdf,.jpg,.jpeg,.png">
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

        // Afficher l'image si c'est une image, sinon le nom de fichier + taille
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