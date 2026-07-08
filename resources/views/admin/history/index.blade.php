@extends('layouts.admin')

@section('title', 'Historique des Actions')
@section('subtitle', 'Journal des activités du système')

@section('content')
<x-page-header title="Historique des actions">
    <x-slot:actions>
        <button type="button" class="btn-secondary" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Imprimer
        </button>
        <button type="button" class="btn-primary" onclick="exportToCSV()">
            <i class="fas fa-download me-1"></i> Exporter CSV
        </button>
    </x-slot:actions>
</x-page-header>
<x-alert />

<x-list-filters-card action="{{ route('admin.history.index') }}" :reset-url="route('admin.history.index')">
    <div>
        <label for="user" class="form-label">Utilisateur</label>
        <select class="form-select" id="user" name="user">
            <option value="">Tous les utilisateurs</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}" {{ request('user') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <div>
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
    <div>
        <label for="date_from" class="form-label">Date de début</label>
        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
    </div>
    <div>
        <label for="date_to" class="form-label">Date de fin</label>
        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
    </div>
</x-list-filters-card>

<x-data-table>
    <x-slot:head>
        <tr>
            <th>Date & heure</th>
            <th>Utilisateur</th>
            <th>Action</th>
            <th>Détails</th>
            <th>Adresse IP</th>
        </tr>
    </x-slot:head>
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
</x-data-table>
<x-pagination :paginator="$activities" />

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
