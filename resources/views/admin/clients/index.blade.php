@extends('layouts.admin')

@section('title', 'Clients')
@section('subtitle', 'Liste des comptes clients')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom d-flex justify-content-between align-items-center">
        <div class="card-title-custom">
            <i class="fas fa-user-tag me-2"></i>
            Liste des Clients
        </div>
    </div>
    <div style="padding: 20px;">
        <form method="GET" action="{{ route('admin.clients.index') }}" class="row g-2 mb-3">
            <div class="col-md-9">
                <input type="text" name="search" class="form-control" placeholder="Nom, email, téléphone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-filter"></i> Filtrer
                </button>
                <a class="btn btn-outline-secondary" href="{{ route('admin.clients.index') }}">Réinitialiser</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Date de création</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clients as $client)
                        <tr>
                            <td>
                                <i class="fas fa-user text-muted me-2"></i>
                                {{ $client->id }}
                            </td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->email }}</td>
                            <td>{{ $client->telephone ?? '—' }}</td>
                            <td>{{ optional($client->created_at)->format('d/m/Y') }}</td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('admin.clients.password.edit', $client) }}" class="btn btn-sm btn-outline-warning" title="Modifier le mot de passe">
                                        <i class="fas fa-key"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 20px;">Aucun client trouvé</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $clients->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection

