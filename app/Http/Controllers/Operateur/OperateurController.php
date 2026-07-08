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
use App\Http\Controllers\Concerns\SyncsIdentityExtensions;
use App\Services\ReferenceGenerator;
use App\Support\CurrentOrganization;

class OperateurController extends Controller
{
    use SyncsIdentityExtensions;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $user = Auth::user();
        
        // Statistiques pour l'opérateur connecté
        $totalSouscriptions = Souscription::where('operateur_id', $user->id)->count();

        $souscriptionsValidees = Souscription::where('operateur_id', $user->id)
            ->where(function ($q) {
                $q->whereIn('statut', ['FRAIS_OK', 'APPORT_OK', 'SOLD'])
                    ->orWhereHas('paiements', function ($p) {
                        $p->where('statut', 'payé');
                    })
                    ->orWhereHas('fraisDossier', function ($f) {
                        $f->where('montant_paye', '>', 0);
                    })
                    ->orWhereHas('apportInitial', function ($a) {
                        $a->where('montant_paye', '>', 0);
                    });
            })
            ->count();

        $souscriptionsEnAttente = Souscription::where('operateur_id', $user->id)
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->whereDoesntHave('paiements', function ($p) {
                $p->where('statut', 'payé');
            })
            ->whereDoesntHave('fraisDossier', function ($f) {
                $f->where('montant_paye', '>', 0);
            })
            ->whereDoesntHave('apportInitial', function ($a) {
                $a->where('montant_paye', '>', 0);
            })
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
        $mutuelles = \App\Models\Mutuelle::where('est_active', true)->get();
        
        foreach ($projets as $projet) {
            $biensImmobiliers[$projet->id] = \App\Models\BienImmobilier::where('idprojet', $projet->id)
                ->with(['mutuelles' => function($q){ $q->select('mutuelles.id','mutuelles.nom'); }])
                ->get();
        }
        
        return view('operateursaisi.create', compact('projets', 'biensImmobiliers', 'mutuelles'));
    }

    public function store(Request $request)
    {
        // Nettoyer les montants (supprimer les espaces)
        $cleanAmount = function ($value) {
            return str_replace(' ', '', (string) ($value ?? ''));
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
            'birthDate' => 'required|date',
            'birthPlace' => 'required|string|max:255',
            'nationality' => 'required|string|max:255',
            'children' => 'required|integer|min:0',
            'heirs' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
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
            'profession' => 'nullable|string|max:255',
            'entreprise' => 'nullable|string|max:255',
            'lieu_residence' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:120',
            'pays' => 'nullable|string|max:120',
            'date_delivrance_piece' => 'nullable|date',
            'date_expiration_piece' => 'nullable|date',
        ]);

        // Mapper les valeurs de situation matrimoniale
        $situationMatrimonialeMap = [
            'Célibataire' => 'celibataire',
            'Divorcé(e)' => 'divorce',
            'Marié(e)' => 'marie',
            'Veuf(ve)' => 'veuf',
            'Concubinage' => 'concubinage'
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

        $refClient = ReferenceGenerator::nextClientRef();

        // Vérifier si le client existe déjà par email ou numéro de pièce
        $client = null;
        if ($request->email) {
            $client = Client::where('email', $request->email)->first();
        }
        if (!$client && $request->idNumber) {
            $client = Client::where('numero_piece', $request->idNumber)->first();
        }
        
        if (!$client) {
            // Créer un nouveau client
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
            $client->ref_client = $refClient;
            
            $client->save();

            // Créer automatiquement un compte utilisateur pour le client
            $emailForUser = $request->email;
            
            // Si l'email n'est pas fourni, générer un email temporaire
            if (empty($emailForUser)) {
                $emailForUser = strtolower($refClient) . '@jarelinstrudie.local';
            }

            // Vérifier si un utilisateur avec cet email existe déjà
            $existingUser = \App\Models\User::where('email', $emailForUser)->first();
            
            if (!$existingUser) {
                // Générer un mot de passe temporaire (8 caractères aléatoires)
                $temporaryPassword = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
                
                // Créer le compte utilisateur
                $user = new \App\Models\User();
                $user->name = $request->nom . ' ' . $request->prenom;
                $user->email = $emailForUser;
                $user->password = \Illuminate\Support\Facades\Hash::make($temporaryPassword);
                $user->role = \App\Models\User::ROLE_CLIENT;
                $user->telephone = $request->phone ?? null;
                $user->save();

                // Stocker le mot de passe temporaire dans la session pour l'afficher à l'opérateur
                session()->flash('client_credentials', [
                    'email' => $emailForUser,
                    'password' => $temporaryPassword,
                    'ref_client' => $refClient,
                    'nom_client' => $request->nom . ' ' . $request->prenom
                ]);
            }
        }

        // Gérer le fichier d'identification s'il est présent
        if ($request->hasFile('idFile')) {
            $file = $request->file('idFile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('identifications', $filename, 'public');
            $client->fichier_piece = $path;
            $client->save();
        }
        
        $this->applyIdentityExtensionsToClient($client, $request);
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
        $valeurSouscription = (float) $request->valeur_souscription;
        $fraisSouscription = (float) $request->frais_souscription;
        $apportInitialCalc = (float) $request->apport_initial;
        if (count($housingTypeParts) === 2) {
            [$bienId, $bienNom] = $housingTypeParts;
            $souscription->bien_immobilier_id = $bienId;
            
            // Récupérer le bien immobilier et utiliser son titre comme source de vérité
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
            // Si le format n'est pas correct, utiliser la valeur brute
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
        $this->applyIdentityExtensionsToSouscription($souscription, $request);

        $souscription->ref_souscription = ReferenceGenerator::nextSouscriptionRef();
        
        $souscription->save();

        // Créer automatiquement les paiements pour les frais de dossier et l'apport initial
        $this->createPaymentFromSubscription($souscription);

        $ficheUrl = route('operateur.souscriptions.fiche-souscription', $souscription);
        $sendUrl = route('operateur.souscriptions.fiche-souscription.send', $souscription);
        $contratUrl = \Illuminate\Support\Facades\URL::signedRoute('public.souscriptions.contrat-reservation', ['souscription' => $souscription->id]);
        return redirect()->route('operateur.souscriptions.create')
            ->with('success', 'Souscription créée avec succès et soumise pour validation.')
            ->with('fiche_souscription_url', $ficheUrl)
            ->with('fiche_souscription_send_url', $sendUrl)
            ->with('contrat_reservation_url', $contratUrl);
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
