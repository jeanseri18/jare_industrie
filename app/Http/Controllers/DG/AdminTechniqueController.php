<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\DatabaseBackup;
use App\Models\Paiement;
use App\Models\Projet;
use App\Models\Souscription;
use App\Models\User;
use App\Services\DashboardChartService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AdminTechniqueController extends Controller
{
    public function dashboard()
    {
        $authUser = Auth::user();
        if (!$authUser || !in_array($authUser->role, [User::ROLE_DG, User::ROLE_ADMIN_TECHNIQUE], true)) {
            abort(403);
        }

        $stats = [
            'total_users' => User::where('role', '!=', 'client')->count(),
            'total_clients' => Client::count(),
            'total_projets' => Projet::count(),
            'total_souscriptions' => Souscription::count(),
            'total_paiements' => Paiement::sum('montant'),
            'lastBackup' => DatabaseBackup::completed()->latest()->first(),
        ];

        $recentActivities = ActivityLog::with('user')
            ->latest()
            ->limit(10)
            ->get();

        $recentBackups = DatabaseBackup::with('user')
            ->completed()
            ->latest()
            ->limit(5)
            ->get();

        $roleOrder = [
            User::ROLE_DG,
            User::ROLE_ADMIN_TECHNIQUE,
            User::ROLE_OPERATEUR,
            User::ROLE_COMPTABLE,
            User::ROLE_CHEF_COMMERCIAL,
            User::ROLE_CLIENT,
        ];

        $roleCounts = User::selectRaw('COALESCE(role, ?) as role, COUNT(*) as count', [User::ROLE_CLIENT])
            ->groupBy('role')
            ->pluck('count', 'role')
            ->toArray();

        $usersByRole = [];
        foreach ($roleOrder as $role) {
            $usersByRole[$role] = (int) ($roleCounts[$role] ?? 0);
        }

        foreach ($roleCounts as $role => $count) {
            if (!array_key_exists($role, $usersByRole)) {
                $usersByRole[$role] = (int) $count;
            }
        }

        $monthlyStats = $this->getMonthlyStats();
        $alerts = $this->getAlerts();

        $charts = app(DashboardChartService::class);
        $roleChart = $charts->usersByRoleDonut($usersByRole);
        $activityTrend = $charts->monthlyActivityTrend();

        $chartDonut = array_merge(['title' => 'Utilisateurs par rôle'], $roleChart);
        $chartLine = array_merge(['title' => 'Évolution mensuelle'], $activityTrend);
        $chartBar = [
            'title' => 'Nouveaux utilisateurs',
            'labels' => $activityTrend['labels'],
            'values' => $activityTrend['datasets'][0]['values'] ?? [],
            'chartLabel' => 'Utilisateurs',
            'currency' => false,
        ];

        return view('dg.admin_technique.dashboard', compact(
            'stats',
            'recentActivities',
            'recentBackups',
            'usersByRole',
            'monthlyStats',
            'alerts',
            'chartDonut',
            'chartLine',
            'chartBar'
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

        // Alert "sauvegarde" supprimée (ne pas afficher de notification si aucune sauvegarde)

        $diskSpace = @disk_free_space(storage_path());
        $totalSpace = @disk_total_space(storage_path());
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
