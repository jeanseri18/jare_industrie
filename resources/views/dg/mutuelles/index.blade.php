@extends('layouts.dg')

@section('title', 'Mutuelles')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-building me-2"></i>
            Liste des Mutuelles
        </div>
        <a href="{{ route('dg.mutuelles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Créer une mutuelle
        </a>
    </div>
    <div style="padding: 20px;">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Code</th>
                        <th>Valeur du Bien</th>
                        <th>Taux de Réduction</th>
                        <th>Apport Initial</th>
                        <th>Projet Associé</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mutuelles as $mutuelle)
                        <tr>
                            <td>
                                <i class="fas fa-hashtag text-muted me-2"></i>
                                {{ $mutuelle->id }}
                            </td>
                            <td>{{ $mutuelle->nom }}</td>
                            <td>{{ $mutuelle->code }}</td>
                            <td>{{ number_format($mutuelle->valeur_du_bien, 2, ',', ' ') }} F CFA</td>
                            <td>{{ $mutuelle->taux_reduction }}%</td>
                            <td>{{ number_format($mutuelle->apport_initial, 2, ',', ' ') }} F CFA</td>
                            <td>
                                @if($mutuelle->projet)
                                    <span class="badge-custom badge-info">{{ $mutuelle->projet->nom }}</span>
                                @else
                                    <span class="text-muted">Aucun projet</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge-custom {{ $mutuelle->est_active ? 'badge-success' : 'badge-danger' }}">
                                    {{ $mutuelle->est_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('dg.mutuelles.edit', $mutuelle) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('dg.mutuelles.destroy', $mutuelle) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette mutuelle ?')">
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
        
        <div class="d-flex justify-content-center mt-4">
            {{ $mutuelles->links() }}
        </div>
    </div>
</div>
@endsection