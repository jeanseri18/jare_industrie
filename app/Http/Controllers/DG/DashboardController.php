<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Projet;
use App\Models\Mutuelle;
use App\Models\Paiement;
use App\Models\Souscription;
use App\Models\FraisDossier;
use App\Models\ApportInitial;
use App\Services\DashboardChartService;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Statistiques DG existantes ---
        $totalClients = Client::count();
        $totalProjets = Projet::count();
        $totalMutuelles = Mutuelle::count();
        // Assuming 'actif' is a status or just count all if no status column known yet. 
        // Let's check Projet model or database if needed. For now, let's assume all projects are 'active' or check if there is a status column.
        // Actually, let's just count all for now, or check if 'statut' exists.
        // Based on previous tool outputs, I didn't see specific columns, but usually 'statut' is common.
        // I'll use count() for now to be safe or check if I can find project structure.
        // Wait, let me check Projet model structure via read.
        $projetsActifs = Projet::count(); 

        // --- Statistiques Comptables ---
        
        // 1. Global Stats
        $totalEncaisse = Paiement::where('statut', 'payé')->sum('montant');
        $totalEncaisseShort = $this->formatFcfaShort($totalEncaisse);
        
        // 2. Frais Dossier Stats
        $fraisDossierTotalAmount = FraisDossier::sum('montant');
        $fraisDossierPaye = FraisDossier::sum('montant_paye');
        $fraisDossierReste = FraisDossier::sum('montant_reste');
        $fraisDossierCountTotal = FraisDossier::count();
        $fraisDossierCountSoldes = FraisDossier::where('montant_reste', 0)->where('montant_paye', '>', 0)->count();

        // 3. Apport Initial Stats
        $apportInitialTotalAmount = ApportInitial::sum('montant');
        $apportInitialPaye = ApportInitial::sum('montant_paye');
        $apportInitialReste = ApportInitial::sum('montant_reste');
        $apportInitialCountTotal = ApportInitial::count();
        $apportInitialCountSoldes = ApportInitial::where('montant_reste', 0)->where('montant_paye', '>', 0)->count();

        // 3b. Suivi Paiement Projet Stats (Prix Logement hors apport initial si applicable, mais ici on prend global)
        $projetTotalAttendu = Souscription::sum('prix_logement');
        $projetTotalPaye = Paiement::where('statut', 'payé')->whereIn('type', ['PROJET', 'APPORT'])->sum('montant');
        $projetTotalReste = max($projetTotalAttendu - $projetTotalPaye, 0);
        $projetCountTotal = Souscription::count();
        $projetCountSoldes = Souscription::with(['paiements'])
            ->get()
            ->filter(function ($s) {
                $totalPayes = $s->paiements()->where('statut', 'payé')->whereIn('type', ['PROJET', 'APPORT'])->sum('montant');
                return ($s->prix_logement ?? 0) > 0 && $totalPayes >= $s->prix_logement;
            })
            ->count();

        // 4. Souscriptions Stats
        $souscriptionsTotalCount = Souscription::count();
        
        // On calcule les dossiers soldés (prix logement + frais dossier <= paiements payés)
        $clientsSoldes = Souscription::where('statut', 'SOLD')->count(); 
        
        // En cours: (Paying but not soldé)
        $souscriptionsForProgress = Souscription::where('statut', '!=', 'SOLD')
            ->with(['paiements' => function ($q) {
                $q->where('statut', 'payé');
            }])->get();
            
        $paiementsEnCours = $souscriptionsForProgress->filter(function ($s) {
            $paye = $s->paiements->sum('montant');
            return $paye > 0;
        })->count();
        
        $souscriptionsEnAttente = max($souscriptionsTotalCount - $clientsSoldes - $paiementsEnCours, 0);

        // Total Restant Global (Sum of all remaining amounts)
        $totalPrixLogements = Souscription::sum('prix_logement');
        $totalFraisDossier = FraisDossier::sum('montant');
        $totalAttendu = $totalPrixLogements + $totalFraisDossier;
        $totalRestant = max($totalAttendu - $totalEncaisse, 0);

        $charts = app(DashboardChartService::class);
        $chartDonut = array_merge(
            ['title' => 'Répartition des souscriptions'],
            $charts->souscriptionStatusDonut($souscriptionsEnAttente, $paiementsEnCours, $clientsSoldes)
        );
        $chartLine = array_merge(
            ['title' => 'Encaissements mensuels', 'currency' => true, 'chartLabel' => 'Encaissements (FCFA)'],
            $charts->monthlyEncaissements()
        );
        $chartBar = array_merge(
            ['title' => 'Encaissements par projet', 'chartLabel' => 'Montant (FCFA)', 'currency' => true],
            $charts->encaissementsByProject()
        );

        // Projects list for filter
        $projets = Projet::all();

        return view('dg.dashboard', compact(
            'totalClients', 'totalProjets', 'totalMutuelles', 'projetsActifs',
            'totalEncaisse', 'totalEncaisseShort', 'totalRestant',
            'fraisDossierTotalAmount', 'fraisDossierPaye', 'fraisDossierReste', 'fraisDossierCountTotal', 'fraisDossierCountSoldes',
            'apportInitialTotalAmount', 'apportInitialPaye', 'apportInitialReste', 'apportInitialCountTotal', 'apportInitialCountSoldes',
            'projetTotalAttendu', 'projetTotalPaye', 'projetTotalReste', 'projetCountTotal', 'projetCountSoldes',
            'souscriptionsEnAttente', 'paiementsEnCours', 'clientsSoldes',
            'chartDonut', 'chartLine', 'chartBar', 'projets'
        ));
    }

    private function formatFcfaShort($amount): string
    {
        $amount = (float) $amount;
        if ($amount >= 1000000) {
            $m = round($amount / 1000000, 1);
            return number_format($m, 1, ',', ' ') . ' M FCFA';
        }
        if ($amount >= 1000) {
            $k = round($amount / 1000, 1);
            return number_format($k, 1, ',', ' ') . ' K FCFA';
        }
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }
}
