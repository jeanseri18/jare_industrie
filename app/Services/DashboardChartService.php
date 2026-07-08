<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Paiement;
use App\Models\Souscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardChartService
{
    /** @var list<string> */
    public const COLORS = ['#111827', '#ff7200', '#6b7280', '#374151', '#9ca3af', '#d1d5db'];

    /**
     * @return array{labels: list<string>, values: list<float|int>}
     */
    public function souscriptionStatusDonut(int $enAttente, int $enCours, int $soldees): array
    {
        return [
            'labels' => ['En attente', 'En cours', 'Soldées'],
            'values' => [$enAttente, $enCours, $soldees],
        ];
    }

    /**
     * @return array{labels: list<string>, values: list<float>}
     */
    public function monthlyEncaissements(int $months = 6): array
    {
        $labels = [];
        $values = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = ucfirst($date->translatedFormat('M Y'));
            $values[] = (float) Paiement::where('statut', 'payé')
                ->whereMonth('date_paiement', $date->month)
                ->whereYear('date_paiement', $date->year)
                ->sum('montant');
        }

        return compact('labels', 'values');
    }

    /**
     * @return array{labels: list<string>, values: list<float>}
     */
    public function encaissementsByProject(int $limit = 8): array
    {
        $byProject = DB::table('paiements')
            ->join('souscriptions', 'paiements.dossier_id', '=', 'souscriptions.id')
            ->join('projets', 'souscriptions.programme', '=', 'projets.id')
            ->where('paiements.statut', '=', 'payé')
            ->select('projets.nom as projet_nom', DB::raw('SUM(paiements.montant) as total'))
            ->groupBy('projets.nom')
            ->orderByDesc('total')
            ->limit($limit)
            ->get();

        return [
            'labels' => $byProject->pluck('projet_nom')->toArray(),
            'values' => $byProject->pluck('total')->map(fn ($v) => (float) $v)->toArray(),
        ];
    }

    /**
     * @return array{labels: list<string>, datasets: list<array{label: string, values: list<int>}>}
     */
    public function monthlyActivityTrend(int $months = 6): array
    {
        $labels = [];
        $users = [];
        $clients = [];
        $souscriptions = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = ucfirst($date->translatedFormat('M Y'));

            $users[] = User::where('role', '!=', 'client')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $clients[] = Client::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();

            $souscriptions[] = Souscription::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Utilisateurs', 'values' => $users],
                ['label' => 'Clients', 'values' => $clients],
                ['label' => 'Souscriptions', 'values' => $souscriptions],
            ],
        ];
    }

    /**
     * @param array<string, int> $usersByRole
     * @return array{labels: list<string>, values: list<int>}
     */
    public function usersByRoleDonut(array $usersByRole): array
    {
        $roleLabels = [
            'dg' => 'DG',
            'admin_technique' => 'Admin technique',
            'operateur' => 'Opérateur',
            'comptable' => 'Comptable',
            'chef_commercial' => 'Chef commercial',
            'client' => 'Client',
        ];

        $labels = [];
        $values = [];

        foreach ($usersByRole as $role => $count) {
            if ((int) $count <= 0) {
                continue;
            }
            $labels[] = $roleLabels[$role] ?? $role;
            $values[] = (int) $count;
        }

        return compact('labels', 'values');
    }
}
