@extends('layouts.dg')

@section('title', 'Suivi des équipes')

@section('content')
<x-page-header title="Suivi des équipes / Activer un compte">
    <x-slot:actions>
        <a href="{{ route('dg.equipes.create') }}" class="btn-primary">
            <i class="fas fa-user-plus me-1"></i> Créer un utilisateur
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

<x-list-filters-card action="{{ route('dg.equipes.index') }}" :reset-url="route('dg.equipes.index')">
    <div>
        <label for="name" class="form-label">Nom & prénom</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ request('name') }}" placeholder="Rechercher par nom...">
    </div>
    <div>
        <label for="role" class="form-label">Rôle</label>
        <select name="role" id="role" class="form-select">
            <option value="">Tous les rôles</option>
            <option value="operateur" {{ request('role') == 'operateur' ? 'selected' : '' }}>Opérateur</option>
            <option value="comptable" {{ request('role') == 'comptable' ? 'selected' : '' }}>Comptable</option>
            <option value="dg" {{ request('role') == 'dg' ? 'selected' : '' }}>DG</option>
            <option value="admin_technique" {{ request('role') == 'admin_technique' ? 'selected' : '' }}>Admin Technique</option>
            <option value="chef_commercial" {{ request('role') == 'chef_commercial' ? 'selected' : '' }}>Chef Commercial</option>
        </select>
    </div>
    <div>
        <label for="date_inscription" class="form-label">Date d'inscription</label>
        <input type="date" name="date_inscription" id="date_inscription" class="form-control" value="{{ request('date_inscription') }}">
    </div>
    <div>
        <label for="statut" class="form-label">Statut</label>
        <select name="statut" id="statut" class="form-select">
            <option value="tous" {{ request('statut', 'tous') == 'tous' ? 'selected' : '' }}>Tous</option>
            <option value="en_attente" {{ request('statut', 'tous') == 'en_attente' ? 'selected' : '' }}>En attente</option>
            <option value="actif" {{ request('statut', 'tous') == 'actif' ? 'selected' : '' }}>Actifs</option>
        </select>
    </div>
</x-list-filters-card>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Nom & prénom</th>
            <th>Rôle demandé</th>
            <th>Email</th>
            <th>Date d'inscription</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($users as $user)
        <tr>
            <td>{{ $user->name }}</td>
            <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->created_at?->format('d/m/Y') }}</td>
            <td>
                @if($user->requires_dg_validation && empty($user->email_verified_at))
                    <span class="status-badge status-attente">En attente</span>
                @else
                    <span class="status-badge status-valide">Actif</span>
                @endif
            </td>
            <td>
                <x-action-dropdown>
                    <li><a href="{{ route('dg.equipes.logs', $user) }}" class="dropdown-item"><i class="fas fa-history"></i> Voir les actions</a></li>
                    <li><a href="{{ route('dg.equipes.edit', $user) }}" class="dropdown-item"><i class="fas fa-edit"></i> Modifier</a></li>
                    @if($user->requires_dg_validation && empty($user->email_verified_at))
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form method="POST" action="{{ route('dg.equipes.activer', $user) }}" class="action-dropdown-form">
                                @csrf
                                <button type="submit" class="dropdown-item"><i class="fas fa-check"></i> Activer</button>
                            </form>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('dg.equipes.refuser', $user) }}" class="action-dropdown-form">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-ban"></i> Refuser</button>
                            </form>
                        </li>
                    @endif
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr><td colspan="6"><x-empty-state title="Aucun utilisateur trouvé" /></td></tr>
    @endforelse
</x-data-table>

<x-pagination :paginator="$users" />
@endsection
