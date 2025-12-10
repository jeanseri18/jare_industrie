<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ActivityLog;
use App\Models\DatabaseBackup;
use App\Models\Client;
use App\Models\Projet;
use App\Models\Souscription;
use App\Models\Paiement;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistiques générales
        $stats = [
            'total_users' => User::where('role', '!=', 'client')->count(),
            'total_clients' => Client::count(),
            'total_projets' => Projet::count(),
            'total_souscriptions' => Souscription::count(),
            'total_paiements' => Paiement::sum('montant'),
            'lastBackup' => DatabaseBackup::completed()->latest()->first(),
        ];

        // Activités récentes
        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        // Sauvegardes récentes
        $recentBackups = DatabaseBackup::with('user')
            ->completed()
            ->latest()
            ->limit(5)
            ->get();

        // Utilisateurs par rôle
        $usersByRole = User::where('role', '!=', 'client')
            ->selectRaw('role, COUNT(*) as count')
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        // Statistiques mensuelles
        $monthlyStats = $this->getMonthlyStats();

        // Alertes et notifications
        $alerts = $this->getAlerts();

        return view('admin.dashboard', compact(
            'stats',
            'recentActivities',
            'recentBackups',
            'usersByRole',
            'monthlyStats',
            'alerts'
        ));
    }

    private function getMonthlyStats()
    {
        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        return [
            'users' => [
                'current' => User::where('role', '!=', 'client')
                    ->whereMonth('created_at', $currentMonth->month)
                    ->whereYear('created_at', $currentMonth->year)
                    ->count(),
                'previous' => User::where('role', '!=', 'client')
                    ->whereMonth('created_at', $previousMonth->month)
                    ->whereYear('created_at', $previousMonth->year)
                    ->count(),
            ],
            'clients' => [
                'current' => Client::whereMonth('created_at', $currentMonth->month)
                    ->whereYear('created_at', $currentMonth->year)
                    ->count(),
                'previous' => Client::whereMonth('created_at', $previousMonth->month)
                    ->whereYear('created_at', $previousMonth->year)
                    ->count(),
            ],
            'souscriptions' => [
                'current' => Souscription::whereMonth('created_at', $currentMonth->month)
                    ->whereYear('created_at', $currentMonth->year)
                    ->count(),
                'previous' => Souscription::whereMonth('created_at', $previousMonth->month)
                    ->whereYear('created_at', $previousMonth->year)
                    ->count(),
            ],
        ];
    }

    private function getAlerts()
    {
        $alerts = [];

        // Vérifier la dernière sauvegarde
        $lastBackup = DatabaseBackup::completed()->latest()->first();
        if (!$lastBackup || $lastBackup->created_at < now()->subDays(7)) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Aucune sauvegarde récente trouvée. La dernière sauvegarde date de ' . 
                    ($lastBackup ? $lastBackup->created_at->diffForHumans() : 'jamais'),
                'icon' => 'fas fa-exclamation-triangle',
            ];
        }

        // Vérifier l'espace disque (si possible)
        $diskSpace = disk_free_space(storage_path());
        $totalSpace = disk_total_space(storage_path());
        if ($diskSpace && $totalSpace) {
            $freePercentage = ($diskSpace / $totalSpace) * 100;
            if ($freePercentage < 10) {
                $alerts[] = [
                    'type' => 'danger',
                    'message' => 'Espace disque faible : ' . round($freePercentage, 1) . '% disponible',
                    'icon' => 'fas fa-hdd',
                ];
            }
        }

        // Vérifier les utilisateurs inactifs
        $inactiveUsers = User::where('role', '!=', 'client')
            ->where('last_login_at', '<', now()->subMonths(3))
            ->count();
        
        if ($inactiveUsers > 0) {
            $alerts[] = [
                'type' => 'info',
                'message' => $inactiveUsers . ' utilisateur(s) inactif(s) depuis plus de 3 mois',
                'icon' => 'fas fa-user-clock',
            ];
        }

        return $alerts;
    }
}