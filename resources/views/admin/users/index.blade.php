@extends('layouts.admin')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<x-page-header title="Liste des utilisateurs">
    <x-slot:actions>
        <a href="{{ route('admin.users.create') }}" class="btn-primary">
            <i class="fas fa-plus me-1"></i> Créer un utilisateur
        </a>
    </x-slot:actions>
</x-page-header>
<x-alert />

<x-filter-bar action="{{ route('admin.users.index') }}">
    <div>
        <label class="form-label">Recherche</label>
        <input type="text" name="search" class="form-input" placeholder="Nom, email, téléphone..." value="{{ request('search') }}">
    </div>
    <div>
        <label class="form-label">Rôle</label>
        <select name="role" class="form-select">
            <option value="">Tous les rôles</option>
            @foreach(($roles ?? []) as $role)
                <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ getUserRoleLabel($role) }}</option>
            @endforeach
        </select>
    </div>
</x-filter-bar>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </x-slot:head>
    @forelse($users as $user)
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td><span class="badge-custom badge-info">{{ getUserRoleLabel($user->role) }}</span></td>
            <td>{{ $user->created_at->format('d/m/Y') }}</td>
            <td>
                @if($user->role !== 'client')
                    <x-action-dropdown>
                        <li><a href="{{ route('admin.users.edit', $user) }}" class="dropdown-item"><i class="fas fa-edit"></i> Modifier</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="action-dropdown-form" onsubmit="return confirm('Supprimer cet utilisateur ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger"><i class="fas fa-trash"></i> Supprimer</button>
                            </form>
                        </li>
                    </x-action-dropdown>
                @else
                    <span class="text-muted">—</span>
                @endif
            </td>
        </tr>
    @empty
        <tr><td colspan="6"><x-empty-state title="Aucun utilisateur" /></td></tr>
    @endforelse
</x-data-table>
<x-pagination :paginator="$users" />
@endsection
