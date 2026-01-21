<?php

namespace App\Http\Controllers\Operateur;

use App\Http\Controllers\Controller;
use App\Models\Souscription;
use App\Models\User;
use App\Models\Client;
use App\Models\Projet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OperateurController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Statistiques pour l'opérateur connecté
        $totalSouscriptions = Souscription::where('operateur_id', $user->id)->count();
        $souscriptionsEnAttente = Souscription::where('operateur_id', $user->id)
            ->where('statut', 'en_attente')
            ->count();
        $souscriptionsValidees = Souscription::where('operateur_id', $user->id)
            ->where('statut', 'valide')
            ->count();
        
        // Calcul du taux de validation
        $tauxValidation = $totalSouscriptions > 0 
            ? round(($souscriptionsValidees / $totalSouscriptions) * 100, 1)
            : 0;

        // Souscriptions récentes de l'opérateur
        $souscriptions = Souscription::where('operateur_id', $user->id)
            ->with(['client', 'bienImmobilier'])
            ->latest()
            ->take(10)
            ->get();

        // Charger les noms des projets pour chaque souscription
        foreach ($souscriptions as $souscription) {
            if ($souscription->programme) {
                $projet = Projet::find($souscription->programme);
                $souscription->nom_programme = $projet ? $projet->nom : 'Projet non défini';
            } else {
                $souscription->nom_programme = 'Projet non défini';
            }
            
            // Si type_logement est vide ou null, récupérer le titre du bien immobilier
            if (empty($souscription->type_logement) && $souscription->bienImmobilier) {
                if (!empty($souscription->bienImmobilier->titre)) {
                    $souscription->type_logement = $souscription->bienImmobilier->titre;
                }
            }
        }

        // Statistiques mensuelles pour l'opérateur
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $souscriptionsCeMois = Souscription::where('operateur_id', $user->id)
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $moisPrecedent = Carbon::now()->subMonth();
        $souscriptionsMoisPrecedent = Souscription::where('operateur_id', $user->id)
            ->whereMonth('created_at', $moisPrecedent->month)
            ->whereYear('created_at', $moisPrecedent->year)
            ->count();

        // Calcul de l'évolution
        $evolution = $souscriptionsMoisPrecedent > 0
            ? round((($souscriptionsCeMois - $souscriptionsMoisPrecedent) / $souscriptionsMoisPrecedent) * 100, 1)
            : ($souscriptionsCeMois > 0 ? 100 : 0);

        return view('operateursaisi.dashboard', compact(
            'user',
            'totalSouscriptions',
            'souscriptionsEnAttente', 
            'souscriptionsValidees',
            'tauxValidation',
            'souscriptions',
            'souscriptionsCeMois',
            'evolution'
        ));
    }

    public function create()
    {
        $projets = Projet::where('est_actif', true)->get();
        $biensImmobiliers = [];
        
        foreach ($projets as $projet) {
            $biensImmobiliers[$projet->id] = \App\Models\BienImmobilier::where('idprojet', $projet->id)->get();
        }
        
        return view('operateursaisi.create', compact('projets', 'biensImmobiliers'));
    }

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

        // Mapper les valeurs de situation matrimoniale
        $situationMatrimonialeMap = [
            'Célibataire' => 'celibataire',
            'Divorcé(e)' => 'divorce',
            'Marié(e)' => 'marie',
            'Veuf(ve)' => 'veuf'
        ];

        // Mapper les valeurs de nature de pièce
        $naturePieceMap = [
            'CNI' => 'cni',
            'Passeport' => 'passeport',
            'Carte consulaire' => 'carte_consulaire'
        ];

        // Mapper les valeurs de catégorie client
        $categorieClientMap = [
            'Client individuel' => 'individuel',
            'Association Syndicat Mutuelle' => 'association',
            'Client diaspora' => 'diaspora'
        ];

        // Vérifier si le client existe déjà par email
        $client = Client::where('email', $request->email)->first();
        
        if (!$client) {
            // Créer un nouveau client
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

        // Gérer le fichier d'identification s'il est présent
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
            $souscription->fichier_piece = $path;
        }
        $souscription->programme = $request->program;
        $souscription->date_debut = $request->startDate;
        $souscription->date_fin = $request->endDate;
        
        // Calculer automatiquement la durée du contrat en mois
        $dateDebut = \Carbon\Carbon::parse($request->startDate);
        $dateFin = \Carbon\Carbon::parse($request->endDate);
        $souscription->duree_contrat_mois = $dateDebut->diffInMonths($dateFin);
        
        // Extraire l'ID et le nom du bien immobilier du format "id|nom"
        $housingTypeParts = explode('|', $request->housingType);
        if (count($housingTypeParts) === 2) {
            [$bienId, $bienNom] = $housingTypeParts;
            $souscription->bien_immobilier_id = $bienId;
            
            // Récupérer le bien immobilier et utiliser son titre comme source de vérité
            $bienImmobilier = \App\Models\BienImmobilier::find($bienId);
            $souscription->type_logement = $bienImmobilier ? $bienImmobilier->titre : $bienNom;
            $souscription->prix_logement = $bienImmobilier ? $bienImmobilier->prix : $request->valeur_souscription;
        } else {
            // Si le format n'est pas correct, utiliser la valeur brute
            $souscription->type_logement = $request->housingType;
            $souscription->bien_immobilier_id = null;
            $souscription->prix_logement = $request->valeur_souscription;
        }
        
        $souscription->mode_paiement = $request->paymentMode;
        $souscription->valeur_souscription = $request->valeur_souscription;
        $souscription->apport_initial = $request->apport_initial;
        $souscription->frais_souscription = $request->frais_souscription;
        $souscription->statut = 'en_attente';
        
        // Générer la référence souscription
        $lastSouscription = Souscription::orderBy('id', 'desc')->first();
        $nextNumber = $lastSouscription ? $lastSouscription->id + 1 : 1;
        $souscription->ref_souscription = 'SOUS-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
        
        $souscription->save();

        // Créer automatiquement les paiements pour les frais de dossier et l'apport initial
        $this->createPaymentFromSubscription($souscription);

        return redirect()->route('operateur.souscriptions.create')
            ->with('success', 'Souscription créée avec succès et soumise pour validation.');
    }

    /**
     * Créer automatiquement les paiements pour une souscription
     */
    private function createPaymentFromSubscription(Souscription $souscription)
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($souscription) {
            // Créer le paiement frais de dossier
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

            // Créer l'apport initial
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
}