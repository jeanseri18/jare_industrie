@extends('layouts.dg')

@section('title', 'Biens Immobiliers - ' . $projet->nom)

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-building me-2"></i>
            Biens Immobiliers - {{ $projet->nom }}
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('dg.projets.biens.create', $projet) }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nouveau Bien
            </a>
            <a href="{{ route('dg.projets.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour aux Projets
            </a>
        </div>
    </div>
    <div style="padding: 20px;">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Titre</th>
                        <th>Type</th>
                        <th>Prix</th>
                        <th>Frais Souscription</th>
                        <th>Apport (%)</th>
                        <th>Apport Initial</th>
                        <th>Surface</th>
                        <th>Pièces</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($biens as $bien)
                    <tr>
                        <td>{{ $bien->id }}</td>
                        <td>{{ $bien->titre }}</td>
                        <td>
                            <span class="badge-custom badge-info">
                                {{ ucfirst($bien->type) }}
                            </span>
                        </td>
                        <td>{{ number_format($bien->prix, 0, ',', ' ') }} FCFA</td>
                        <td>{{ number_format($bien->frais_souscription, 0, ',', ' ') }} FCFA</td>
                        <td>{{ $bien->pourcentage_apport }}%</td>
                        <td>{{ $bien->apport_initial ? number_format($bien->apport_initial, 0, ',', ' ') . ' FCFA' : 'N/A' }}</td>
                        <td>{{ $bien->surface_habitable ?? 'N/A' }} m²{{ $bien->surface_total ? ' / '.$bien->surface_total.' m²' : '' }}</td>
                        <td>{{ $bien->nbre_piece ?? 'N/A' }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('dg.biens.edit', $bien) }}" class="btn btn-sm btn-warning" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('dg.biens.destroy', $bien) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce bien immobilier ?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $biens->links() }}
        </div>
    </div>
</div>
@endsection