@extends('layouts.platform')

@section('title', 'Organisations')

@section('content')
<x-page-header title="Organisations BTP" subtitle="Gérez les entreprises inscrites sur la plateforme.">
    <x-slot:actions>
        <a href="{{ route('platform.organizations.create') }}" class="btn-primary">Nouvelle organisation</a>
    </x-slot:actions>
</x-page-header>

<x-alert />

<x-data-table :headers="['Nom', 'Sous-domaine', 'Plan', 'Users', 'Projets', 'Statut', 'Actions']">
    @forelse($organizations as $org)
        <tr>
            <td class="font-medium">{{ $org->name }}</td>
            <td>{{ $org->subdomain }}</td>
            <td><span class="badge badge-info">{{ ucfirst($org->plan) }}</span></td>
            <td>{{ $org->users_count }}</td>
            <td>{{ $org->projets_count }}</td>
            <td><span class="badge {{ $org->is_active ? 'badge-success' : 'badge-danger' }}">{{ $org->is_active ? 'Actif' : 'Inactif' }}</span></td>
            <td>
                <x-action-dropdown>
                    <li>
                        <a href="{{ route('platform.organizations.edit', $org) }}" class="dropdown-item">
                            <i class="fas fa-edit"></i> Modifier
                        </a>
                    </li>
                </x-action-dropdown>
            </td>
        </tr>
    @empty
        <tr><td colspan="7"><x-empty-state /></td></tr>
    @endforelse
</x-data-table>

<x-pagination :paginator="$organizations" />
@endsection
