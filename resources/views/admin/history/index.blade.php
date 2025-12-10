@extends('layouts.admin')

@section('title', 'Historique des Actions')
@section('subtitle', 'Journal des activités du système')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom">
        <h5 class="card-title-custom">
            <i class="fas fa-history me-2"></i>Historique des Actions
        </h5>
        <div class="btn-group">
            <button class="btn btn-outline-secondary" onclick="window.print()">
                <i class="fas fa-print"></i> Imprimer
            </button>
            <button class="btn btn-outline-primary" onclick="exportToCSV()">
                <i class="fas fa-download"></i> Exporter CSV
            </button>
        </div>
    </div>

    <div style="padding: 20px;">
        <form method="GET" action="{{ route('admin.history.index') }}" class="row g-3">
            <div class="col-md-3">
                <label for="user" class="form-label">Utilisateur</label>
                <select class="form-select" id="user" name="user">
                    <option value="">Tous les utilisateurs</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label for="action" class="form-label">Action</label>
                <select class="form-select" id="action" name="action">
                    <option value="">Toutes les actions</option>
                    <option value="create" {{ request('action') == 'create' ? 'selected' : '' }}>Création</option>
                    <option value="update" {{ request('action') == 'update' ? 'selected' : '' }}>Modification</option>
                    <option value="delete" {{ request('action') == 'delete' ? 'selected' : '' }}>Suppression</option>
                    <option value="login" {{ request('action') == 'login' ? 'selected' : '' }}>Connexion</option>
                    <option value="logout" {{ request('action') == 'logout' ? 'selected' : '' }}>Déconnexion</option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="date_from" class="form-label">Date de début</label>
                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-3">
                <label for="date_to" class="form-label">Date de fin</label>
                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <a href="{{ route('admin.history.index') }}" class="btn btn-secondary">
                    <i class="fas fa-refresh"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <div style="padding: 20px;">
        <div class="table-responsive">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Date & Heure</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Détails</th>
                        <th>Adresse IP</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($activities as $activity)
                    <tr>
                        <td>
                            <i class="fas fa-clock text-muted me-2"></i>
                            {{ $activity->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td>
                            <strong>{{ $activity->user->name ?? 'Système' }}</strong><br>
                            <small class="text-muted">{{ $activity->user->role ?? 'N/A' }}</small>
                        </td>
                        <td>
                            @php
                                $actionClass = [
                                    'create' => 'success',
                                    'update' => 'warning',
                                    'delete' => 'danger',
                                    'login' => 'info',
                                    'logout' => 'secondary'
                                ][$activity->action] ?? 'primary';
                            @endphp
                            <span class="badge-custom badge-{{ $actionClass }}">
                                {{ ucfirst($activity->action) }}
                            </span>
                        </td>
                        <td>
                            <strong>{{ $activity->description }}</strong><br>
                            <small class="text-muted">
                                @if($activity->model_type)
                                    {{ class_basename($activity->model_type) }} #{{ $activity->model_id }}
                                @endif
                            </small>
                        </td>
                        <td>
                            <code>{{ $activity->ip_address ?? 'N/A' }}</code><br>
                            <small class="text-muted">{{ $activity->user_agent ?? 'N/A' }}</small>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">
                            <i class="fas fa-info-circle"></i> Aucune activité trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="d-flex justify-content-center">
        {{ $activities->links() }}
    </div>
</div>

<script>
function exportToCSV() {
    const table = document.querySelector('table');
    const rows = Array.from(table.querySelectorAll('tr'));
    const csvContent = rows.map(row => {
        const cells = Array.from(row.querySelectorAll('th, td'));
        return cells.map(cell => {
            let text = cell.innerText.replace(/\n/g, ' ').trim();
            text = text.replace(/"/g, '""');
            return `"${text}"`;
        }).join(',');
    }).join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv' });
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'historique_actions_' + new Date().toISOString().slice(0, 10) + '.csv';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}
</script>
@endsection