<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Souscription;
use App\Models\FraisDossier;
use App\Models\ApportInitial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComptableController extends Controller
{
    public function fraisDossier(Request $request)
    {
        $query = FraisDossier::with(['souscription.client', 'projet', 'souscription.paiements']);

        // Recherche textuelle (client, projet)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('souscription.client', function($q) use ($search) {
                        $q->where('nom_prenom', 'like', "%{$search}%")
                          ->orWhere('ref_client', 'like', "%{$search}%");
                    })
                  ->orWhereHas('projet', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par date (création de l'enregistrement)
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        // Filtre par statut (En attente, En cours, Réglé)
        if ($request->filled('statut')) {
            switch ($request->statut) {
                case 'en_attente':
                    $query->where(function($q){
                        $q->whereNull('montant_paye')->orWhere('montant_paye', '=', 0);
                    });
                    break;
                case 'en_cours':
                    $query->where('montant_reste', '>', 0)->where('montant_paye', '>', 0);
                    break;
                case 'regle':
                case 'payé':
                    $query->where(function($q){
                        $q->whereNull('montant_reste')->orWhere('montant_reste', '=', 0);
                    });
                    break;
            }
        }

        $fraisDossier = $query->latest()->paginate(20);

        return view('comptable.frais-dossier', compact('fraisDossier'));
    }

    public function apportsInitiaux(Request $request)
    {
        $query = ApportInitial::with(['souscription.client', 'projet', 'souscription.paiements']);

        // Recherche textuelle (client, projet)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('souscription.client', function($q) use ($search) {
                        $q->where('nom_prenom', 'like', "%{$search}%")
                          ->orWhere('ref_client', 'like', "%{$search}%");
                    })
                  ->orWhereHas('projet', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par date (création de l'enregistrement)
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        // Filtre par statut (En attente, En cours, Réglé)
        if ($request->filled('statut')) {
            switch ($request->statut) {
                case 'en_attente':
                    $query->where(function($q){
                        $q->whereNull('montant_paye')->orWhere('montant_paye', '=', 0);
                    });
                    break;
                case 'en_cours':
                    $query->where('montant_reste', '>', 0)->where('montant_paye', '>', 0);
                    break;
                case 'regle':
                case 'payé':
                    $query->where(function($q){
                        $q->whereNull('montant_reste')->orWhere('montant_reste', '=', 0);
                    });
                    break;
            }
        }

        $apportsInitiaux = $query->latest()->paginate(20);

        return view('comptable.apports-initiaux', compact('apportsInitiaux'));
    }

    public function suiviPaiementsProjet(Request $request)
    {
        $query = Souscription::with(['client', 'projet', 'paiements']);

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ref_souscription', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nom_prenom', 'like', "%{$search}%")
                        ->orWhere('ref_client', 'like', "%{$search}%");
                  })
                  ->orWhereHas('projet', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par projet
        if ($request->filled('projet')) {
            $query->where('programme', $request->projet);
        }

        // Filtre par mode de paiement
        if ($request->filled('mode_paiement')) {
            $query->where('mode_paiement', $request->mode_paiement);
        }

        // Filtre par date (sur la date de création)
        if ($request->filled('date_debut')) {
            $query->whereDate('created_at', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('created_at', '<=', $request->date_fin);
        }

        // Filtre par statut de paiement au niveau des souscriptions
        if ($request->filled('statut')) {
            switch ($request->statut) {
                case 'en_attente':
                    $query->whereHas('paiements', function($q){ $q->where('statut', 'en_attente'); });
                    break;
                case 'en_cours':
                    // souscriptions avec paiements payés mais pas soldées (il reste à payer)
                    $query->whereHas('paiements', function($q){ $q->where('statut', 'payé'); })
                          ->whereColumn('prix_logement', '>', DB::raw('(select coalesce(sum(montant),0) from paiements where paiements.dossier_id = souscriptions.id and paiements.statut = "payé")'));
                    break;
                case 'regle':
                case 'payé':
                    $query->whereHas('paiements', function($q){ $q->where('statut', 'payé'); })
                          ->whereColumn('prix_logement', '<=', DB::raw('(select coalesce(sum(montant),0) from paiements where paiements.dossier_id = souscriptions.id and paiements.statut = "payé")'));
                    break;
                case 'annule':
                case 'annulé':
                    $query->whereHas('paiements', function($q){ $q->where('statut', 'annulé'); });
                    break;
            }
        }

        $souscriptions = $query->latest()->paginate(20);

        // Récupérer la liste des projets pour le filtre
        $projets = \App\Models\Projet::all();

        return view('comptable.suivi-paiements-projet', compact('souscriptions', 'projets'));
    }

    public function projetsSoldes(Request $request)
    {
        $query = Paiement::with(['souscription.client', 'comptable'])
            ->whereHas('souscription', function($query) {
                $query->where('statut', 'soldé');
            });

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhereHas('souscription.client', function($q) use ($search) {
                      $q->where('nom_prenom', 'like', "%{$search}%")
                        ->orWhere('ref_client', 'like', "%{$search}%");
                  })
                  ->orWhereHas('souscription.projet', function($q) use ($search) {
                      $q->where('nom', 'like', "%{$search}%");
                  });
            });
        }

        // Filtre par projet
        if ($request->filled('projet')) {
            $query->whereHas('souscription', function($q) use ($request) {
                $q->where('programme', $request->projet);
            });
        }

        // Filtre par type de paiement
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        $projetsSoldes = $query->latest()->paginate(20);

        // Récupérer la liste des projets pour le filtre
        $projets = \App\Models\Projet::all();

        return view('comptable.projets-soldes', compact('projetsSoldes', 'projets'));
    }

    public function updateStatut(Request $request, Paiement $paiement)
    {
        $request->validate([
            'statut' => 'required|in:en_attente,payé,annulé'
        ]);

        $paiement->update([
            'statut' => $request->statut,
            'comptable_id' => auth()->id(),
            'valide_at' => $request->statut === 'payé' ? now() : null
        ]);

        return redirect()->back()->with('success', 'Statut du paiement mis à jour avec succès.');
    }

    public function validerPaiement(Request $request, Paiement $paiement)
    {
        $request->validate([
            'mode' => 'required|in:ESPECES,VIREMENT,MOBILE_MONEY,TEMPERAMENT,CREDIT_BANCAIRE',
            'preuve_paiement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'montant' => 'nullable|numeric|min:1',
            'type' => 'nullable|in:FRAIS_DOSSIER,APPORT,PROJET'
        ]);

        $preuvePath = null;
        if ($request->hasFile('preuve_paiement')) {
            $preuvePath = $request->file('preuve_paiement')->store('preuves_paiements', 'public');
        }

        $paiement->update([
            'statut' => 'payé',
            'comptable_id' => auth()->id(),
            'valide_at' => now(),
            'mode' => $request->mode,
            'preuve_paiement' => $preuvePath,
            'montant' => $request->montant ?? $paiement->montant,
            'type' => $request->type ?? $paiement->type,
        ]);

        return redirect()->back()->with('success', 'Paiement validé avec succès.');
    }

    public function annulerPaiement(Paiement $paiement)
    {
        $paiement->update([
            'statut' => 'annulé',
            'comptable_id' => auth()->id(),
            'valide_at' => null
        ]);

        return redirect()->back()->with('success', 'Paiement annulé avec succès.');
    }

    public function createPaiement(Request $request, Souscription $souscription)
    {
        $request->validate([
            'type' => 'required|in:FRAIS_DOSSIER,APPORT,PROJET',
            'montant' => 'required|numeric|min:1',
            'mode' => 'required|in:ESPECES,VIREMENT,MOBILE_MONEY,TEMPERAMENT,CREDIT_BANCAIRE',
            'date_paiement' => 'required|date',
            'preuve_paiement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        // Générer une référence unique
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

        // Contrôles spécifiques selon le type sélectionné
        if ($request->type === 'FRAIS_DOSSIER') {
            $fraisDossier = \App\Models\FraisDossier::where('id_souscription', $souscription->id)->first();
            if (!$fraisDossier) {
                return redirect()->back()->with('error', 'Frais de dossier introuvable pour cette souscription.');
            }
            if ($montant > (float) $fraisDossier->montant_reste) {
                return redirect()->back()->with('error', 'Le montant saisi dépasse le montant restant des frais de dossier.');
            }
        } elseif ($request->type === 'APPORT') {
            $apportInitial = \App\Models\ApportInitial::where('id_souscription', $souscription->id)->first();
            if (!$apportInitial) {
                return redirect()->back()->with('error', 'Apport initial introuvable pour cette souscription.');
            }
            if ($montant > (float) $apportInitial->montant_reste) {
                return redirect()->back()->with('error', "Le montant saisi dépasse le montant restant de l'apport initial.");
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $souscription, $reference, $preuvePath, $montant, $fraisDossier, $apportInitial) {
            // Créer l'enregistrement de paiement
            $paiement = \App\Models\Paiement::create([
                'dossier_id' => $souscription->id,
                'type' => $request->type,
                'montant' => $montant,
                'mode' => $request->mode,
                'reference' => $reference,
                'date_paiement' => $request->date_paiement,
                'statut' => 'payé',
                'comptable_id' => auth()->id(),
                'valide_at' => now(),
                'cree_at' => now(),
                'preuve_paiement' => $preuvePath
            ]);

            // Incrémenter les montants du modèle concerné si applicable
            if ($request->type === 'FRAIS_DOSSIER' && $fraisDossier) {
                $fraisDossier->montant_paye = (float) $fraisDossier->montant_paye + $montant;
                $fraisDossier->montant_reste = max(((float) $fraisDossier->montant - (float) $fraisDossier->montant_paye), 0);
                $fraisDossier->id_comptable = auth()->id();
                $fraisDossier->save();

                // Mise à jour du statut de la souscription si les frais de dossier sont soldés
                if ((float) $fraisDossier->montant_reste <= 0) {
                    $souscription->statut = 'FRAIS_OK';
                    $souscription->save();
                }
            } elseif ($request->type === 'APPORT' && $apportInitial) {
                $apportInitial->montant_paye = (float) $apportInitial->montant_paye + $montant;
                $apportInitial->montant_reste = max(((float) $apportInitial->montant - (float) $apportInitial->montant_paye), 0);
                $apportInitial->id_comptable = auth()->id();
                $apportInitial->save();

                // Mise à jour du statut de la souscription si l'apport initial est soldé
                if ((float) $apportInitial->montant_reste <= 0) {
                    $souscription->statut = 'APPORT_OK';
                    $souscription->save();
                }
            }
        });

        return redirect()->back()->with('success', 'Paiement créé avec succès.');
    }

    public function voirPaiementsSouscription(Request $request, Souscription $souscription)
    {
        $query = $souscription->paiements()
            ->whereIn('type', ['FRAIS_DOSSIER', 'APPORT', 'PROJET'])
            ->with('comptable');

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('montant', 'like', "%{$search}%");
            });
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        $paiements = $query->latest()->paginate(20);

        return view('comptable.paiements-souscription', compact('souscription', 'paiements'));
    }

    public function createPaymentFromSubscription(Souscription $souscription)
    {
        DB::transaction(function () use ($souscription) {
            // Créer le paiement frais de dossier
            if ($souscription->frais_souscription > 0) {
                Paiement::create([
                    'dossier_id' => $souscription->id,
                    'type' => 'frais_dossier',
                    'montant' => $souscription->frais_souscription,
                    'mode' => 'en_attente',
                    'reference' => 'FD-' . $souscription->numero_dossier,
                    'date_paiement' => now(),
                    'statut' => 'en_attente',
                    'cree_at' => now()
                ]);
            }

            // Créer le paiement apport initial
            if ($souscription->apport_initial > 0) {
                Paiement::create([
                    'dossier_id' => $souscription->id,
                    'type' => 'apport_initial',
                    'montant' => $souscription->apport_initial,
                    'mode' => 'en_attente',
                    'reference' => 'AI-' . $souscription->numero_dossier,
                    'date_paiement' => now(),
                    'statut' => 'en_attente',
                    'cree_at' => now()
                ]);
            }
        });
    }

    public function dashboard()
    {
        // Total encaissé (tous paiements validés)
        $totalEncaisse = Paiement::where('statut', 'payé')->sum('montant');
        $totalEncaisseShort = $this->formatFcfaShort($totalEncaisse);
    
        // Frais de dossier validés vs total
        $fraisDossierValides = Paiement::where('type', 'FRAIS_DOSSIER')->where('statut', 'payé')->count();
        $fraisDossierTotal = Paiement::where('type', 'FRAIS_DOSSIER')->count();
    
        // Suivi paiements projet: souscriptions avec paiements en cours (montant restant > 0 et montant payé > 0)
        $souscriptionsForProgress = Souscription::select('id', 'prix_logement')
            ->with(['paiements' => function ($q) {
                $q->where('statut', 'payé');
            }])->get();
        $paiementsEnCours = $souscriptionsForProgress->filter(function ($s) {
            $paye = $s->paiements->sum('montant');
            return $paye > 0 && ($s->prix_logement ?? 0) > $paye;
        })->count();
    
        // Clients soldés (souscriptions avec statut "soldé")
        $clientsSoldes = Souscription::where('statut', 'soldé')->count();
    
        // Données des graphiques: montant encaissé par projet
        $byProject = DB::table('paiements')
            ->join('souscriptions', 'paiements.dossier_id', '=', 'souscriptions.id')
            ->join('projets', 'souscriptions.programme', '=', 'projets.id')
            ->where('paiements.statut', '=', 'payé')
            ->select('projets.nom as projet_nom', DB::raw('SUM(paiements.montant) as total'))
            ->groupBy('projets.nom')
            ->get();
    
        $barChartLabels = $byProject->pluck('projet_nom')->toArray();
        $barChartValues = $byProject->pluck('total')->map(function ($v) { return (float) $v; })->toArray();
    
        return view('comptable.dashboard', compact(
            'totalEncaisse',
            'totalEncaisseShort',
            'fraisDossierValides',
            'fraisDossierTotal',
            'paiementsEnCours',
            'clientsSoldes',
            'barChartLabels',
            'barChartValues'
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

    public function payerFraisDossier(Request $request, FraisDossier $fraisDossier)
    {
        $request->validate([
            'montant' => ['required','numeric','min:1'],
            'mode' => 'required|in:ESPECES,VIREMENT,MOBILE_MONEY,TEMPERAMENT,CREDIT_BANCAIRE',
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

        // Mettre à jour les montants du frais de dossier
        $fraisDossier->montant_paye = (float) $fraisDossier->montant_paye + $montant;
        $fraisDossier->montant_reste = max(((float) $fraisDossier->montant - (float) $fraisDossier->montant_paye), 0);
        $fraisDossier->id_comptable = auth()->id();
        $fraisDossier->save();

        // Créer l'enregistrement de paiement avec preuve
        Paiement::create([
            'dossier_id' => $fraisDossier->souscription->id ?? $fraisDossier->id_souscription,
            'type' => 'FRAIS_DOSSIER',
            'montant' => $montant,
            'mode' => $request->mode,
            'reference' => 'FRD-' . ($fraisDossier->souscription->ref_souscription ?? $fraisDossier->id_souscription) . '-' . time(),
            'date_paiement' => now(),
            'statut' => 'payé',
            'comptable_id' => auth()->id(),
            'valide_at' => now(),
            'cree_at' => now(),
            'preuve_paiement' => $preuvePath,
        ]);

        // Mise à jour du statut de la souscription si les frais de dossier sont soldés
        if ((float) $fraisDossier->montant_reste <= 0) {
            if ($fraisDossier->souscription) {
                $fraisDossier->souscription->statut = 'FRAIS_OK';
                $fraisDossier->souscription->save();
            } else {
                \App\Models\Souscription::where('id', $fraisDossier->id_souscription)->update(['statut' => 'FRAIS_OK']);
            }
        }

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    }

    public function payerApportInitial(Request $request, ApportInitial $apportInitial)
    {
        $request->validate([
            'montant' => ['required','numeric','min:1'],
            'mode' => 'required|in:ESPECES,VIREMENT,MOBILE_MONEY,TEMPERAMENT,CREDIT_BANCAIRE',
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

        // Mettre à jour les montants de l'apport initial
        $apportInitial->montant_paye = (float) $apportInitial->montant_paye + $montant;
        $apportInitial->montant_reste = max(((float) $apportInitial->montant - (float) $apportInitial->montant_paye), 0);
        $apportInitial->id_comptable = auth()->id();
        $apportInitial->save();

        // Créer l'enregistrement de paiement avec preuve
        Paiement::create([
            'dossier_id' => $apportInitial->souscription->id ?? $apportInitial->id_souscription,
            'type' => 'APPORT',
            'montant' => $montant,
            'mode' => $request->mode,
            'reference' => 'APPORT-' . ($apportInitial->souscription->ref_souscription ?? $apportInitial->id_souscription) . '-' . time(),
            'date_paiement' => now(),
            'statut' => 'payé',
            'comptable_id' => auth()->id(),
            'valide_at' => now(),
            'cree_at' => now(),
            'preuve_paiement' => $preuvePath,
        ]);

        // Mise à jour du statut de la souscription si l'apport initial est soldé
        if ((float) $apportInitial->montant_reste <= 0) {
            if ($apportInitial->souscription) {
                $apportInitial->souscription->statut = 'APPORT_OK';
                $apportInitial->souscription->save();
            } else {
                \App\Models\Souscription::where('id', $apportInitial->id_souscription)->update(['statut' => 'APPORT_OK']);
            }
        }

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.');
    }
}