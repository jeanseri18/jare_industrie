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
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom & Prénom</th>
                        <th>Rôle demandé</th>
                        <th>Email</th>
                        <th>Date d’inscription</th>
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
                            <td class="d-flex gap-2">
                                <a href="{{ route('dg.equipes.edit', $user) }}" class="btn btn-warning btn-sm">
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('dg.equipes.refuser', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Refuser</button>
                                </form>
                                <form method="POST" action="{{ route('dg.equipes.activer', $user) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm">Activer</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun utilisateur en attente d’activation.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection