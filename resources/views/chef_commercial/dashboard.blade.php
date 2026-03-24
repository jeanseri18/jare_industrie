@extends('layouts.chef_commercial')
@section('title','Tableau de bord - Chef Commercial')
@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-title">Souscriptions corrigées</div>
                <div class="stat-value">{{ $souscriptionsCorrigees }}</div>
            </div>
            <div class="badge-icon" style="background:#e9f7ef;color:#2ecc71"><i class="fa-solid fa-check"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-title">Taux d'erreurs opérateurs</div>
                <div class="stat-value">{{ $tauxErreurs }} %</div>
            </div>
            <div class="badge-icon" style="background:#fdecea;color:#e74c3c"><i class="fa-solid fa-xmark"></i></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-title">Total de dossier soumis</div>
                <div class="stat-value">{{ $totalSouscriptions }}</div>
            </div>
            <div class="badge-icon" style="background:#eaf2ff;color:#003d82"><i class="fa-solid fa-file-circle-plus"></i></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="m-0">Liste des souscriptions</h5>
        <div></div>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('chef_commercial.dashboard') }}" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="text" name="nom" class="form-control" placeholder="Nom client" value="{{ request('nom') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="code" class="form-control" placeholder="Code souscription" value="{{ request('code') }}">
            </div>
            <div class="col-md-2">
                <input type="text" name="num_client" class="form-control" placeholder="N° client" value="{{ request('num_client') }}">
            </div>
            <div class="col-md-2">
                <select name="correction" class="form-select">
                    <option value="">Correction (tous)</option>
                    <option value="pas_corrige" {{ request('correction') === 'pas_corrige' ? 'selected' : '' }}>Non corrigée</option>
                    <option value="corrige" {{ request('correction') === 'corrige' ? 'selected' : '' }}>Corrigée</option>
                </select>
            </div>
            <div class="col-md-1">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-1">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-12 d-flex gap-2">
                <button class="btn btn-primary btn-sm" type="submit">Filtrer</button>
                <a class="btn btn-secondary btn-sm" href="{{ route('chef_commercial.dashboard') }}">Réinitialiser</a>
            </div>
        </form>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Operatrice</th>
                        <th>N° client</th>
                        <th>Nom client</th>
                        <th>Projet</th>
                        <th>Date de correction</th>
                        <th>Statut</th>
                        <th>Correction</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($correctionsRecentes as $correction)
                    <tr>
                        <td>{{ $correction->operateur->name ?? 'Non défini' }}</td>
                        <td>{{ $correction->client->ref_client ?? 'N/A' }}</td>
                        <td>{{ $correction->client->nom_prenom ?? $correction->nom_prenom ?? 'N/A' }}</td>
                        <td>{{ $correction->projet->nom ?? $correction->programme }}</td>
                        <td>{{ $correction->updated_at->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($correction->statut ?? '') }}</td>
                        <td>{{ ($correction->statut_correction ?? 'pas_corrige') === 'corrige' ? 'Corrigée' : 'Non corrigée' }}</td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('chef_commercial.souscriptions.edit', ['souscription' => $correction->id]) }}">Corriger</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center">Aucune souscription trouvée</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $correctionsRecentes->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
