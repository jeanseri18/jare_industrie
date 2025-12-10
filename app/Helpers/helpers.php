<?php

if (!function_exists('formatBytes')) {
    /**
     * Format bytes to human readable format
     *
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    function formatBytes($bytes, $precision = 2) {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('getUserRoleLabel')) {
    /**
     * Get user role label
     *
     * @param string $role
     * @return string
     */
    function getUserRoleLabel($role) {
        $labels = [
            'DG' => 'Directeur Général',
            'Admin Technique' => 'Administrateur Technique',
            'Operateur' => 'Opérateur de saisie',
            'Comptable' => 'Comptable',
            'Chef Commercial' => 'Chef Commercial',
            'Client' => 'Client'
        ];

        return $labels[$role] ?? $role;
    }
}

if (!function_exists('getActionLabel')) {
    /**
     * Get action label
     *
     * @param string $action
     * @return string
     */
    function getActionLabel($action) {
        $labels = [
            'created' => 'Création',
            'updated' => 'Modification',
            'deleted' => 'Suppression',
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'backup' => 'Sauvegarde',
            'restore' => 'Restauration',
            'clear' => 'Nettoyage',
            'import' => 'Importation',
            'export' => 'Exportation'
        ];

        return $labels[$action] ?? ucfirst($action);
    }
}

if (!function_exists('getActionClass')) {
    /**
     * Get action CSS class
     *
     * @param string $action
     * @return string
     */
    function getActionClass($action) {
        $classes = [
            'created' => 'success',
            'updated' => 'primary',
            'deleted' => 'danger',
            'login' => 'info',
            'logout' => 'secondary',
            'backup' => 'warning',
            'restore' => 'info',
            'clear' => 'dark',
            'import' => 'primary',
            'export' => 'primary'
        ];

        return $classes[$action] ?? 'secondary';
    }
}

if (!function_exists('getStatusLabel')) {
    /**
     * Get status label
     *
     * @param string $status
     * @return string
     */
    function getStatusLabel($status) {
        $labels = [
            'pending' => 'En attente',
            'in_progress' => 'En cours',
            'completed' => 'Terminé',
            'failed' => 'Échoué',
            'cancelled' => 'Annulé',
            'active' => 'Actif',
            'inactive' => 'Inactif',
            'suspended' => 'Suspendu'
        ];

        return $labels[$status] ?? ucfirst($status);
    }
}

if (!function_exists('getStatusClass')) {
    /**
     * Get status CSS class
     *
     * @param string $status
     * @return string
     */
    function getStatusClass($status) {
        $classes = [
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
            'cancelled' => 'secondary',
            'active' => 'success',
            'inactive' => 'secondary',
            'suspended' => 'warning'
        ];

        return $classes[$status] ?? 'secondary';
    }
}

if (!function_exists('generateBackupFilename')) {
    /**
     * Generate backup filename
     *
     * @param string $type
     * @param bool $compressed
     * @return string
     */
    function generateBackupFilename($type = 'full', $compressed = true) {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $extension = $compressed ? '.sql.gz' : '.sql';
        return 'backup_' . $type . '_' . $timestamp . $extension;
    }
}

if (!function_exists('logActivity')) {
    /**
     * Log activity (alias for ActivityLogger::log)
     *
     * @param string $action
     * @param string $description
     * @param mixed $model
     * @param array $oldValues
     * @param array $newValues
     * @return void
     */
    function logActivity($action, $description, $model = null, $oldValues = [], $newValues = []) {
        \App\Services\ActivityLogger::log($action, $description, $model, $oldValues, $newValues);
    }
}