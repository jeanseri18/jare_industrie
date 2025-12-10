@extends('layouts.dg')

@section('title', 'Gestion des Projets')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-project-diagram me-2"></i>
            Gestion des Projets
        </div>
        <a href="{{ route('dg.projets.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Projet
        </a>
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
                                    <th>Nom</th>
                                    <th>Localisation</th>
                                    <th>Superficie</th>
                                    <th>Type</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($projets as $projet)
                                <tr>
                                    <td>{{ $projet->id }}</td>
                                    <td>{{ $projet->nom }}</td>
                                    <td>{{ $projet->localisation ?? 'Non définie' }}</td>
                                    <td>{{ $projet->superficie ?? 'N/A' }} m²</td>
                                    <td>
                                        @if($projet->isduplex)
                                            <span class="badge-custom badge-primary">Duplex</span>
                                        @endif
                                        @if($projet->isappartement)
                                            <span class="badge-custom badge-info">Appartement</span>
                                        @endif
                                        @if($projet->isvillabase)
                                            <span class="badge-custom badge-success">Villa</span>
                                        @endif
                                        @if($projet->isterrains)
                                            <span class="badge-custom badge-warning">Terrain</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge-custom badge-{{ $projet->est_actif ? 'success' : 'danger' }}">
                                            {{ $projet->est_actif ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('dg.projets.edit', $projet) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('dg.projets.destroy', $projet) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce projet ?')">
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
                        {{ $projets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection