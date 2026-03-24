<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function suiviPaiementsProjet(Request $request)
    {
        $query = Souscription::with(['client', 'projet', 'paiements']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ref_souscription', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('nom_prenom', 'like', "%{$search}%")
                            ->orWhere('ref_client', 'like', "%{$search}%");
                    })
                    ->orWhereHas('projet', function ($q) use ($search) {
                        $q->where('nom', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('projet')) {
            $query->where('programme', $request->projet);
        }

        if ($request->filled('mode_paiement')) {
            $query->where('mode_paiement', $request->mode_paiement);
        }

        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        if ($request->filled('statut')) {
            switch ($request->statut) {
                case 'en_attente':
                    $query->whereHas('paiements', function ($q) {
                        $q->where('statut', 'en_attente');
                    });
                    break;
                case 'en_cours':
                    $query->whereHas('paiements', function ($q) {
                        $q->where('statut', 'payé');
                    })->whereColumn('prix_logement', '>', DB::raw('(select coalesce(sum(montant),0) from paiements where paiements.dossier_id = souscriptions.id and paiements.statut = "payé")'));
                    break;
                case 'regle':
                case 'payé':
                    $query->whereHas('paiements', function ($q) {
                        $q->where('statut', 'payé');
                    })->whereColumn('prix_logement', '<=', DB::raw('(select coalesce(sum(montant),0) from paiements where paiements.dossier_id = souscriptions.id and paiements.statut = "payé")'));
                    break;
                case 'annule':
                case 'annulé':
                    $query->whereHas('paiements', function ($q) {
                        $q->where('statut', 'annulé');
                    });
                    break;
            }
        }

        $souscriptions = $query->latest()->paginate(20);
        $projets = \App\Models\Projet::all();

        return view('dg.suivi-paiements-projet', compact('souscriptions', 'projets'));
    }
}
