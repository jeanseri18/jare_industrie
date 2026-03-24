@extends('layouts.comptable')

@section('title', 'Dossiers Annulés')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-ban me-2"></i>
            Liste des Dossiers Annulés
        </div>
        <div>
            <!-- Filters -->
            <form action="{{ route('comptable.dossiers-annules') }}" method="GET" class="d-flex gap-2">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher (Client, Réf...)" value="{{ request('search') }}">
                <button type="submit" class="btn btn-primary btn-sm">Filtrer</button>
                @if(request()->hasAny(['search']))
                    <a href="{{ route('comptable.dossiers-annules') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div style="padding: 20px;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Client</th>
                        <th>Projet</th>
                        <th>Montant Payé</th>
                        <th>Montant Remboursé</th>
                        <th>Reste à Rembourser</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
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
                            <td class="text-success font-weight-bold">{{ number_format($totalPaye, 0, ',', ' ') }} FCFA</td>
                            <td class="text-warning font-weight-bold">{{ number_format($totalRembourse, 0, ',', ' ') }} FCFA</td>
                            <td class="text-danger font-weight-bold">{{ number_format($reste, 0, ',', ' ') }} FCFA</td>
                            <td>
                                @if($reste > 0)
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#rembourserModal{{ $dossier->id }}">
                                        <i class="fas fa-undo me-1"></i> Rembourser
                                    </button>

                                    <!-- Modal Remboursement -->
                                    <div class="modal fade" id="rembourserModal{{ $dossier->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Rembourser le dossier {{ $dossier->ref_souscription }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('comptable.dossiers-annules.rembourser', $dossier) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="alert alert-info">
                                                            Montant disponible à rembourser : <strong>{{ number_format($reste, 0, ',', ' ') }} FCFA</strong>
                                                        </div>
                                                        
                                                        <div class="mb-3">
                                                            <label class="form-label">Montant à rembourser</label>
                                                            <input type="number" name="montant" class="form-control" max="{{ $reste }}" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Mode de remboursement</label>
                                                            <select name="mode" class="form-select" required>
                                                                <option value="ESPECES">Espèces</option>
                                                                <option value="VIREMENT">Virement</option>
                                                                <option value="CHEQUE">Chèque</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label class="form-label">Motif / Observation</label>
                                                            <textarea name="motif" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                        <button type="submit" class="btn btn-primary">Valider le remboursement</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Soldé</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Aucun dossier annulé trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $dossiersAnnules->links() }}
        </div>
    </div>
</div>
@endsection
