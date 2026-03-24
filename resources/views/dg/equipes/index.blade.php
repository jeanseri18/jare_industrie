@extends('layouts.dg')

@section('title', 'Suivi des équipes / Activer un compte')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-users me-2"></i>
            Suivi des équipes / Activer un compte  
            
            <a href="{{ route('dg.equipes.create') }}" class="btn btn-primary btn-sm ms-3">
                <i class="fas fa-user-plus"></i> Créer un utilisateur
            </a>
        </div>
    </div>

    <div style="padding: 20px;">
        <!-- Formulaire de filtrage -->
        <div class="card-custom mb-4 p-3 bg-light">
            <form method="GET" action="{{ route('dg.equipes.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="name" class="form-label small fw-bold">Nom & Prénom</label>
                    <input type="text" name="name" id="name" class="form-control form-control-sm" 
                           value="{{ request('name') }}" placeholder="Rechercher par nom...">
                </div>
                <div class="col-md-3">
                    <label for="role" class="form-label small fw-bold">Rôle</label>
                    <select name="role" id="role" class="form-select form-select-sm">
                        <option value="">Tous les rôles</option>
                        <option value="operateur" {{ request('role') == 'operateur' ? 'selected' : '' }}>Opérateur</option>
                        <option value="comptable" {{ request('role') == 'comptable' ? 'selected' : '' }}>Comptable</option>
                        <option value="dg" {{ request('role') == 'dg' ? 'selected' : '' }}>DG</option>
                        <option value="admin_technique" {{ request('role') == 'admin_technique' ? 'selected' : '' }}>Admin Technique</option>
                        <option value="chef_commercial" {{ request('role') == 'chef_commercial' ? 'selected' : '' }}>Chef Commercial</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="date_inscription" class="form-label small fw-bold">Date d'inscription</label>
                    <input type="date" name="date_inscription" id="date_inscription" 
                           class="form-control form-control-sm" value="{{ request('date_inscription') }}">
                </div>
                <div class="col-md-2">
                    <label for="statut" class="form-label small fw-bold">Statut</label>
                    <select name="statut" id="statut" class="form-select form-select-sm">
                        <option value="tous" {{ request('statut') == 'tous' ? 'selected' : '' }}>Tous</option>
                        <option value="en_attente" {{ request('statut', 'en_attente') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="actif" {{ request('statut') == 'actif' ? 'selected' : '' }}>Actifs</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-filter"></i> Filtrer
                    </button>
                    <a href="{{ route('dg.equipes.index') }}" class="btn btn-secondary btn-sm w-100">
                        <i class="fas fa-undo"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom & Prénom</th>
                        <th>Rôle demandé</th>
                        <th>Email</th>
                        <th>Date d’inscription</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at?->format('d/m/Y') }}</td>
                            <td>
                                @if($user->requires_dg_validation && empty($user->email_verified_at))
                                    <span class="badge bg-warning text-dark">En attente</span>
                                @else
                                    <span class="badge bg-success">Actif</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('dg.equipes.logs', $user) }}" class="btn btn-secondary btn-sm">
                                    Voir les actions
                                </a>
                                <a href="{{ route('dg.equipes.edit', $user) }}" class="btn btn-warning btn-sm">
                                    Modifier
                                </a>
                                @if($user->requires_dg_validation && empty($user->email_verified_at))
                                    <form method="POST" action="{{ route('dg.equipes.refuser', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                                    </form>
                                    <form method="POST" action="{{ route('dg.equipes.activer', $user) }}">
                                        @csrf
                                        <button type="submit" class="btn btn-primary btn-sm">Activer</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">
                                @if(request('statut', 'en_attente') === 'actif')
                                    Aucun utilisateur actif trouvé.
                                @elseif(request('statut', 'en_attente') === 'tous')
                                    Aucun utilisateur trouvé.
                                @else
                                    Aucun utilisateur en attente d’activation.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
