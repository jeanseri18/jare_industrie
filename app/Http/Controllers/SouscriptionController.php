<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Souscription;
use App\Models\AttributionLot;
use App\Models\ValidationFinale;
use App\Models\Paiement;
use App\Models\Client;
use App\Models\Projet;
use App\Models\Mutuelle;
use App\Models\BienImmobilier;
use App\Models\FraisDossier;
use App\Models\ApportInitial;
use App\Models\User;
use App\Mail\FicheSouscriptionMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class SouscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Souscription::with(['client', 'projet', 'attributionLot']);

        if ($request->filled('statut')) {
            switch ($request->statut) {
                case 'annulee':
                    $query->where('statut', 'annulee');
                    break;
                case 'en_attente':
                    $query->whereDoesntHave('paiements', function($q){
                        $q->where('statut', 'payé');
                    })->where('statut', '!=', 'annulee');
                    break;
                case 'en_cours':
                    $query->whereHas('paiements', function($q){
                        $q->where('statut', 'payé');
                    })->whereRaw('
                        (COALESCE(prix_logement,0) + (select COALESCE(sum(montant),0) from frais_dossiers where id_souscription = souscriptions.id))
                        >
                        (select COALESCE(sum(montant),0) from paiements where paiements.dossier_id = souscriptions.id and paiements.statut = "payé")
                    ');
                    break;
                case 'solde':
                case 'soldé':
                    $query->where('statut', 'SOLD');
                    break;
            }
        }

        $souscriptions = $query->latest()->paginate(20);
        return view('dg.souscriptions.index', compact('souscriptions'));
    }

    /**
     * Annuler une souscription
     */
    public function annuler(Souscription $souscription)
    {
        $souscription->update([
            'statut_precedent' => $souscription->statut,
            'statut' => 'annulee'
        ]);
        return redirect()->back()->with('success', "La souscription {$souscription->ref_souscription} a été annulée avec succès.");
    }

    /**
     * Réactiver une souscription annulée
     */
    public function reactiver(Souscription $souscription)
    {
        if ($souscription->statut !== 'annulee') {
            return redirect()->back()->with('error', "Cette souscription n'est pas annulée.");
        }

        $nouveauStatut = $souscription->statut_precedent ?? 'en_attente';
        
        $souscription->update([
            'statut' => $nouveauStatut,
            'statut_precedent' => null
        ]);

        return redirect()->back()->with('success', "La souscription {$souscription->ref_souscription} a été réactivée avec le statut : {$nouveauStatut}.");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $projets = Projet::where('est_actif', true)->get();
        $biensImmobiliers = [];
        $mutuelles = Mutuelle::where('est_active', true)->get();

        foreach ($projets as $projet) {
            $biensImmobiliers[$projet->id] = BienImmobilier::where('idprojet', $projet->id)
                ->with(['mutuelles' => function($q){ $q->select('mutuelles.id','mutuelles.nom'); }])
                ->get();
        }

        return view('dg.souscriptions.create', compact('projets', 'biensImmobiliers', 'mutuelles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        $categorieClient = $categorieClientMap[$effectiveCategory] ?? 'individuel';

        $lastClient = Client::orderBy('id', 'desc')->first();
        $nextNumber = $lastClient ? $lastClient->id + 1 : 1;
        $refClient = 'CLI-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        $client = null;
        if ($request->email) {
            $client = Client::where('email', $request->email)->first();
        }
        if (!$client && $request->idNumber) {
            $client = Client::where('numero_piece', $request->idNumber)->first();
        }

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
            $client->ref_client = $refClient;
            $client->save();

            $emailForUser = $request->email;
            if (empty($emailForUser)) {
                $emailForUser = strtolower($refClient) . '@jarelinstrudie.local';
            }

            $existingUser = User::where('email', $emailForUser)->first();
            if (!$existingUser) {
                $temporaryPassword = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'), 0, 8);
                $user = new User();
                $user->name = $request->nom . ' ' . $request->prenom;
                $user->email = $emailForUser;
                $user->password = Hash::make($temporaryPassword);
                $user->role = User::ROLE_CLIENT;
                $user->telephone = $request->phone ?? null;
                $user->save();

                session()->flash('client_credentials', [
                    'email' => $emailForUser,
                    'password' => $temporaryPassword,
                    'ref_client' => $refClient,
                    'nom_client' => $request->nom . ' ' . $request->prenom
                ]);
            }
        }

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

        $dateDebut = \Carbon\Carbon::parse($request->startDate);
        $dateFin = \Carbon\Carbon::parse($request->endDate);
        $souscription->duree_contrat_mois = $dateDebut->diffInMonths($dateFin);

        $housingTypeParts = explode('|', $request->housingType);
        $valeurSouscription = (float) $request->valeur_souscription;
        $fraisSouscription = (float) $request->frais_souscription;
        $apportInitialCalc = (float) $request->apport_initial;
        if (count($housingTypeParts) === 2) {
            [$bienId, $bienNom] = $housingTypeParts;
            $souscription->bien_immobilier_id = $bienId;
            $bienImmobilier = BienImmobilier::with('mutuelles')->find($bienId);
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

        $lastSouscription = Souscription::orderBy('id', 'desc')->first();
        $nextNumber = $lastSouscription ? $lastSouscription->id + 1 : 1;
        $souscription->ref_souscription = 'SOUS-' . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        $souscription->save();

        $this->createPaymentFromSubscription($souscription);

        $ficheUrl = route('dg.souscriptions.fiche-souscription', $souscription);
        $sendUrl = route('dg.souscriptions.fiche-souscription.send', $souscription);
        return redirect()->route('dg.souscriptions.create')
            ->with('success', 'Souscription créée avec succès et soumise pour validation.')
            ->with('fiche_souscription_url', $ficheUrl)
            ->with('fiche_souscription_send_url', $sendUrl);
    }

    private function createPaymentFromSubscription(Souscription $souscription): void
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($souscription) {
            if ($souscription->frais_souscription > 0) {
                FraisDossier::create([
                    'id_souscription' => $souscription->id,
                    'id_projet' => $souscription->programme,
                    'montant' => $souscription->frais_souscription,
                    'montant_paye' => 0,
                    'montant_reste' => $souscription->frais_souscription,
                    'id_comptable' => null,
                ]);
            }

            if ($souscription->apport_initial > 0) {
                ApportInitial::create([
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

    /**
     * Display the specified resource.
     */
    public function show(Souscription $souscription)
    {
        $souscription->load(['client', 'projet', 'attributionLot', 'paiements', 'fraisDossier', 'apportInitial']);
        return view('dg.souscriptions.show', compact('souscription'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Enregistrer l'attribution d'un logement pour une souscription.
     */


    
    // ...

    public function attribuer(Request $request, Souscription $souscription)
    {
        $request->validate([
            'numero_page_guide' => 'required|string|max:50',
            'lot' => 'required|string|max:50',
            'ilot' => 'required|string|max:50',
            'numero_villa' => 'required|string|max:50',
            'superficie' => 'required|numeric|min:0',
            'observations_internes' => 'nullable|string|max:1000',
        ]);

        // Vérification de la double attribution (même projet, même îlot, même lot)
        $existe = AttributionLot::where('idProjet', $souscription->programme)
            ->where('ilot', $request->ilot)
            ->where('lot', $request->lot)
            ->exists();

        if ($existe) {
            return back()->withErrors(['lot' => "Ce lot (Îlot {$request->ilot}, Lot {$request->lot}) est déjà attribué dans ce projet."])->withInput();
        }

        // Vérification logique : Numéro Villa doit être égal au Lot
        if ($request->numero_villa != $request->lot) {
             return back()->withErrors(['numero_villa' => "Le numéro de la villa doit être identique au numéro du lot."])->withInput();
        }

        $attribution = AttributionLot::create([
            'idProjet' => $souscription->programme,
            'id_souscription' => $souscription->id,
            'type_logement' => $souscription->type_logement,
            'numero_page_guide' => $request->numero_page_guide,
            'lot' => $request->lot,
            'ilot' => $request->ilot,
            'numero_villa' => $request->numero_villa,
            'superficie' => $request->superficie,
            'observations_internes' => $request->observations_internes,
        ]);

        return redirect()->route('dg.attribution.index')
            ->with('success', "Attribution enregistrée pour la souscription {$souscription->ref_souscription}. Une attestation de réservation peut être générée.")
            ->with('download_attestation_url', route('dg.souscriptions.attestation', $souscription));
    }

    public function downloadAttestation(Souscription $souscription)
    {
        if (!$souscription->attributionLot) {
            return back()->with('error', "Aucune attribution trouvée pour cette souscription.");
        }
        
        $pdf = Pdf::loadView('documents.attestation_reservation', compact('souscription'))
            ->setPaper('a4');
        return $pdf->stream("Attestation_Reservation_{$souscription->ref_souscription}.pdf");
    }

    public function downloadFicheSouscription(Souscription $souscription)
    {
        $user = Auth::user();
        if (!$user || !($user instanceof User) || !$user->isAdmin()) {
            abort(403);
        }
        if ($user->role === User::ROLE_OPERATEUR && (int)$souscription->operateur_id !== (int)$user->id) {
            abort(403);
        }

        $souscription->load(['client.mutuelle', 'projet', 'bienImmobilier', 'operateur']);
        $pdf = Pdf::loadView('documents.fiche_souscription', compact('souscription'))
            ->setPaper('a4');
        return $pdf->download("Fiche_Souscription_{$souscription->ref_souscription}.pdf");
    }

    public function sendFicheSouscription(Souscription $souscription)
    {
        $user = Auth::user();
        if (!$user || !($user instanceof User) || !$user->isAdmin()) {
            abort(403);
        }
        if ($user->role === User::ROLE_OPERATEUR && (int)$souscription->operateur_id !== (int)$user->id) {
            abort(403);
        }

        $souscription->load(['client.mutuelle', 'projet', 'bienImmobilier', 'operateur']);
        $email = $souscription->client->email ?? $souscription->email;
        if (empty($email)) {
            return redirect()->back()->with('error', 'Email du client introuvable.');
        }

        $pdf = Pdf::loadView('documents.fiche_souscription', compact('souscription'))
            ->setPaper('a4');

        $sendRouteName = request()->route()?->getName();
        $downloadRouteName = $sendRouteName ? preg_replace('/\.send$/', '', $sendRouteName) : null;
        $ficheUrl = $downloadRouteName ? route($downloadRouteName, $souscription) : null;
        $sendUrl = $sendRouteName ? route($sendRouteName, $souscription) : null;

        try {
            Mail::to($email)->send(new FicheSouscriptionMail($souscription, $pdf->output()));
        } catch (\Throwable $e) {
            $resp = redirect()->back()->with('error', 'Échec d’envoi de la fiche de souscription par email.');
            if ($ficheUrl) $resp->with('fiche_souscription_url', $ficheUrl);
            if ($sendUrl) $resp->with('fiche_souscription_send_url', $sendUrl);
            return $resp;
        }

        $resp = redirect()->back()->with('success', "Fiche de souscription envoyée au client ({$email}).");
        if ($ficheUrl) $resp->with('fiche_souscription_url', $ficheUrl);
        if ($sendUrl) $resp->with('fiche_souscription_send_url', $sendUrl);
        return $resp;
    }

    /**
     * Affichage du formulaire de validation finale (DG)
     */
    public function confirmationForm(Souscription $souscription)
    {
        // Charger projet, client, paiements et attribution
        $souscription->load(['projet', 'client', 'paiements', 'attributionLot']);
        if (!$souscription->attributionLot) {
            return redirect()->route('dg.attribution.show', $souscription)
                ->with('warning', 'Attribuez un logement (lot/îlot) avant de confirmer ce dossier.');
        }

        // Dernier paiement (par date)
        $dernierPaiement = $souscription->paiements()->orderByDesc('date_paiement')->first();

        // Montant total payé (sur tous les types)
        $montantTotalPaye = $souscription->paiements()->sum('montant');

        // Validation finale existante
        $validationFinale = ValidationFinale::where('idsouscription', $souscription->id)->first();

        return view('dg.confirmation.show', [
            'souscription' => $souscription,
            'dernierPaiement' => $dernierPaiement,
            'montantTotalPaye' => $montantTotalPaye,
            'validationFinale' => $validationFinale,
        ]);
    }

    /**
     * Enregistrement de la validation finale et génération de la lettre
     */
    public function validerDefinitive(Request $request, Souscription $souscription)
    {
        $request->validate([
            'nom_client' => 'required|string|max:255',
            'prenom_client' => 'nullable|string|max:255',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'nullable|string|max:255',
            'numero_telephone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'lieu_residence' => 'nullable|string|max:255',
            'profession' => 'nullable|string|max:255',
            'montant_total_paye' => 'required|numeric',
            'date_dernier_paiement' => 'required|date'
            // lot et ilot_attribue retirés de la validation: récupérés directement depuis AttributionLot
        ]);

        // Récupérer les informations de lot/îlot depuis l'attribution existante
        $attrib = $souscription->attributionLot; // eager loaded dans confirmationForm, mais on sécurise
        $lot = $attrib?->lot;
        $ilotAttribue = $attrib?->ilot;

        $nomComplet = trim($request->nom_client);
        $prenom = $request->prenom_client;
        $nomSeul = $nomComplet;
        if (empty($prenom) && !empty($nomComplet)) {
            $parts = preg_split('/\s+/', $nomComplet);
            if (count($parts) > 1) {
                $prenom = array_pop($parts);
                $nomSeul = implode(' ', $parts);
            } else {
                $prenom = '';
            }
        }

        ValidationFinale::create([
            'idsouscription' => $souscription->id,
            'nom_client' => $nomSeul,
            'prenom_client' => $prenom ?? '',
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'numero_telephone' => $request->numero_telephone,
            'email' => $request->email,
            'lieu_residence' => $request->lieu_residence,
            'profession' => $request->profession,
            'idProjet' => $souscription->programme,
            'montant_total_paye' => $request->montant_total_paye,
            'date_dernier_paiement' => $request->date_dernier_paiement,
            'lot' => $lot,
            'ilot_attribue' => $ilotAttribue,
        ]);

        return redirect()->route('dg.confirmation.index')
            ->with('success', 'Validation finale enregistrée.')
            ->with('download_lettre_url', route('dg.souscriptions.lettre-definitive', $souscription));
    }

    public function downloadLettreDefinitive(Souscription $souscription)
    {
        $validation = ValidationFinale::where('idsouscription', $souscription->id)->first();
        if (!$validation) {
            return redirect()->back()->with('error', 'Validation finale introuvable pour cette souscription.');
        }
        $souscription->load(['client', 'projet', 'attributionLot', 'bienImmobilier']);
        $pdf = Pdf::loadView('documents.lettre_definitive', compact('souscription', 'validation'))
            ->setPaper('a4');
        return $pdf->stream("Lettre_Definitive_{$souscription->ref_souscription}.pdf");
    }

    /**
     * Voir les paiements d'une souscription (DG)
     */
    public function voirPaiements(Request $request, Souscription $souscription)
    {
        $query = $souscription->paiements()
            ->whereIn('type', ['FRAIS_DOSSIER', 'APPORT', 'PROJET'])
            ->with('comptable');

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('montant', 'like', "%{$search}%");
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date_paiement', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_paiement', '<=', $request->date_fin);
        }

        $paiements = $query->latest()->paginate(20);

        return view('dg.souscriptions.paiements', compact('souscription', 'paiements'));
    }
}
