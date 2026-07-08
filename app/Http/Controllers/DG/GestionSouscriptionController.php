<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\FraisDossier;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Souscription;
use App\Models\ApportInitial;
use App\Models\ProjetLot;

class GestionSouscriptionController extends Controller
{
    /**
     * Liste des souscriptions en attente d'attribution de logement
     */
    public function attributionIndex(Request $request)
    {
        $query = Souscription::with(['client', 'projet', 'attributionLot'])
            ->where('statut', '!=', 'annulee');

        if ($request->filled('client')) {
            $client = $request->client;
            $query->whereHas('client', function ($q) use ($client) {
                $q->where('nom_prenom', 'like', "%{$client}%")
                    ->orWhere('ref_client', 'like', "%{$client}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date_fin', $request->date);
        }

        if ($request->filled('statut')) {
            if ($request->statut === 'attribue') {
                $query->whereHas('attributionLot');
            } elseif ($request->statut === 'non_attribue') {
                $query->whereDoesntHave('attributionLot');
            }
        }

        $souscriptions = $query->latest()->paginate(config('pagination.per_page'))->withQueryString();

        return view('dg.attribution.index', compact('souscriptions'));
    }

    /**
     * Liste des souscriptions soldées à confirmer
     */
    public function confirmationIndex(Request $request)
    {
        $query = Souscription::with(['client', 'projet', 'paiements', 'attributionLot'])
            ->where('statut', 'SOLD')
            ->whereHas('attributionLot');

        if ($request->filled('client')) {
            $client = $request->client;
            $query->whereHas('client', function ($q) use ($client) {
                $q->where('nom_prenom', 'like', "%{$client}%")
                    ->orWhere('ref_client', 'like', "%{$client}%");
            });
        }

        if ($request->filled('date')) {
            $date = $request->date;
            $query->whereHas('paiements', function ($q) use ($date) {
                $q->whereDate('date_paiement', $date);
            });
        }

        if ($request->filled('statut')) {
            if ($request->statut === 'a_confirmer') {
                $query->whereDoesntHave('validationFinale');
            } elseif ($request->statut === 'lettre_generee') {
                $query->whereHas('validationFinale');
            }
        }

        $souscriptions = $query->latest()->paginate(config('pagination.per_page'))->withQueryString();
        $souscriptions->getCollection()->transform(function ($s) {
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
        $souscription->load(['client', 'projet', 'attributionLot', 'bienImmobilier']);
        $lotsDisponibles = ProjetLot::disponiblesPourSouscription($souscription)->get();

        return view('dg.attribution.show', compact('souscription', 'lotsDisponibles'));
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

        $souscriptions = $query->latest()->paginate(config('pagination.per_page'))->withQueryString();
        $projets = \App\Models\Projet::all();

        return view('dg.suivi-paiements-projet', compact('souscriptions', 'projets'));
    }

    public function createPaiement(Request $request, Souscription $souscription)
    {
        $request->validate([
            'type' => 'required|in:FRAIS_DOSSIER,APPORT,PROJET',
            'montant' => 'required|numeric|min:1',
            'mode' => 'required|in:ESPECES,VIREMENT,TEMPERAMENT,CREDIT_BANCAIRE,PRELEVEMENT_SOURCE',
            'date_paiement' => 'required|date',
            'preuve_paiement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        if ($request->type === 'APPORT' && empty($souscription->apport_initial_paye_par_client)) {
            return redirect()->back()->with('error', "Apport initial non applicable : ce client ne paie pas d'apport initial.");
        }

        $prefixes = [
            'FRAIS_DOSSIER' => 'FRD',
            'APPORT' => 'APPORT',
            'PROJET' => 'PROJ',
        ];
        $reference = ($prefixes[$request->type] ?? 'PAY') . '-' . $souscription->ref_souscription . '-' . time();

        $preuvePath = null;
        if ($request->hasFile('preuve_paiement')) {
            $preuvePath = $request->file('preuve_paiement')->store('preuves_paiements', 'public');
        }

        $montant = (float) $request->montant;
        $fraisDossier = null;
        $apportInitial = null;

        if ($request->type === 'FRAIS_DOSSIER') {
            $fraisDossier = FraisDossier::where('id_souscription', $souscription->id)->first();
            if (!$fraisDossier) {
                return redirect()->back()->with('error', 'Frais de dossier introuvable pour cette souscription.');
            }
            if ($montant > (float) $fraisDossier->montant_reste) {
                return redirect()->back()->with('error', 'Le montant saisi dépasse le montant restant des frais de dossier.');
            }
        } elseif ($request->type === 'APPORT') {
            if (empty($souscription->apport_initial_paye_par_client) || (float) ($souscription->apport_initial ?? 0) <= 0) {
                return redirect()->back()->with('error', "Apport initial non applicable pour cette souscription.");
            }
            $apportInitial = ApportInitial::where('id_souscription', $souscription->id)->first();
            if (!$apportInitial) {
                return redirect()->back()->with('error', 'Apport initial introuvable pour cette souscription.');
            }
            if ($montant > (float) $apportInitial->montant_reste) {
                return redirect()->back()->with('error', "Le montant saisi dépasse le montant restant de l'apport initial (" . number_format($apportInitial->montant_reste, 0, ',', ' ') . " FCFA).");
            }
        } elseif ($request->type === 'PROJET') {
            $totalPayeHorsFrais = Paiement::where('dossier_id', $souscription->id)
                ->where('statut', 'payé')
                ->whereIn('type', ['PROJET', 'APPORT'])
                ->sum('montant');
            $prixLogement = (float) ($souscription->prix_logement ?? 0);
            $resteLogement = max($prixLogement - $totalPayeHorsFrais, 0);

            if ($montant > $resteLogement) {
                return redirect()->back()->with('error', "Le montant saisi dépasse le montant restant du projet (" . number_format($resteLogement, 0, ',', ' ') . " FCFA).");
            }
        }

        DB::transaction(function () use ($request, $souscription, $reference, $preuvePath, $montant, $fraisDossier, $apportInitial) {
            $paiement = Paiement::create([
                'dossier_id' => $souscription->id,
                'type' => $request->type,
                'montant' => $montant,
                'mode' => $request->mode,
                'reference' => $reference,
                'date_paiement' => $request->date_paiement,
                'statut' => 'payé',
                'comptable_id' => Auth::id(),
                'valide_at' => now(),
                'cree_at' => now(),
                'preuve_paiement' => $preuvePath,
            ]);

            if ($request->type === 'FRAIS_DOSSIER' && $fraisDossier) {
                $fraisDossier->montant_paye = (float) $fraisDossier->montant_paye + $montant;
                $fraisDossier->montant_reste = max(((float) $fraisDossier->montant - (float) $fraisDossier->montant_paye), 0);
                $fraisDossier->id_comptable = Auth::id();
                $fraisDossier->save();

                if ((float) $fraisDossier->montant_reste <= 0) {
                    $souscription->statut = 'FRAIS_OK';
                    $souscription->save();
                }
            } elseif ($request->type === 'APPORT' && $apportInitial) {
                $apportInitial->montant_paye = (float) $apportInitial->montant_paye + $montant;
                $apportInitial->montant_reste = max(((float) $apportInitial->montant - (float) $apportInitial->montant_paye), 0);
                $apportInitial->id_comptable = Auth::id();
                $apportInitial->save();

                if ((float) $apportInitial->montant_reste <= 0) {
                    $souscription->statut = 'APPORT_OK';
                    $souscription->save();
                }
            } elseif ($request->type === 'PROJET') {
                $apport = $apportInitial ?: ApportInitial::where('id_souscription', $souscription->id)->first();
                if ($apport) {
                    $resteApport = max((float) $apport->montant - (float) $apport->montant_paye, 0);
                    if ($resteApport > 0) {
                        $reduction = min($montant, $resteApport);
                        $apport->montant_paye = (float) $apport->montant_paye + $reduction;
                        $apport->montant_reste = max(((float) $apport->montant - (float) $apport->montant_paye), 0);
                        $apport->id_comptable = Auth::id();
                        $apport->save();
                        if ((float) $apport->montant_reste <= 0) {
                            $souscription->statut = 'APPORT_OK';
                            $souscription->save();
                        }
                    }
                }
            }
        });

        $this->checkAndSetSoldStatus($souscription);

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    }

    public function fraisDossier(Request $request)
    {
        $query = FraisDossier::with(['souscription.client', 'projet', 'souscription.paiements']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('souscription.client', function ($q) use ($search) {
                    $q->where('nom_prenom', 'like', "%{$search}%")
                        ->orWhere('ref_client', 'like', "%{$search}%");
                })->orWhereHas('projet', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%");
                });
            });
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
                    $query->where(function ($q) {
                        $q->whereNull('montant_paye')->orWhere('montant_paye', '=', 0);
                    });
                    break;
                case 'en_cours':
                    $query->where('montant_reste', '>', 0)->where('montant_paye', '>', 0);
                    break;
                case 'regle':
                case 'payé':
                    $query->where(function ($q) {
                        $q->whereNull('montant_reste')->orWhere('montant_reste', '=', 0);
                    });
                    break;
            }
        }

        $fraisDossier = $query->latest()->paginate(config('pagination.per_page'))->withQueryString();
        return view('dg.frais-dossier', compact('fraisDossier'));
    }

    public function apportsInitiaux(Request $request)
    {
        $query = ApportInitial::with(['souscription.client', 'projet', 'souscription.paiements']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('souscription.client', function ($q) use ($search) {
                    $q->where('nom_prenom', 'like', "%{$search}%")
                        ->orWhere('ref_client', 'like', "%{$search}%");
                })->orWhereHas('projet', function ($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%");
                });
            });
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
                    $query->where(function ($q) {
                        $q->whereNull('montant_paye')->orWhere('montant_paye', '=', 0);
                    });
                    break;
                case 'en_cours':
                    $query->where('montant_reste', '>', 0)->where('montant_paye', '>', 0);
                    break;
                case 'regle':
                case 'payé':
                    $query->where(function ($q) {
                        $q->whereNull('montant_reste')->orWhere('montant_reste', '=', 0);
                    });
                    break;
            }
        }

