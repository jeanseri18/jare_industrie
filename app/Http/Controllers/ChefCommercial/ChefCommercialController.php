<?php

namespace App\Http\Controllers\ChefCommercial;

use App\Http\Controllers\Controller;
use App\Models\Souscription;
use App\Models\Client;
use App\Models\Projet;
use App\Models\User;
use App\Models\Mutuelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChefCommercialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le formulaire de création de souscription (identique à l'opérateur)
     */
    public function create()
    {
        $projets = Projet::where('est_actif', true)->get();
        $biensImmobiliers = [];
        $mutuelles = Mutuelle::where('est_active', true)->get();
        
        foreach ($projets as $projet) {
            $biensImmobiliers[$projet->id] = \App\Models\BienImmobilier::where('idprojet', $projet->id)
                ->with(['mutuelles' => function($q){ $q->select('mutuelles.id','mutuelles.nom'); }])
                ->get();
        }
        
        return view('chef_commercial.souscriptions.create', compact('projets', 'biensImmobiliers', 'mutuelles'));
    }

    /**
     * Enregistrer une souscription (même logique que l'opérateur)
     */
    public function store(Request $request)
    {
        // Nettoyer les montants (supprimer les espaces)
        $cleanAmount = function($value) {
            return str_replace(' ', '', $value);
        };

        $request->merge([
            'salary' => $cleanAmount($request->salary),
            'valeur_souscription' => $cleanAmount($request->valeur_souscription),
            'apport_initial' => $cleanAmount($request->apport_initial),
            'frais_souscription' => $cleanAmount($request->frais_souscription),
        ]);

        $request->validate([
            'clientCategory' => 'required|string|in:Client individuel,Association,Syndicat,Mutuelle,Association Syndicat Mutuelle,Client diaspora',
            'organisation_type' => 'required_if:clientCategory,Association Syndicat Mutuelle|nullable|string|in:Association,Syndicat,Mutuelle',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'birthDate' => 'required|date',
            'birthPlace' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'children' => 'required|integer|min:0',
            'heirs' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'salary' => 'required|numeric|min:0',
            'maritalStatus' => 'required|string|in:Célibataire,Divorcé(e),Marié(e),Veuf(ve),Concubinage',
            'nomConjoint' => 'required_if:maritalStatus,Marié(e)|nullable|string|max:255',
            'telephoneConjoint' => 'required_if:maritalStatus,Marié(e)|nullable|string|max:255',
            'idType' => 'required|string|in:CNI,Passeport,Carte consulaire',
            'idNumber' => 'required|string|max:255',
            'idFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'mutuelle_id' => 'required_if:organisation_type,Mutuelle|nullable|integer|exists:mutuelles,id',
            'program' => 'required|exists:projets,id',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'housingType' => ['required', 'string', function($attribute, $value, $fail) {
                if (!preg_match('/^[0-9]+\|.+$/', $value)) {
                    $fail('Le format du type de logement est invalide.');
                }
            }],
            'paymentMode' => 'required|string',
            'valeur_souscription' => 'required|numeric|min:0',
            'apport_initial' => 'required|numeric|min:0',
            'apport_initial_paye_par_client' => 'nullable|boolean',
            'frais_souscription' => 'required|numeric|min:0',
        ]);

        // Mapper les valeurs
        $situationMatrimonialeMap = [
            'Célibataire' => 'celibataire',
            'Divorcé(e)' => 'divorce',
            'Marié(e)' => 'marie',
            'Veuf(ve)' => 'veuf',
            'Concubinage' => 'concubinage'
        ];

        $naturePieceMap = [
            'CNI' => 'cni',
            'Passeport' => 'passeport',
            'Carte consulaire' => 'carte_consulaire'
        ];

        $categorieClientMap = [
            'Client individuel' => 'individuel',
            'Association' => 'association',
            'Syndicat' => 'syndicat',
            'Mutuelle' => 'mutuelle',
            'Client diaspora' => 'diaspora'
        ];

        $effectiveCategory = $request->clientCategory;
        if ($request->clientCategory === 'Association Syndicat Mutuelle') {
            $effectiveCategory = $request->organisation_type;
        }
        $categorieClient = $categorieClientMap[$effectiveCategory] ?? 'individuel';

        // Vérifier si le client existe déjà par email
        $client = Client::where('email', $request->email)->first();
        
        if (!$client) {
            $client = new Client();
            $client->nom = $request->nom;
            $client->prenom = $request->prenom;
            $client->nom_prenom = $request->nom . ' ' . $request->prenom;
            $client->email = $request->email;
            $client->telephone = $request->phone;
            $client->date_naissance = $request->birthDate;
            $client->lieu_naissance = $request->birthPlace;
            $client->nationalite = $request->nationality;
            $client->nombre_enfants = $request->children;
            $client->ayant_droit = $request->heirs;
            $client->salaire_mensuel = $request->salary;
            $client->situation_matrimoniale = $situationMatrimonialeMap[$request->maritalStatus] ?? 'celibataire';
            if ($request->maritalStatus === 'Marié(e)') {
                $client->nom_conjoint = $request->nomConjoint;
                $client->telephone_conjoint = $request->telephoneConjoint;
            }
            $client->nature_piece = $naturePieceMap[$request->idType] ?? 'cni';
            $client->numero_piece = $request->idNumber;
            $client->categorie_client = $categorieClient;
            $client->mutuelle_id = $categorieClient === 'mutuelle' ? $request->mutuelle_id : null;

            // Générer la référence client
            $lastClient = Client::orderBy('id', 'desc')->first();
            $nextNumber = $lastClient ? $lastClient->id + 1 : 1;
            $client->ref_client = 'CLI-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
            
            $client->save();
        }

        // Fichier d'identification
        if ($request->hasFile('idFile')) {
            $file = $request->file('idFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('identifications', $filename, 'public');
            $client->fichier_piece = $path;
            $client->save();
        }
        
        $client->categorie_client = $categorieClient;
        $client->mutuelle_id = $categorieClient === 'mutuelle' ? $request->mutuelle_id : null;
        $client->save();

        // Créer la souscription
        $souscription = new Souscription();
        $souscription->operateur_id = Auth::id();
        $souscription->client_id = $client->id;
        $souscription->categorie_client = $categorieClient;
        $souscription->nom = $request->nom;
        $souscription->prenom = $request->prenom;
        $souscription->nom_prenom = $request->nom . ' ' . $request->prenom;
        $souscription->date_naissance = $request->birthDate;
        $souscription->lieu_naissance = $request->birthPlace;
        $souscription->nationalite = $request->nationality;
        $souscription->nombre_enfants = $request->children;
        $souscription->ayant_droit = $request->heirs;
        $souscription->email = $request->email;
        $souscription->telephone = $request->phone;
        $souscription->salaire_mensuel = $request->salary;
        $souscription->situation_matrimoniale = $situationMatrimonialeMap[$request->maritalStatus] ?? 'celibataire';
        if ($request->maritalStatus === 'Marié(e)') {
            $souscription->nom_conjoint = $request->nomConjoint;
            $souscription->telephone_conjoint = $request->telephoneConjoint;
        }
        $souscription->nature_piece = $naturePieceMap[$request->idType] ?? 'cni';
        $souscription->numero_piece = $request->idNumber;
        if ($request->hasFile('idFile')) {
            $souscription->fichier_piece = $path ?? null;
        }
        $souscription->programme = $request->program;
        $souscription->date_debut = $request->startDate;
        $souscription->date_fin = $request->endDate;
        
        // Durée du contrat en mois
        $dateDebut = \Carbon\Carbon::parse($request->startDate);
        $dateFin = \Carbon\Carbon::parse($request->endDate);
        $souscription->duree_contrat_mois = $dateDebut->diffInMonths($dateFin);
        
        // Type de logement (format "id|nom")
        $housingTypeParts = explode('|', $request->housingType);
        $valeurSouscription = (float) $request->valeur_souscription;
        $fraisSouscription = (float) $request->frais_souscription;
        $apportInitialCalc = (float) $request->apport_initial;
        if (count($housingTypeParts) === 2) {
            [$bienId, $bienNom] = $housingTypeParts;
            $souscription->bien_immobilier_id = $bienId;
            $bienImmobilier = \App\Models\BienImmobilier::with('mutuelles')->find($bienId);
            $souscription->type_logement = $bienImmobilier ? $bienImmobilier->titre : $bienNom;
            if ($bienImmobilier) {
                $valeurSouscription = (float) $bienImmobilier->prix;
                if (strtolower((string) $request->organisation_type) === 'mutuelle' && $request->filled('mutuelle_id')) {
                    $mutuelle = $bienImmobilier->mutuelles()->where('mutuelle_id', $request->mutuelle_id)->first();
                    if ($mutuelle && $mutuelle->pivot && !empty($mutuelle->pivot->prix_special)) {
                        $valeurSouscription = (float) $mutuelle->pivot->prix_special;
                    }
                }
                $fraisSouscription = (float) ($bienImmobilier->frais_souscription ?? $fraisSouscription);
                $pct = (float) ($bienImmobilier->pourcentage_apport ?? 10);
                $apportInitialCalc = (float) ($bienImmobilier->apport_initial ?: round($valeurSouscription * ($pct / 100)));
            }
            $souscription->prix_logement = $valeurSouscription;
        } else {
            $souscription->type_logement = $request->housingType;
            $souscription->bien_immobilier_id = null;
            $souscription->prix_logement = $valeurSouscription;
        }
        
        $souscription->mode_paiement = $request->paymentMode;
        $souscription->valeur_souscription = $valeurSouscription;
        $apportPaye = (bool)($request->input('apport_initial_paye_par_client', 1));
        $souscription->apport_initial_paye_par_client = $apportPaye;
        $souscription->apport_initial = $apportPaye ? $apportInitialCalc : 0;
        $souscription->frais_souscription = $fraisSouscription;
        $souscription->statut = 'en_attente';
        
        // Référence souscription
        $lastSouscription = Souscription::orderBy('id', 'desc')->first();
        $nextNumber = $lastSouscription ? $lastSouscription->id + 1 : 1;
        $souscription->ref_souscription = 'SOUS-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        $souscription->save();

        // Générer les paiements (frais dossier + apport initial)
        $this->createPaymentFromSubscription($souscription);

        $ficheUrl = route('chef_commercial.souscriptions.fiche-souscription', $souscription);
        $sendUrl = route('chef_commercial.souscriptions.fiche-souscription.send', $souscription);
        return redirect()->route('chef_commercial.souscriptions.create')
            ->with('success', 'Souscription créée avec succès et soumise pour validation.')
            ->with('fiche_souscription_url', $ficheUrl)
            ->with('fiche_souscription_send_url', $sendUrl);
    }

    /**
     * Afficher le tableau de bord du chef commercial
     */
    public function dashboard(Request $request)
    {
        $baseQuery = Souscription::query()
            ->whereHas('operateur', function ($q) {
                $q->where('role', User::ROLE_OPERATEUR);
            });

        // Statistiques des souscriptions
        $totalSouscriptions = (clone $baseQuery)->count();
        $souscriptionsCorrigees = (clone $baseQuery)->where('statut_correction', 'corrige')->count();
        
        // Calcul du taux d'erreurs (souscriptions en attente de correction / total)
        $souscriptionsNonCorrigees = (clone $baseQuery)
            ->where(function ($q) {
                $q->whereNull('statut_correction')
                    ->orWhere('statut_correction', 'pas_corrige');
            })
            ->count();
        $tauxErreurs = $totalSouscriptions > 0 ? round(($souscriptionsNonCorrigees / $totalSouscriptions) * 100, 1) : 0;
        
        $correctionsQuery = Souscription::with(['operateur', 'client', 'projet'])
            ->whereHas('operateur', function ($q) {
                $q->where('role', User::ROLE_OPERATEUR);
            })
            ->where(function ($q) {
                $q->whereNull('statut_correction')
                    ->orWhereIn('statut_correction', ['pas_corrige', 'corrige']);
            })
            ->orderBy('updated_at', 'desc');

        $this->applySouscriptionFilters($request, $correctionsQuery);

        $correctionsRecentes = $correctionsQuery->paginate(10)->withQueryString();
        
        return view('chef_commercial.dashboard', compact(
            'souscriptionsCorrigees', 
            'tauxErreurs', 
            'totalSouscriptions',
            'correctionsRecentes'
        ));
    }

    /**
     * Calculer le délai moyen de correction des souscriptions
     */
    private function calculerDelaiMoyenCorrection()
    {
        // Récupérer les souscriptions corrigées avec leurs dates
        $souscriptionsCorrigees = Souscription::where('statut_correction', 'corrige')
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->get();
        
        if ($souscriptionsCorrigees->isEmpty()) {
            return '0h 00min';
        }
        
        $totalHeures = 0;
        $nombreCorrections = 0;
        
        foreach ($souscriptionsCorrigees as $souscription) {
            $dateCreation = \Carbon\Carbon::parse($souscription->created_at);
            $dateCorrection = \Carbon\Carbon::parse($souscription->updated_at);
            
            // Calculer la différence en heures
            $diffHeures = $dateCreation->diffInHours($dateCorrection);
            $totalHeures += $diffHeures;
            $nombreCorrections++;
        }
        
        $delaiMoyenHeures = $totalHeures / $nombreCorrections;
        $heures = floor($delaiMoyenHeures);
        $minutes = round(($delaiMoyenHeures - $heures) * 60);
        
        return "{$heures}h {$minutes}min";
    }

    /**
     * Afficher la liste des souscriptions à corriger
     */
    public function corrigees(Request $request)
    {
        $countQuery = Souscription::query()->whereHas('operateur', function ($q) {
            $q->where('role', User::ROLE_OPERATEUR);
        });
        $this->applySouscriptionFilters($request, $countQuery);
        $totalEnAttente = (clone $countQuery)
            ->where(function ($q) {
                $q->whereNull('statut_correction')
                    ->orWhere('statut_correction', 'pas_corrige');
            })
            ->count();
        
        $souscriptionsParOperateur = Souscription::with('operateur')
            ->whereHas('operateur', function ($q) {
                $q->where('role', User::ROLE_OPERATEUR);
            })
            ->where(function ($q) {
                $q->whereNull('statut_correction')
                    ->orWhere('statut_correction', 'pas_corrige');
            })
            ->select('operateur_id', DB::raw('count(*) as total'))
            ->groupBy('operateur_id');

        $this->applySouscriptionFilters($request, $souscriptionsParOperateur);

        $souscriptionsParOperateur = $souscriptionsParOperateur->get();

        $tableQuery = Souscription::with(['operateur', 'client', 'projet'])
            ->whereHas('operateur', function ($q) {
                $q->where('role', User::ROLE_OPERATEUR);
            })
            ->where(function ($q) {
                $q->whereNull('statut_correction')
                    ->orWhere('statut_correction', 'pas_corrige');
            })
            ->orderBy('created_at', 'desc');

        $this->applySouscriptionFilters($request, $tableQuery);

        $souscriptionsACorriger = $tableQuery->paginate(20)->withQueryString();
        
        return view('chef_commercial.souscriptions.corrigees', compact(
            'souscriptionsACorriger', 
            'totalEnAttente',
            'souscriptionsParOperateur'
        ));
    }

    public function corrige(Request $request)
    {
        $countQuery = Souscription::query()
            ->whereHas('operateur', function ($q) {
                $q->where('role', User::ROLE_OPERATEUR);
            })
            ->where('statut_correction', 'corrige');
        $this->applySouscriptionFilters($request, $countQuery);
        $totalCorrigees = (clone $countQuery)->count();

        $tableQuery = Souscription::with(['operateur', 'client', 'projet'])
            ->where('statut_correction', 'corrige')
            ->whereHas('operateur', function ($q) {
                $q->where('role', User::ROLE_OPERATEUR);
            })
            ->orderBy('updated_at', 'desc');

        $this->applySouscriptionFilters($request, $tableQuery);

        $souscriptionsCorrigees = $tableQuery->paginate(20)->withQueryString();

        return view('chef_commercial.souscriptions.corrige', compact(
            'souscriptionsCorrigees',
            'totalCorrigees'
        ));
    }

    private function applySouscriptionFilters(Request $request, $query)
    {
        if ($request->filled('nom')) {
            $nom = $request->nom;
            $query->whereHas('client', function ($q) use ($nom) {
                $q->where('nom_prenom', 'like', "%{$nom}%");
            });
        }

        if ($request->filled('code')) {
            $code = $request->code;
            $query->where('ref_souscription', 'like', "%{$code}%");
        }

        if ($request->filled('num_client')) {
            $numClient = $request->num_client;
            $query->whereHas('client', function ($q) use ($numClient) {
                $q->where('ref_client', 'like', "%{$numClient}%");
            });
        }

        if ($request->filled('correction') && in_array($request->correction, ['pas_corrige', 'corrige'], true)) {
            $query->where('statut_correction', $request->correction);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    /**
     * Créer automatiquement les paiements pour une souscription
     */
    private function createPaymentFromSubscription(Souscription $souscription)
    {
        DB::transaction(function () use ($souscription) {
            if ($souscription->frais_souscription > 0) {
                \App\Models\FraisDossier::create([
                    'id_souscription' => $souscription->id,
                    'id_projet' => $souscription->programme,
                    'montant' => $souscription->frais_souscription,
                    'montant_paye' => 0,
                    'montant_reste' => $souscription->frais_souscription,
                    'id_comptable' => null,
                ]);
            }

            if ($souscription->apport_initial > 0) {
                \App\Models\ApportInitial::create([
                    'id_souscription' => $souscription->id,
                    'id_projet' => $souscription->programme,
                    'montant' => $souscription->apport_initial,
                    'montant_paye' => 0,
                    'montant_reste' => $souscription->apport_initial,
                    'id_comptable' => null,
                ]);
            }
        });
    }

    // Ajouter l'édition d'une souscription
    public function edit(\App\Models\Souscription $souscription)
    {
        // Charger la relation client pour éviter l'erreur null
        $souscription->load(['client', 'bienImmobilier']);
        
        $projets = \App\Models\Projet::where('est_actif', true)->get();
        $biensImmobiliers = [];
        $mutuelles = Mutuelle::where('est_active', true)->get();
        foreach ($projets as $projet) {
            $biensImmobiliers[$projet->id] = \App\Models\BienImmobilier::where('idprojet', $projet->id)
                ->with(['mutuelles' => function($q){ $q->select('mutuelles.id','mutuelles.nom'); }])
                ->get();
        }
        return view('chef_commercial.souscriptions.edit', compact('souscription', 'projets', 'biensImmobiliers', 'mutuelles'));
    }

    // Mettre à jour une souscription
    public function update(Request $request, \App\Models\Souscription $souscription)
    {
        // Nettoyer les montants (supprimer les espaces)
        $cleanAmount = function($value) {
            return str_replace(' ', '', $value);
        };

        $request->merge([
            'salary' => $cleanAmount($request->salary),
            'valeur_souscription' => $cleanAmount($request->valeur_souscription),
            'apport_initial' => $cleanAmount($request->apport_initial),
            'frais_souscription' => $cleanAmount($request->frais_souscription),
        ]);

        $request->validate([
            'clientCategory' => 'required|string|in:Client individuel,Association,Syndicat,Mutuelle,Association Syndicat Mutuelle,Client diaspora',
            'organisation_type' => 'required_if:clientCategory,Association Syndicat Mutuelle|nullable|string|in:Association,Syndicat,Mutuelle',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'birthDate' => 'required|date',
            'birthPlace' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'children' => 'required|integer|min:0',
            'heirs' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'salary' => 'required|numeric|min:0',
            'maritalStatus' => 'required|string|in:Célibataire,Divorcé(e),Marié(e),Veuf(ve),Concubinage',
            'nomConjoint' => 'required_if:maritalStatus,Marié(e)|nullable|string|max:255',
            'telephoneConjoint' => 'required_if:maritalStatus,Marié(e)|nullable|string|max:255',
            'idType' => 'required|string|in:CNI,Passeport,Carte consulaire',
            'idNumber' => 'required|string|max:255',
            'idFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'mutuelle_id' => 'required_if:organisation_type,Mutuelle|nullable|integer|exists:mutuelles,id',
            'program' => 'required|exists:projets,id',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after:startDate',
            'housingType' => ['required', 'string', function($attribute, $value, $fail) {
                if (!preg_match('/^[0-9]+\|.+$/', $value) && !preg_match('/^.+$/', $value)) {
                    $fail('Le format du type de logement est invalide.');
                }
            }],
            'paymentMode' => 'required|string',
            'valeur_souscription' => 'required|numeric|min:0',
            'apport_initial' => 'required|numeric|min:0',
            'apport_initial_paye_par_client' => 'nullable|boolean',
            'frais_souscription' => 'required|numeric|min:0',
        ]);

        $situationMatrimonialeMap = [
            'Célibataire' => 'celibataire',
            'Divorcé(e)' => 'divorce',
            'Marié(e)' => 'marie',
            'Veuf(ve)' => 'veuf',
            'Concubinage' => 'concubinage'
        ];
        $naturePieceMap = [
            'CNI' => 'cni',
            'Passeport' => 'passeport',
            'Carte consulaire' => 'carte_consulaire'
        ];
        $categorieClientMap = [
            'Client individuel' => 'individuel',
            'Association' => 'association',
            'Syndicat' => 'syndicat',
            'Mutuelle' => 'mutuelle',
            'Client diaspora' => 'diaspora'
        ];

        $effectiveCategory = $request->clientCategory;
        if ($request->clientCategory === 'Association Syndicat Mutuelle') {
            $effectiveCategory = $request->organisation_type;
        }
        $categorieClient = $categorieClientMap[$effectiveCategory] ?? ($souscription->categorie_client ?? 'individuel');

        // Mettre à jour le client lié
        $client = $souscription->client ?: new \App\Models\Client();
        $client->nom = $request->nom;
        $client->prenom = $request->prenom;
        $client->nom_prenom = $request->nom . ' ' . $request->prenom;
        $client->email = $request->email;
        $client->telephone = $request->phone;
        $client->date_naissance = $request->birthDate;
        $client->lieu_naissance = $request->birthPlace;
        $client->nationalite = $request->nationality;
        $client->nombre_enfants = $request->children;
        $client->ayant_droit = $request->heirs;
        $client->salaire_mensuel = $request->salary;
        $client->situation_matrimoniale = $situationMatrimonialeMap[$request->maritalStatus] ?? $client->situation_matrimoniale;
        if ($request->maritalStatus === 'Marié(e)') {
            $client->nom_conjoint = $request->nomConjoint;
            $client->telephone_conjoint = $request->telephoneConjoint;
        } else {
            // Si le statut change, on peut vouloir vider les infos du conjoint
            // $client->nom_conjoint = null;
            // $client->telephone_conjoint = null;
        }
        $client->nature_piece = $naturePieceMap[$request->idType] ?? $client->nature_piece;
        $client->numero_piece = $request->idNumber;
        $client->categorie_client = $categorieClient;
        $client->mutuelle_id = $categorieClient === 'mutuelle' ? $request->mutuelle_id : null;

        // Gestion du fichier d'identification
        $path = null;
        if ($request->hasFile('idFile')) {
            $file = $request->file('idFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('identifications', $filename, 'public');
            $client->fichier_piece = $path;
        }
        $client->save();

        // Lier au client si nouveau
        if (!$souscription->client_id) {
            $souscription->client_id = $client->id;
        }

        // Mettre à jour la souscription
        $souscription->operateur_id = $souscription->operateur_id ?: \Illuminate\Support\Facades\Auth::id();
        $souscription->categorie_client = $categorieClient;
        $souscription->nom = $request->nom;
        $souscription->prenom = $request->prenom;
        $souscription->nom_prenom = $request->nom . ' ' . $request->prenom;
        $souscription->date_naissance = $request->birthDate;
        $souscription->lieu_naissance = $request->birthPlace;
        $souscription->nationalite = $request->nationality;
        $souscription->nombre_enfants = $request->children;
        $souscription->ayant_droit = $request->heirs;
        $souscription->email = $request->email;
        $souscription->telephone = $request->phone;
        $souscription->salaire_mensuel = $request->salary;
        $souscription->situation_matrimoniale = $situationMatrimonialeMap[$request->maritalStatus] ?? $souscription->situation_matrimoniale;
        if ($request->maritalStatus === 'Marié(e)') {
            $souscription->nom_conjoint = $request->nomConjoint;
            $souscription->telephone_conjoint = $request->telephoneConjoint;
        }
        $souscription->nature_piece = $naturePieceMap[$request->idType] ?? $souscription->nature_piece;
        $souscription->numero_piece = $request->idNumber;
        if ($path) { $souscription->fichier_piece = $path; }
        $souscription->programme = $request->program;
        $souscription->date_debut = $request->startDate;
        $souscription->date_fin = $request->endDate;
        $dateDebut = \Carbon\Carbon::parse($request->startDate);
        $dateFin = \Carbon\Carbon::parse($request->endDate);
        $souscription->duree_contrat_mois = $dateDebut->diffInMonths($dateFin);

        // Type de logement (format "id|nom" ou nom simple)
        $housingTypeParts = explode('|', $request->housingType);
        $valeurSouscription = (float) $request->valeur_souscription;
        $fraisSouscription = (float) $request->frais_souscription;
        $apportInitialCalc = (float) $request->apport_initial;
        if (count($housingTypeParts) === 2) {
            [$bienId, $bienNom] = $housingTypeParts;
            $souscription->bien_immobilier_id = $bienId;
            $bienImmobilier = \App\Models\BienImmobilier::with('mutuelles')->find($bienId);
            $souscription->type_logement = $bienImmobilier ? $bienImmobilier->titre : $bienNom;
            if ($bienImmobilier) {
                $valeurSouscription = (float) $bienImmobilier->prix;
                if (strtolower((string) $request->organisation_type) === 'mutuelle' && $request->filled('mutuelle_id')) {
                    $mutuelle = $bienImmobilier->mutuelles()->where('mutuelle_id', $request->mutuelle_id)->first();
                    if ($mutuelle && $mutuelle->pivot && !empty($mutuelle->pivot->prix_special)) {
                        $valeurSouscription = (float) $mutuelle->pivot->prix_special;
                    }
                }
                $fraisSouscription = (float) ($bienImmobilier->frais_souscription ?? $fraisSouscription);
                $pct = (float) ($bienImmobilier->pourcentage_apport ?? 10);
                $apportInitialCalc = (float) ($bienImmobilier->apport_initial ?: round($valeurSouscription * ($pct / 100)));
            }
            $souscription->prix_logement = $valeurSouscription;
        } else {
            $souscription->type_logement = $request->housingType;
            $souscription->bien_immobilier_id = null;
            $souscription->prix_logement = $valeurSouscription;
        }

        $souscription->mode_paiement = $request->paymentMode;
        $souscription->valeur_souscription = $valeurSouscription;
        $apportPaye = (bool)($request->input('apport_initial_paye_par_client', 1));
        $souscription->apport_initial_paye_par_client = $apportPaye;
        $souscription->apport_initial = $apportPaye ? $apportInitialCalc : 0;
        $souscription->frais_souscription = $fraisSouscription;
        $souscription->statut = $souscription->statut ?: 'en_attente';
        $souscription->statut_correction = 'corrige';

        $souscription->save();

        // Synchroniser les paiements
        $this->syncPaymentsFromSubscription($souscription);

        return redirect()->route('chef_commercial.souscriptions.corrigees')
            ->with('success', 'Souscription mise à jour avec succès.');
    }

    private function syncPaymentsFromSubscription(\App\Models\Souscription $souscription)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($souscription) {
            $frais = \App\Models\FraisDossier::where('id_souscription', $souscription->id)->first();
            if ($souscription->frais_souscription > 0) {
                if ($frais) {
                    $frais->id_projet = $souscription->programme;
                    $frais->montant = $souscription->frais_souscription;
                    $frais->montant_reste = max(($frais->montant ?? 0) - ($frais->montant_paye ?? 0), 0);
                    $frais->save();
                } else {
                    \App\Models\FraisDossier::create([
                        'id_souscription' => $souscription->id,
                        'id_projet' => $souscription->programme,
                        'montant' => $souscription->frais_souscription,
                        'montant_paye' => 0,
                        'montant_reste' => $souscription->frais_souscription,
                        'id_comptable' => null,
                    ]);
                }
            } elseif ($frais) {
                $frais->montant = 0; $frais->montant_reste = 0; $frais->save();
            }

            $apport = \App\Models\ApportInitial::where('id_souscription', $souscription->id)->first();
            if ($souscription->apport_initial > 0) {
                if ($apport) {
                    $apport->id_projet = $souscription->programme;
                    $apport->montant = $souscription->apport_initial;
                    $apport->montant_reste = max(($apport->montant ?? 0) - ($apport->montant_paye ?? 0), 0);
                    $apport->save();
                } else {
                    \App\Models\ApportInitial::create([
                        'id_souscription' => $souscription->id,
                        'id_projet' => $souscription->programme,
                        'montant' => $souscription->apport_initial,
                        'montant_paye' => 0,
                        'montant_reste' => $souscription->apport_initial,
                        'id_comptable' => null,
                    ]);
                }
            } elseif ($apport) {
                $apport->montant = 0; $apport->montant_reste = 0; $apport->save();
            }
        });
    }


}
