<?php

namespace App\Http\Controllers\ChefCommercial;

use App\Http\Controllers\Controller;
use App\Models\Souscription;
use App\Models\Client;
use App\Models\Projet;
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
        
        foreach ($projets as $projet) {
            $biensImmobiliers[$projet->id] = \App\Models\BienImmobilier::where('idprojet', $projet->id)->get();
        }
        
        return view('chef_commercial.souscriptions.create', compact('projets', 'biensImmobiliers'));
    }

    /**
     * Enregistrer une souscription (même logique que l'opérateur)
     */
    public function store(Request $request)
    {
        $request->validate([
            'clientCategory' => 'required|string|in:Client individuel,Association Syndicat Mutuelle,Client diaspora',
            'fullName' => 'required|string|max:255',
            'birthDate' => 'required|date',
            'birthPlace' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'children' => 'required|integer|min:0',
            'heirs' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'salary' => 'required|string|max:255',
            'maritalStatus' => 'required|string|in:Célibataire,Divorcé(e),Marié(e),Veuf(ve)',
            'idType' => 'required|string|in:CNI,Passeport,Carte consulaire',
            'idNumber' => 'required|string|max:255',
            'idFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
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
            'frais_souscription' => 'required|numeric|min:0',
        ]);

        // Mapper les valeurs
        $situationMatrimonialeMap = [
            'Célibataire' => 'celibataire',
            'Divorcé(e)' => 'divorce',
            'Marié(e)' => 'marie',
            'Veuf(ve)' => 'veuf'
        ];

        $naturePieceMap = [
            'CNI' => 'cni',
            'Passeport' => 'passeport',
            'Carte consulaire' => 'carte_consulaire'
        ];

        $categorieClientMap = [
            'Client individuel' => 'individuel',
            'Association Syndicat Mutuelle' => 'association',
            'Client diaspora' => 'diaspora'
        ];

        // Vérifier si le client existe déjà par email
        $client = Client::where('email', $request->email)->first();
        
        if (!$client) {
            $client = new Client();
            $client->nom_prenom = $request->fullName;
            $client->email = $request->email;
            $client->date_naissance = $request->birthDate;
            $client->lieu_naissance = $request->birthPlace;
            $client->nationalite = $request->nationality;
            $client->nombre_enfants = $request->children;
            $client->ayant_droit = $request->heirs;
            $client->salaire_mensuel = $request->salary;
            $client->situation_matrimoniale = $situationMatrimonialeMap[$request->maritalStatus] ?? 'celibataire';
            $client->nature_piece = $naturePieceMap[$request->idType] ?? 'cni';
            $client->numero_piece = $request->idNumber;
            $client->categorie_client = $categorieClientMap[$request->clientCategory] ?? 'individuel';
            $client->mutuelle_id = null;

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

        // Créer la souscription
        $souscription = new Souscription();
        $souscription->operateur_id = Auth::id();
        $souscription->client_id = $client->id;
        $souscription->categorie_client = $categorieClientMap[$request->clientCategory] ?? 'particulier';
        $souscription->nom_prenom = $request->fullName;
        $souscription->date_naissance = $request->birthDate;
        $souscription->lieu_naissance = $request->birthPlace;
        $souscription->nationalite = $request->nationality;
        $souscription->nombre_enfants = $request->children;
        $souscription->ayant_droit = $request->heirs;
        $souscription->email = $request->email;
        $souscription->salaire_mensuel = $request->salary;
        $souscription->situation_matrimoniale = $situationMatrimonialeMap[$request->maritalStatus] ?? 'celibataire';
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
        if (count($housingTypeParts) === 2) {
            [$bienId, $bienNom] = $housingTypeParts;
            $souscription->bien_immobilier_id = $bienId;
            $bienImmobilier = \App\Models\BienImmobilier::find($bienId);
            $souscription->type_logement = $bienImmobilier ? $bienImmobilier->titre : $bienNom;
            $souscription->prix_logement = $bienImmobilier ? $bienImmobilier->prix : $request->valeur_souscription;
        } else {
            $souscription->type_logement = $request->housingType;
            $souscription->bien_immobilier_id = null;
            $souscription->prix_logement = $request->valeur_souscription;
        }
        
        $souscription->mode_paiement = $request->paymentMode;
        $souscription->valeur_souscription = $request->valeur_souscription;
        $souscription->apport_initial = $request->apport_initial;
        $souscription->frais_souscription = $request->frais_souscription;
        $souscription->statut = 'en_attente';
        
        // Référence souscription
        $lastSouscription = Souscription::orderBy('id', 'desc')->first();
        $nextNumber = $lastSouscription ? $lastSouscription->id + 1 : 1;
        $souscription->ref_souscription = 'SOUS-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        $souscription->save();

        // Générer les paiements (frais dossier + apport initial)
        $this->createPaymentFromSubscription($souscription);

        return redirect()->route('chef_commercial.souscriptions.create')
            ->with('success', 'Souscription créée avec succès et soumise pour validation.');
    }

    /**
     * Afficher le tableau de bord du chef commercial
     */
    public function dashboard()
    {
        // Statistiques des souscriptions
        $totalSouscriptions = Souscription::count();
        $souscriptionsCorrigees = Souscription::where('statut', 'corrige')->count();
        
        // Calcul du taux d'erreurs (souscriptions en attente de correction / total)
        $souscriptionsEnAttente = Souscription::where('statut', 'en_attente')->count();
        $tauxErreurs = $totalSouscriptions > 0 ? round(($souscriptionsEnAttente / $totalSouscriptions) * 100, 1) : 0;
        
        // Calcul du délai moyen de correction en heures
        $delaiMoyenCorrection = $this->calculerDelaiMoyenCorrection();
        
        // Historique des corrections récentes (10 dernières)
        $correctionsRecentes = Souscription::with(['operateur', 'client', 'projet'])
            ->whereIn('statut', ['corrige', 'en_attente'])
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('chef_commercial.dashboard', compact(
            'souscriptionsCorrigees', 
            'tauxErreurs', 
            'delaiMoyenCorrection', 
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
        $souscriptionsCorrigees = Souscription::where('statut', 'corrige')
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
    public function corrigees()
    {
        // Récupérer les souscriptions en attente de correction avec leurs relations
        $souscriptionsACorriger = Souscription::with(['operateur', 'client', 'projet'])
            ->where('statut', 'en_attente')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Calculer le nombre total de souscriptions en attente
        $totalEnAttente = $souscriptionsACorriger->count();
        
        // Calculer le nombre de souscriptions par opérateur
        $souscriptionsParOperateur = Souscription::with('operateur')
            ->where('statut', 'en_attente')
            ->select('operateur_id', DB::raw('count(*) as total'))
            ->groupBy('operateur_id')
            ->get();
        
        return view('chef_commercial.souscriptions.corrigees', compact(
            'souscriptionsACorriger', 
            'totalEnAttente',
            'souscriptionsParOperateur'
        ));
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
        foreach ($projets as $projet) {
            $biensImmobiliers[$projet->id] = \App\Models\BienImmobilier::where('idprojet', $projet->id)->get();
        }
        return view('chef_commercial.souscriptions.edit', compact('souscription', 'projets', 'biensImmobiliers'));
    }

    // Mettre à jour une souscription
    public function update(Request $request, \App\Models\Souscription $souscription)
    {
        $request->validate([
            'clientCategory' => 'required|string|in:Client individuel,Association Syndicat Mutuelle,Client diaspora',
            'fullName' => 'required|string|max:255',
            'birthDate' => 'required|date',
            'birthPlace' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'children' => 'required|integer|min:0',
            'heirs' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'salary' => 'required|string|max:255',
            'maritalStatus' => 'required|string|in:Célibataire,Divorcé(e),Marié(e),Veuf(ve)',
            'idType' => 'required|string|in:CNI,Passeport,Carte consulaire',
            'idNumber' => 'required|string|max:255',
            'idFile' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
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
            'frais_souscription' => 'required|numeric|min:0',
        ]);

        $situationMatrimonialeMap = [
            'Célibataire' => 'celibataire',
            'Divorcé(e)' => 'divorce',
            'Marié(e)' => 'marie',
            'Veuf(ve)' => 'veuf'
        ];
        $naturePieceMap = [
            'CNI' => 'cni',
            'Passeport' => 'passeport',
            'Carte consulaire' => 'carte_consulaire'
        ];
        $categorieClientMap = [
            'Client individuel' => 'individuel',
            'Association Syndicat Mutuelle' => 'association',
            'Client diaspora' => 'diaspora'
        ];

        // Mettre à jour le client lié
        $client = $souscription->client ?: new \App\Models\Client();
        $client->nom_prenom = $request->fullName;
        $client->email = $request->email;
        $client->date_naissance = $request->birthDate;
        $client->lieu_naissance = $request->birthPlace;
        $client->nationalite = $request->nationality;
        $client->nombre_enfants = $request->children;
        $client->ayant_droit = $request->heirs;
        $client->salaire_mensuel = $request->salary;
        $client->situation_matrimoniale = $situationMatrimonialeMap[$request->maritalStatus] ?? $client->situation_matrimoniale;
        $client->nature_piece = $naturePieceMap[$request->idType] ?? $client->nature_piece;
        $client->numero_piece = $request->idNumber;
        $client->categorie_client = $categorieClientMap[$request->clientCategory] ?? $client->categorie_client;
        $client->mutuelle_id = $client->mutuelle_id ?? null;

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
        $souscription->categorie_client = $categorieClientMap[$request->clientCategory] ?? $souscription->categorie_client;
        $souscription->nom_prenom = $request->fullName;
        $souscription->date_naissance = $request->birthDate;
        $souscription->lieu_naissance = $request->birthPlace;
        $souscription->nationalite = $request->nationality;
        $souscription->nombre_enfants = $request->children;
        $souscription->ayant_droit = $request->heirs;
        $souscription->email = $request->email;
        $souscription->salaire_mensuel = $request->salary;
        $souscription->situation_matrimoniale = $situationMatrimonialeMap[$request->maritalStatus] ?? $souscription->situation_matrimoniale;
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
        if (count($housingTypeParts) === 2) {
            [$bienId, $bienNom] = $housingTypeParts;
            $souscription->bien_immobilier_id = $bienId;
            $bienImmobilier = \App\Models\BienImmobilier::find($bienId);
            $souscription->type_logement = $bienImmobilier ? $bienImmobilier->titre : $bienNom;
            $souscription->prix_logement = $bienImmobilier ? $bienImmobilier->prix : $request->valeur_souscription;
        } else {
            $souscription->type_logement = $request->housingType;
            $souscription->bien_immobilier_id = null;
            $souscription->prix_logement = $request->valeur_souscription;
        }

        $souscription->mode_paiement = $request->paymentMode;
        $souscription->valeur_souscription = $request->valeur_souscription;
        $souscription->apport_initial = $request->apport_initial;
        $souscription->frais_souscription = $request->frais_souscription;
        $souscription->statut = $souscription->statut ?: 'en_attente';

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