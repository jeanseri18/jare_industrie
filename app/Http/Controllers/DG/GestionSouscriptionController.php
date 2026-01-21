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
        // Récupérer les IDs des souscriptions dont l'apport initial est totalement réglé
        $souscriptionIds = ApportInitial::where('montant_reste', '<=', 0)->pluck('id_souscription');

        $souscriptions = Souscription::with(['client', 'projet'])
            ->whereIn('id', $souscriptionIds)
            ->get();

        return view('dg.attribution.index', compact('souscriptions'));
    }

    /**
     * Liste des souscriptions soldées à confirmer
     */
    public function confirmationIndex()
    {
        $souscriptions = Souscription::with(['client', 'projet', 'paiements'])
            ->where('statut', 'SOLD')
            ->get();

        return view('dg.confirmation.index', compact('souscriptions'));
    }
}