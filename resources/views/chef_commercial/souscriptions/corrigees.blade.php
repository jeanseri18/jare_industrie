@extends('layouts.chef_commercial')
@section('title','Souscriptions corrigées - Chef Commercial')
@section('content')
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-title">Total à corriger</div>
                <div class="stat-value">{{ $totalEnAttente }}</div>
            </div>
            <div class="badge-icon" style="background:#fdecea;color:#e74c3c"><i class="fa-solid fa-exclamation-triangle"></i></div>
        </div>
    </div>
    @foreach($souscriptionsParOperateur as $stat)
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-title">{{ $stat->operateur->name ?? 'Opérateur' }}</div>
                <div class="stat-value">{{ $stat->total }}</div>
            </div>
            <div class="badge-icon" style="background:#fff7e6;color:#f1c40f"><i class="fa-solid fa-user"></i></div>
        </div>
    </div>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <h5 class="m-0">Dossier à corriger</h5>
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
                        <th>Date de création</th>
                        <th>Champs corriger</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($souscriptionsACorriger as $souscription)
                    <tr>
                        <td>{{ $souscription->operateur->name ?? 'Non défini' }}</td>
                        <td>{{ $souscription->client->ref_client ?? 'N/A' }}</td>
                        <td>{{ $souscription->projet->nom ?? $souscription->programme }}</td>
                        <td>{{ $souscription->created_at->format('d/m/Y') }}</td>
                        <td>{{ ucfirst($souscription->statut) }}</td>
                        <td><a class="btn btn-sm btn-danger" href="{{ route('chef_commercial.souscriptions.edit', ['souscription' => $souscription->id]) }}">Corriger</a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Aucune souscription à corriger</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection