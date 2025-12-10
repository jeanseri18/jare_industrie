@extends('layouts.admin')

@section('title', 'Sauvegarder la Base de Données')
@section('subtitle', 'Gestion des sauvegardes du système')

@section('content')
<div class="data-table-container">
    <div class="card-header-custom">
        <h5 class="card-title-custom">
            <i class="fas fa-database me-2"></i>Sauvegarder la Base de Données
        </h5>
        <button class="btn btn-primary" onclick="createBackup()">
            <i class="fas fa-database"></i> Créer une sauvegarde
        </button>
    </div>

    <div class="row" style="padding: 20px;">
        <div class="col-md-8">
            <div class="info-banner success mb-4">
                <i class="fas fa-info-circle"></i>
                <div>
                    <strong>Informations de la base de données</strong><br>
                    <small>Nom: {{ config('database.connections.mysql.database') }} | Type: {{ config('database.default') }} | Hôte: {{ config('database.connections.mysql.host') }}</small>
                </div>
            </div>

            <div class="data-table-container mb-4">
            <div class="card-header-custom">
                <h5 class="card-title-custom">
                    <i class="fas fa-cog me-2"></i>Options de sauvegarde
                </h5>
            </div>
            <div style="padding: 20px;">
                <form id="backupForm" method="POST" action="{{ route('admin.backup.create') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="backup_type" class="form-label">Type de sauvegarde</label>
                        <select class="form-select" id="backup_type" name="backup_type" required>
                            <option value="full">Sauvegarde complète</option>
                            <option value="structure">Structure uniquement</option>
                            <option value="data">Données uniquement</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="tables" class="form-label">Tables (laisser vide pour toutes)</label>
                        <input type="text" class="form-control" id="tables" name="tables" 
                               placeholder="users,clients,documents (séparées par des virgules)">
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="compress" name="compress" checked>
                            <label class="form-check-label" for="compress">
                                Compresser la sauvegarde (ZIP)
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="filename" class="form-label">Nom du fichier (optionnel)</label>
                        <input type="text" class="form-control" id="filename" name="filename" 
                               placeholder="backup_{{ date('Y-m-d_H-i-s') }}">
                    </div>
                    
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-download"></i> Lancer la sauvegarde
                    </button>
                </form>
            </div>
        </div>
        </div>

        <div class="col-md-4">
            <div class="data-table-container mb-3">
                <div class="card-header-custom">
                    <h5 class="card-title-custom">
                        <i class="fas fa-chart-bar me-2"></i>Statistiques des sauvegardes
                    </h5>
                </div>
                <div style="padding: 20px;">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Sauvegardes totales:</span>
                            <strong>{{ $totalBackups ?? 0 }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Taille totale:</span>
                            <strong>{{ formatBytes($totalSize ?? 0) }}</strong>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between">
                            <span>Dernière sauvegarde:</span>
                            <strong>{{ $lastBackup ? $lastBackup->created_at->diffForHumans() : 'Jamais' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="info-banner warning">
                <i class="fas fa-lightbulb"></i>
                <div>
                    <strong>Conseils de sauvegarde</strong><br>
                    <small>Effectuer des sauvegardes régulièrement • Stocker hors site • Tester la restauration • Garder plusieurs versions</small>
                </div>
            </div>
        </div>
    </div>

    <div class="data-table-container mt-4">
        <div class="card-header-custom">
            <h5 class="card-title-custom">
                <i class="fas fa-history me-2"></i>Sauvegardes récentes
            </h5>
        </div>
        <div style="padding: 20px;">
            <div class="table-responsive">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Nom du fichier</th>
                            <th>Type</th>
                            <th>Taille</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBackups as $backup)
                        <tr>
                            <td>
                                <i class="fas fa-calendar-alt text-muted me-2"></i>
                                {{ $backup->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td>{{ $backup->filename }}</td>
                            <td>
                                @php
                                    $typeClass = [
                                        'full' => 'primary',
                                        'structure' => 'warning',
                                        'data' => 'info'
                                    ][$backup->type] ?? 'secondary';
                                @endphp
                                <span class="badge-custom badge-{{ $typeClass }}">
                                    {{ ucfirst($backup->type) }}
                                </span>
                            </td>
                            <td>{{ formatBytes($backup->size) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.backup.download', $backup->id) }}" class="btn btn-outline-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button onclick="deleteBackup({{ $backup->id }})" class="btn btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                <i class="fas fa-info-circle"></i> Aucune sauvegarde trouvée
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function createBackup() {
    if (confirm('Êtes-vous sûr de vouloir créer une nouvelle sauvegarde ?')) {
        document.getElementById('backupForm').submit();
    }
}

function deleteBackup(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde ?')) {
        fetch(`/admin/backup/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression');
            }
        });
    }
}
</script>
@endsection