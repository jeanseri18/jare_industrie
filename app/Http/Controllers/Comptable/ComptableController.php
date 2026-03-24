<?php

namespace App\Http\Controllers\Comptable;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Souscription;
use App\Models\FraisDossier;
use App\Models\ApportInitial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // 1. Identifier et mettre à jour les souscriptions soldées
        // On le fait sur toutes les souscriptions qui ont au moins un paiement validé
        $souscriptionIdsWithPayments = Paiement::where('statut', 'payé')->distinct()->pluck('dossier_id');
        $soldIds = [];
        
        $souscriptions = \App\Models\Souscription::whereIn('id', $souscriptionIdsWithPayments)->get();
        foreach ($souscriptions as $s) {
            $this->checkAndSetSoldStatus($s);
            if ($s->statut === 'SOLD') {
                $soldIds[] = $s->id;
            }
        }

        // 2. Construire la requête pour afficher les paiements de ces dossiers soldés
        $query = Paiement::with(['souscription.client', 'comptable'])
            ->whereIn('dossier_id', $soldIds)
            ->where('statut', 'payé');

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
            'comptable_id' => Auth::id(),
            'valide_at' => $request->statut === 'payé' ? now() : null
        ]);

        return redirect()->back()->with('success', 'Statut du paiement mis à jour avec succès.');
    }

    public function validerPaiement(Request $request, Paiement $paiement)
    {
        $request->validate([
            'mode' => 'required|in:ESPECES,VIREMENT,TEMPERAMENT,CREDIT_BANCAIRE,PRELEVEMENT_SOURCE',
            'preuve_paiement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'montant' => 'nullable|numeric|min:1',
            'type' => 'nullable|in:FRAIS_DOSSIER,APPORT,PROJET'
        ]);

        $preuvePath = null;
        if ($request->hasFile('preuve_paiement')) {
            $preuvePath = $request->file('preuve_paiement')->store('preuves_paiements', 'public');
        }

        $montant = $request->montant ?? $paiement->montant;
        $type = $request->type ?? $paiement->type;
        $souscription = $paiement->souscription;

        if ($souscription) {
            if ($type === 'FRAIS_DOSSIER') {
                $frais = \App\Models\FraisDossier::where('id_souscription', $souscription->id)->first();
                if ($frais && $montant > (float) $frais->montant_reste + ($paiement->statut === 'payé' ? $paiement->montant : 0)) {
                    return redirect()->back()->with('error', "Le montant saisi dépasse le montant restant des frais de dossier.");
                }
            } elseif ($type === 'APPORT') {
                $apport = \App\Models\ApportInitial::where('id_souscription', $souscription->id)->first();
                if ($apport && $montant > (float) $apport->montant_reste + ($paiement->statut === 'payé' ? $paiement->montant : 0)) {
                    return redirect()->back()->with('error', "Le montant saisi dépasse le montant restant de l'apport initial.");
                }
            } elseif ($type === 'PROJET') {
                $totalPayeHorsFrais = \App\Models\Paiement::where('dossier_id', $souscription->id)
                    ->where('statut', 'payé')
                    ->where('id', '!=', $paiement->id)
                    ->whereIn('type', ['PROJET', 'APPORT'])
                    ->sum('montant');
                $prixLogement = (float) ($souscription->prix_logement ?? 0);
                $resteLogement = max($prixLogement - $totalPayeHorsFrais, 0);

                if ($montant > $resteLogement) {
                    return redirect()->back()->with('error', "Le montant saisi dépasse le montant restant du projet (" . number_format($resteLogement, 0, ',', ' ') . " FCFA).");
                }
            }
        }

        $paiement->update([
            'statut' => 'payé',
            'comptable_id' => Auth::id(),
            'valide_at' => now(),
            'mode' => $request->mode,
            'preuve_paiement' => $preuvePath,
            'montant' => $montant,
            'type' => $type,
        ]);

        // Déclenchement de la vérification du statut global
        if ($paiement->souscription) {
            $this->checkAndSetSoldStatus($paiement->souscription);
        }

        return redirect()->back()->with('success', 'Paiement validé avec succès.')
            ->with('receipt_url', route('comptable.paiements.recu', $paiement));
    }

    public function annulerPaiement(Paiement $paiement)
    {
        $paiement->update([
            'statut' => 'annulé',
            'comptable_id' => Auth::id(),
            'valide_at' => null
        ]);

        return redirect()->back()->with('success', 'Paiement annulé avec succès.');
    }

    public function createPaiement(Request $request, Souscription $souscription)
    {
        $request->validate([
            'type' => 'required|in:FRAIS_DOSSIER,APPORT,PROJET',
            'montant' => 'required|numeric|min:1',
            'mode' => 'required|in:ESPECES,VIREMENT,TEMPERAMENT,CREDIT_BANCAIRE,PRELEVEMENT_SOURCE',
            'date_paiement' => 'required|date',
            'preuve_paiement' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240'
        ]);

        if ($request->type === 'APPORT' && empty($souscription->apport_initial_paye_par_client)) {
            return redirect()->back()->with('error', "Apport initial non applicable : ce client ne paie pas d'apport initial.");
        }

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
            if (empty($souscription->apport_initial_paye_par_client) || (float)($souscription->apport_initial ?? 0) <= 0) {
                return redirect()->back()->with('error', "Apport initial non applicable pour cette souscription.");
            }
            $apportInitial = \App\Models\ApportInitial::where('id_souscription', $souscription->id)->first();
            if (!$apportInitial) {
                return redirect()->back()->with('error', 'Apport initial introuvable pour cette souscription.');
            }
            if ($montant > (float) $apportInitial->montant_reste) {
                return redirect()->back()->with('error', "Le montant saisi dépasse le montant restant de l'apport initial (" . number_format($apportInitial->montant_reste, 0, ',', ' ') . " FCFA).");
            }
        } elseif ($request->type === 'PROJET') {
            $totalPayeHorsFrais = \App\Models\Paiement::where('dossier_id', $souscription->id)
                ->where('statut', 'payé')
                ->whereIn('type', ['PROJET', 'APPORT'])
                ->sum('montant');
            $prixLogement = (float) ($souscription->prix_logement ?? 0);
            $resteLogement = max($prixLogement - $totalPayeHorsFrais, 0);

            if ($montant > $resteLogement) {
                return redirect()->back()->with('error', "Le montant saisi dépasse le montant restant du projet (" . number_format($resteLogement, 0, ',', ' ') . " FCFA).");
            }
        }

        $paiement = \Illuminate\Support\Facades\DB::transaction(function () use ($request, $souscription, $reference, $preuvePath, $montant, $fraisDossier, $apportInitial) {
            // Créer l'enregistrement de paiement
            $paiement = \App\Models\Paiement::create([
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
                'preuve_paiement' => $preuvePath
            ]);

            // Incrémenter les montants du modèle concerné si applicable
            if ($request->type === 'FRAIS_DOSSIER' && $fraisDossier) {
                $fraisDossier->montant_paye = (float) $fraisDossier->montant_paye + $montant;
                $fraisDossier->montant_reste = max(((float) $fraisDossier->montant - (float) $fraisDossier->montant_paye), 0);
                $fraisDossier->id_comptable = Auth::id();
                $fraisDossier->save();

                // Mise à jour du statut de la souscription si les frais de dossier sont soldés
                if ((float) $fraisDossier->montant_reste <= 0) {
                    $souscription->statut = 'FRAIS_OK';
                    $souscription->save();
                }
            } elseif ($request->type === 'APPORT' && $apportInitial) {
                $apportInitial->montant_paye = (float) $apportInitial->montant_paye + $montant;
                $apportInitial->montant_reste = max(((float) $apportInitial->montant - (float) $apportInitial->montant_paye), 0);
                $apportInitial->id_comptable = Auth::id();
                $apportInitial->save();

                // Mise à jour du statut de la souscription si l'apport initial est soldé
                if ((float) $apportInitial->montant_reste <= 0) {
                    $souscription->statut = 'APPORT_OK';
                    $souscription->save();
                }
            } elseif ($request->type === 'PROJET') {
                // Si on paye "PROJET", on considère que cela réduit aussi l'apport initial (qui fait partie du prix)
                $apport = $apportInitial ?: \App\Models\ApportInitial::where('id_souscription', $souscription->id)->first();
                if ($apport) {
                    $resteApport = max((float)$apport->montant - (float)$apport->montant_paye, 0);
                    if ($resteApport > 0) {
                        $reduction = min($montant, $resteApport);
                        $apport->montant_paye = (float)$apport->montant_paye + $reduction;
                        $apport->montant_reste = max(((float)$apport->montant - (float)$apport->montant_paye), 0);
                        $apport->id_comptable = Auth::id();
                        $apport->save();
                        if ($apport->montant_reste <= 0) {
                            $souscription->statut = 'APPORT_OK';
                            $souscription->save();
                        }
                    }
                }
            }

            // Si les paiements PROJET+APPORT soldent le prix du logement, alors l'apport restant (s'il en reste) est considéré soldé
            $totalPayeHorsFrais = \App\Models\Paiement::where('dossier_id', $souscription->id)
                ->where('statut', 'payé')
                ->whereIn('type', ['PROJET','APPORT'])
                ->sum('montant');
            $prixLogement = (float) ($souscription->prix_logement ?? 0);
            if ($prixLogement > 0 && $totalPayeHorsFrais >= $prixLogement) {
                $apport = \App\Models\ApportInitial::where('id_souscription', $souscription->id)->first();
                if ($apport && (float)$apport->montant_reste > 0) {
                    $apport->montant_paye = (float)$apport->montant;
                    $apport->montant_reste = 0;
                    $apport->id_comptable = Auth::id();
                    $apport->save();
                    $souscription->statut = 'APPORT_OK';
                    $souscription->save();
                }
            }

            // Vérification globale si TOUT est soldé (Logement + Frais)
            $this->checkAndSetSoldStatus($souscription);

            return $paiement;
        });

        return redirect()->back()->with('success', 'Paiement créé avec succès.')
            ->with('receipt_url', route('comptable.paiements.recu', $paiement));
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
            if ($souscription->apport_initial_paye_par_client && $souscription->apport_initial > 0) {
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

        // 3b. Suivi Paiement Projet Stats
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
        
        $souscriptionsEnAttente = $souscriptionsTotalCount - $clientsSoldes - $paiementsEnCours;

        // Total Restant Global (Sum of all remaining amounts)
        // We approximate Total Expected = Sum(Prix Logement) + Sum(Frais Dossier)
        // This assumes Prix Logement covers Apport Initial and regular payments.
        $totalPrixLogements = Souscription::sum('prix_logement');
        $totalFraisDossier = FraisDossier::sum('montant');
        $totalAttendu = $totalPrixLogements + $totalFraisDossier;
        $totalRestant = $totalAttendu - $totalEncaisse;

        // 5. Charts
        $byProject = DB::table('paiements')
            ->join('souscriptions', 'paiements.dossier_id', '=', 'souscriptions.id')
            ->join('projets', 'souscriptions.programme', '=', 'projets.id')
            ->where('paiements.statut', '=', 'payé')
            ->select('projets.nom as projet_nom', DB::raw('SUM(paiements.montant) as total'))
            ->groupBy('projets.nom')
            ->get();
    
        $barChartLabels = $byProject->pluck('projet_nom')->toArray();
        $barChartValues = $byProject->pluck('total')->map(function ($v) { return (float) $v; })->toArray();

        // Projects list for filter
        $projets = \App\Models\Projet::all();
    
        return view('comptable.dashboard', compact(
            'totalEncaisse', 'totalEncaisseShort', 'totalRestant',
            'fraisDossierTotalAmount', 'fraisDossierPaye', 'fraisDossierReste', 'fraisDossierCountTotal', 'fraisDossierCountSoldes',
            'apportInitialTotalAmount', 'apportInitialPaye', 'apportInitialReste', 'apportInitialCountTotal', 'apportInitialCountSoldes',
            'projetTotalAttendu', 'projetTotalPaye', 'projetTotalReste', 'projetCountTotal', 'projetCountSoldes',
            'souscriptionsEnAttente', 'paiementsEnCours', 'clientsSoldes',
            'barChartLabels', 'barChartValues', 'projets'
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

        // Mettre à jour les montants du frais de dossier
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

        // Mise à jour du statut de la souscription si les frais de dossier sont soldés
        if ((float) $fraisDossier->montant_reste <= 0) {
            $souscription = $fraisDossier->souscription ?: \App\Models\Souscription::find($fraisDossier->id_souscription);
            if ($souscription) {
                $souscription->statut = 'FRAIS_OK';
                $souscription->save();

                $this->checkAndSetSoldStatus($souscription);
            }
        }

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.')
            ->with('receipt_url', route('comptable.paiements.recu', $paiement));
    }

    public function recu(Paiement $paiement)
    {
        $paiement->load(['souscription.client', 'souscription.projet', 'comptable']);
        return view('comptable.recu', compact('paiement'));
    }

    public function editionRecus(Request $request)
    {
        $query = Paiement::with(['souscription.client', 'souscription.projet', 'comptable'])
            ->where('statut', 'payé');

        // Filtre par client
        if ($request->filled('search')) {
             $search = $request->search;
             $query->whereHas('souscription.client', function($q) use ($search) {
                $q->where('nom_prenom', 'like', "%{$search}%")
                  ->orWhere('ref_client', 'like', "%{$search}%");
            });
        }

        // Filtre par période
        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        $paiements = $query->latest('date_paiement')->paginate(20);

        return view('comptable.edition-recus', compact('paiements'));
    }

    public function dossiersAnnules(Request $request)
    {
        $query = Souscription::with(['client', 'projet', 'paiements'])
            ->where('statut', 'annulee');

        // Recherche textuelle
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ref_souscription', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('nom_prenom', 'like', "%{$search}%")
                        ->orWhere('ref_client', 'like', "%{$search}%");
                  });
            });
        }

        $dossiersAnnules = $query->latest()->paginate(20);

        return view('comptable.dossiers-annules', compact('dossiersAnnules'));
    }

    public function rembourserDossier(Request $request, Souscription $souscription)
    {
        $request->validate([
            'montant' => 'required|numeric|min:1',
            'mode' => 'required|in:ESPECES,VIREMENT,CHEQUE',
            'motif' => 'nullable|string'
        ]);

        // Vérifier que le montant à rembourser ne dépasse pas le total payé
        $totalPaye = $souscription->paiements()->where('statut', 'payé')->sum('montant');
        $totalRembourse = $souscription->paiements()->where('statut', 'rembourse')->sum('montant');
        $resteARembourser = $totalPaye - $totalRembourse;

        if ($request->montant > $resteARembourser) {
            return back()->with('error', "Le montant saisi ({$request->montant}) dépasse le montant disponible à rembourser ({$resteARembourser}).");
        }

        // Créer un enregistrement de paiement négatif ou de type remboursement
        Paiement::create([
            'dossier_id' => $souscription->id,
            'type' => 'REMBOURSEMENT',
            'montant' => $request->montant, // On peut le garder positif et utiliser le type pour distinguer
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

    public function payerApportInitial(Request $request, ApportInitial $apportInitial)
    {
        $request->validate([
            'montant' => ['required','numeric','min:1'],
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

        // Mettre à jour les montants de l'apport initial
        $apportInitial->montant_paye = (float) $apportInitial->montant_paye + $montant;
        $apportInitial->montant_reste = max(((float) $apportInitial->montant - (float) $apportInitial->montant_paye), 0);
        $apportInitial->id_comptable = Auth::id();
        $apportInitial->save();

        $paiement = Paiement::create([
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

        // Mise à jour du statut de la souscription si l'apport initial est soldé
        if ((float) $apportInitial->montant_reste <= 0) {
            $souscription = $apportInitial->souscription ?: \App\Models\Souscription::find($apportInitial->id_souscription);
            if ($souscription) {
                $souscription->statut = 'APPORT_OK';
                $souscription->save();

                $this->checkAndSetSoldStatus($souscription);
            }
        }

        return redirect()->back()->with('success', 'Paiement enregistré avec succès.')
            ->with('receipt_url', route('comptable.paiements.recu', $paiement));
    }

    public function etatVersements(Souscription $souscription)
    {
        $souscription->load(['client', 'projet', 'attributionLot', 'paiements']);
        $paiements = $souscription->paiements()->where('statut', 'payé')->orderBy('date_paiement')->get();
        return view('documents.etat_versements', compact('souscription', 'paiements'));
    }

    /**
     * Vérifie si une souscription est intégralement soldée et met à jour son statut
     * ainsi que les enregistrements de frais et d'apport si nécessaire.
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
            // 1. Mettre à jour le statut global de la souscription
            $souscription->statut = 'SOLD';
            $souscription->save();

            // 2. Solder automatiquement l'apport initial s'il ne l'est pas
            // Note: Les Frais de Dossier restent indépendants et ne sont PAS forcés à 0.
            $apport = ApportInitial::where('id_souscription', $souscription->id)->first();
            if ($apport && (float)$apport->montant_reste > 0) {
                $apport->update([
                    'montant_paye' => $apport->montant,
                    'montant_reste' => 0,
                    'id_comptable' => Auth::id() ?? $apport->id_comptable
                ]);
            }
        }
    }

    /**
     * Liste des clients (Comptabilité)
     */
    public function clientsIndex(Request $request)
    {
        $query = \App\Models\Client::latest();
        
        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom_prenom', 'like', "%{$search}%")
                  ->orWhere('ref_client', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_creation')) {
            $query->whereDate('created_at', $request->date_creation);
        }

        $clients = $query->paginate(10);
        return view('comptable.clients.index', compact('clients'));
    }

    /**
     * Détails d'un client (Comptabilité)
     */
    public function clientsShow(\App\Models\Client $client)
    {
        $client->load(['souscriptions' => function($query) {
            $query->with(['projet', 'paiements'])->latest();
        }, 'mutuelle']);
        
        return view('comptable.clients.show', compact('client'));
    }
}
