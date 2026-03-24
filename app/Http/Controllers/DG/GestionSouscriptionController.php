<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\Souscription;
use App\Models\ApportInitial;

class GestionSouscriptionController extends Controller
{
    /**
     * Liste des souscriptions en attente d'attribution de logement
     */
    public function attributionIndex()
    {
        // Récupérer toutes les souscriptions qui ne sont pas annulées
        $souscriptions = Souscription::with(['client', 'projet', 'attributionLot'])
            ->where('statut', '!=', 'annulee')
            ->get();

        return view('dg.attribution.index', compact('souscriptions'));
    }

    /**
     * Liste des souscriptions soldées à confirmer
     */
    public function confirmationIndex()
    {
        $souscriptions = Souscription::with(['client', 'projet', 'paiements', 'attributionLot'])
            ->where('statut', 'SOLD')
            ->get()
            ->filter(function ($s) {
                return $s->attributionLot;
            })
            ->map(function ($s) {
                $totalPayes = $s->paiements()->where('statut', 'payé')->sum('montant');
                $fraisTotal = \App\Models\FraisDossier::where('id_souscription', $s->id)->sum('montant');
                $s->montant_total = (float) ($s->prix_logement ?? 0) + (float) $fraisTotal;
                $s->total_paye = (float) $totalPayes;
                $s->dernier_paiement = $s->paiements()->orderByDesc('date_paiement')->first();
                return $s;
            });

        return view('dg.confirmation.index', compact('souscriptions'));
    }

    /**
     * Fiche d'attribution pour une souscription donnée.
     */
    public function attributionShow(Souscription $souscription)
    {
        $souscription->load(['client', 'projet', 'attributionLot']);
        return view('dg.attribution.show', compact('souscription'));
    }
}
