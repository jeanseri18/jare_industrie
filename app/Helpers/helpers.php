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
            'dg' => 'Directeur Général',
            'admin_technique' => 'Administrateur Technique',
            'operateur' => 'Opérateur de saisie',
            'comptable' => 'Comptable',
            'chef_commercial' => 'Chef Commercial',
            'client' => 'Client'
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

if (!function_exists('numberToWords')) {
    /**
     * Convert number to words in French
     *
     * @param float|int $number
     * @return string
     */
    function numberToWords($number) {
        $hyphen      = '-';
        $conjunction = ' ';
        $separator   = ' ';
        $negative    = 'moins ';
        $decimal     = ' virgule ';
        $dictionary  = array(
            0                   => 'zéro',
            1                   => 'un',
            2                   => 'deux',
            3                   => 'trois',
            4                   => 'quatre',
            5                   => 'cinq',
            6                   => 'six',
            7                   => 'sept',
            8                   => 'huit',
            9                   => 'neuf',
            10                  => 'dix',
            11                  => 'onze',
            12                  => 'douze',
            13                  => 'treize',
            14                  => 'quatorze',
            15                  => 'quinze',
            16                  => 'seize',
            17                  => 'dix-sept',
            18                  => 'dix-huit',
            19                  => 'dix-neuf',
            20                  => 'vingt',
            30                  => 'trente',
            40                  => 'quarante',
            50                  => 'cinquante',
            60                  => 'soixante',
            70                  => 'soixante-dix',
            80                  => 'quatre-vingts',
            90                  => 'quatre-vingt-dix',
            100                 => 'cent',
            1000                => 'mille',
            1000000             => 'million',
            1000000000          => 'milliard',
            1000000000000       => 'billion',
            1000000000000000    => 'billiard',
            1000000000000000000 => 'trillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . numberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    if ($units == 1 && $tens != 80) {
                        $string .= ' et un';
                    } elseif ($number == 71) {
                        $string = 'soixante et onze';
                    } elseif ($number > 71 && $number < 80) {
                        $string = 'soixante-' . numberToWords($number - 60);
                    } elseif ($number == 81) {
                        $string = 'quatre-vingt-un';
                    } elseif ($number > 90 && $number < 100) {
                        $string = 'quatre-vingt-' . numberToWords($number - 80);
                    } else {
                        $string .= $hyphen . $dictionary[$units];
                    }
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                if ($hundreds >= 1 && $hundreds < 2) {
                     $string = $dictionary[100];
                } else {
                     $string = numberToWords((int)$hundreds) . ' ' . $dictionary[100];
                     // Gestion du pluriel de cent
                     if ($remainder == 0 && (int)$hundreds > 1) {
                         $string .= 's';
                     }
                }
                
                if ($remainder) {
                    $string .= $separator . numberToWords($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                
                if ($baseUnit == 1000) {
                    if ($numBaseUnits == 1) {
                         $string = $dictionary[1000];
                    } else {
                         $string = numberToWords($numBaseUnits) . ' ' . $dictionary[1000];
                    }
                } else {
                     $string = numberToWords($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                     if ($numBaseUnits > 1) {
                         $string .= 's';
                     }
                }
                
                if ($remainder) {
                    $string .= $separator . numberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return $string;
    }
}