        $apportsInitiaux = $query->latest()->paginate(config('pagination.per_page'))->withQueryString();
        return view('dg.apports-initiaux', compact('apportsInitiaux'));
    }

    public function payerFraisDossier(Request $request, FraisDossier $fraisDossier)
    {
        $request->validate([
            'montant' => ['required', 'numeric', 'min:1'],
            'mode' => 'required|in:ESPECES,VIREMENT,TEMPERAMENT,CREDIT_BANCAIRE,PRELEVEMENT_SOURCE',
            'preuve_paiement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $montant = (float) $request->montant;
        if ($montant > (float) $fraisDossier->montant_reste) {
            return redirect()->back()->with('error', 'Le montant saisi dépasse le montant restant.');
        }

        $preuvePath = null;
        if ($request->hasFile('preuve_paiement')) {
            $preuvePath = $request->file('preuve_paiement')->store('preuves_paiements', 'public');
        }

        $fraisDossier->montant_paye = (float) $fraisDossier->montant_paye + $montant;
        $fraisDossier->montant_reste = max(((float) $fraisDossier->montant - (float) $fraisDossier->montant_paye), 0);
        $fraisDossier->id_comptable = Auth::id();
        $fraisDossier->save();

        $paiement = Paiement::create([
            'dossier_id' => $fraisDossier->souscription->id ?? $fraisDossier->id_souscription,
            'type' => 'FRAIS_DOSSIER',
            'montant' => $montant,
            'mode' => $request->mode,
            'reference' => 'FRD-' . ($fraisDossier->souscription->ref_souscription ?? $fraisDossier->id_souscription) . '-' . time(),
            'date_paiement' => now(),
            'statut' => 'payé',
            'comptable_id' => Auth::id(),
            'valide_at' => now(),
            'cree_at' => now(),
            'preuve_paiement' => $preuvePath,
        ]);

        if ((float) $fraisDossier->montant_reste <= 0) {
            $souscription = $fraisDossier->souscription ?: Souscription::find($fraisDossier->id_souscription);
            if ($souscription) {
                $souscription->statut = 'FRAIS_OK';
                $souscription->save();
                $this->checkAndSetSoldStatus($souscription);
            }
        }

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    }

    public function payerApportInitial(Request $request, ApportInitial $apportInitial)
    {
        $request->validate([
            'montant' => ['required', 'numeric', 'min:1'],
            'mode' => 'required|in:ESPECES,VIREMENT,TEMPERAMENT,CREDIT_BANCAIRE,PRELEVEMENT_SOURCE',
            'preuve_paiement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $montant = (float) $request->montant;
        if ($montant > (float) $apportInitial->montant_reste) {
            return redirect()->back()->with('error', 'Le montant saisi dépasse le montant restant.');
        }

        $preuvePath = null;
        if ($request->hasFile('preuve_paiement')) {
            $preuvePath = $request->file('preuve_paiement')->store('preuves_paiements', 'public');
        }

        $apportInitial->montant_paye = (float) $apportInitial->montant_paye + $montant;
        $apportInitial->montant_reste = max(((float) $apportInitial->montant - (float) $apportInitial->montant_paye), 0);
        $apportInitial->id_comptable = Auth::id();
        $apportInitial->save();

        Paiement::create([
            'dossier_id' => $apportInitial->souscription->id ?? $apportInitial->id_souscription,
            'type' => 'APPORT',
            'montant' => $montant,
            'mode' => $request->mode,
            'reference' => 'APPORT-' . ($apportInitial->souscription->ref_souscription ?? $apportInitial->id_souscription) . '-' . time(),
            'date_paiement' => now(),
            'statut' => 'payé',
            'comptable_id' => Auth::id(),
            'valide_at' => now(),
            'cree_at' => now(),
            'preuve_paiement' => $preuvePath,
        ]);

        if ((float) $apportInitial->montant_reste <= 0) {
            $souscription = $apportInitial->souscription ?: Souscription::find($apportInitial->id_souscription);
            if ($souscription) {
                $souscription->statut = 'APPORT_OK';
                $souscription->save();
                $this->checkAndSetSoldStatus($souscription);
            }
        }

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    }

    public function dossiersAnnules(Request $request)
    {
        $query = Souscription::with(['client', 'projet', 'paiements'])
            ->where('statut', 'annulee');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ref_souscription', 'like', "%{$search}%")
                    ->orWhereHas('client', function ($q) use ($search) {
                        $q->where('nom_prenom', 'like', "%{$search}%")
                            ->orWhere('ref_client', 'like', "%{$search}%");
                    });
            });
        }

        $dossiersAnnules = $query->latest()->paginate(config('pagination.per_page'))->withQueryString();
        return view('dg.dossiers-annules', compact('dossiersAnnules'));
    }

    public function rembourserDossier(Request $request, Souscription $souscription)
    {
        $request->validate([
            'montant' => 'required|numeric|min:1',
            'mode' => 'required|in:ESPECES,VIREMENT,CHEQUE',
            'motif' => 'nullable|string',
        ]);

        $totalPaye = $souscription->paiements()->where('statut', 'payé')->sum('montant');
        $totalRembourse = $souscription->paiements()->where('statut', 'rembourse')->sum('montant');
        $resteARembourser = $totalPaye - $totalRembourse;

        if ((float) $request->montant > (float) $resteARembourser) {
            return back()->with('error', "Le montant saisi ({$request->montant}) dépasse le montant disponible à rembourser ({$resteARembourser}).");
        }

        Paiement::create([
            'dossier_id' => $souscription->id,
            'type' => 'REMBOURSEMENT',
            'montant' => $request->montant,
            'mode' => $request->mode,
            'reference' => 'REMB-' . $souscription->ref_souscription . '-' . time(),
            'date_paiement' => now(),
            'statut' => 'rembourse',
            'comptable_id' => Auth::id(),
            'valide_at' => now(),
            'cree_at' => now(),
        ]);

        return back()->with('success', 'Remboursement enregistré avec succès.');
    }

    /**
     * Vérifie si une souscription est intégralement soldée et met à jour son statut.
     */
    private function checkAndSetSoldStatus(Souscription $souscription): void
    {
        $totalPayesGlobal = Paiement::where('dossier_id', $souscription->id)
            ->where('statut', 'payé')
            ->sum('montant');

        $fraisTotal = FraisDossier::where('id_souscription', $souscription->id)->sum('montant');
        $prixLogement = (float) ($souscription->prix_logement ?? 0);
        $duGlobal = $prixLogement + (float) $fraisTotal;

        if ($duGlobal > 0 && $totalPayesGlobal >= $duGlobal) {
            $souscription->statut = 'SOLD';
            $souscription->save();

            $apport = ApportInitial::where('id_souscription', $souscription->id)->first();
            if ($apport && (float) $apport->montant_reste > 0) {
                $apport->update([
                    'montant_paye' => $apport->montant,
                    'montant_reste' => 0,
                    'id_comptable' => Auth::id() ?? $apport->id_comptable,
                ]);
            }
        }
    }
}
