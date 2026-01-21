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
                <div class="stat-title">Délai moyen de correction</div>
                <div class="stat-value">4h 22min</div>
            </div>
            <div class="badge-icon" style="background:#fff7e6;color:#f1c40f"><i class="fa-solid fa-clock"></i></div>
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
        <h5 class="m-0">Historiques</h5>
        <div></div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Operatrice</th>
                        <th>N° client</th>
                        <th>Projet</th>
                        <th>Date de correction</th>
                        <th>Champs corriger</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($correctionsRecentes as $correction)
                    <tr>
                        <td>{{ $correction->operateur->name ?? 'Non défini' }}</td>
                        <td>{{ $correction->client->ref_client ?? 'N/A' }}</td>
                        <td>{{ $correction->projet->nom ?? $correction->programme }}</td>
                        <td>{{ $correction->updated_at->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($correction->statut) }}</td>
                        <td><a class="btn btn-sm btn-primary" href="{{ route('chef_commercial.souscriptions.edit', ['souscription' => $correction->id]) }}">Corriger</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune correction récente</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection