<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ActivityLog;
use App\Models\Mutuelle;
use App\Models\Projet;
use App\Models\User;
use App\Services\DocumentPdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request)
            ->with(['mutuelle', 'lastSouscription.projet']);

        $clients = $query->paginate(config('pagination.per_page'))->withQueryString();

        $projets = Projet::orderBy('nom')->get();
        $mutuelles = Mutuelle::where('est_active', true)->orderBy('nom')->get();

        return view('dg.clients.index', compact('clients', 'projets', 'mutuelles'));
    }

    public function create()
    {
        $mutuelles = Mutuelle::where('est_active', true)->get();
        return view('dg.clients.create', compact('mutuelles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients',
            'telephone' => 'nullable|string|max:50',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'nationalite' => 'required|string|max:100',
            'nombre_enfants' => 'nullable|integer|min:0',
            'ayant_droit' => 'nullable|string',
            'situation_matrimoniale' => 'required|in:celibataire,marie,divorce,veuf',
            'categorie_client' => 'required|in:individuel,association,syndicat,diaspora,mutuelle',
            'salaire_mensuel' => 'nullable|integer|min:0',
            'nature_piece' => 'nullable|in:cni,passeport,carte_consulaire,id',
            'numero_piece' => 'nullable|string|max:100',
            'fichier_piece' => 'nullable|string|max:255',
            'mutuelle_id' => 'nullable|exists:mutuelles,id'
        ]);

        $client = Client::create($request->all());

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => "Création du client: {$client->nom_prenom}",
            'model_type' => Client::class,
            'model_id' => $client->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.clients.index')
            ->with('success', 'Client créé avec succès');
    }

    public function show(Client $client)
    {
        $client->load(['souscriptions' => function($query) {
            $query->with('projet')->latest();
        }, 'mutuelle']);

        $clientUser = $this->findUserForClient($client);
        
        foreach ($client->souscriptions as $souscription) {
            if ($souscription->programme && is_numeric($souscription->programme) && !$souscription->projet) {
                $projet = \App\Models\Projet::find($souscription->programme);
                $souscription->nom_programme = $projet ? $projet->nom : $souscription->programme;
            } elseif ($souscription->projet) {
                $souscription->nom_programme = $souscription->projet->nom;
            } else {
                $souscription->nom_programme = $souscription->programme;
            }
        }
        
        return view('dg.clients.show', compact('client', 'clientUser'));
    }

    public function edit(Client $client)
    {
        $mutuelles = Mutuelle::where('est_active', true)->get();
        return view('dg.clients.edit', compact('client', 'mutuelles'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom_prenom' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:clients,email,' . $client->id,
            'telephone' => 'nullable|string|max:50',
            'date_naissance' => 'required|date',
            'lieu_naissance' => 'required|string|max:255',
            'nationalite' => 'required|string|max:100',
            'nombre_enfants' => 'nullable|integer|min:0',
            'ayant_droit' => 'nullable|string',
            'situation_matrimoniale' => 'required|in:celibataire,marie,divorce,veuf',
            'categorie_client' => 'required|in:individuel,association,syndicat,diaspora,mutuelle',
            'salaire_mensuel' => 'nullable|integer|min:0',
            'nature_piece' => 'nullable|in:cni,passeport,carte_consulaire,id',
            'numero_piece' => 'nullable|string|max:100',
            'fichier_piece' => 'nullable|string|max:255',
            'mutuelle_id' => 'nullable|exists:mutuelles,id'
        ]);

        $client->update($request->all());

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => "Modification des informations du client: {$client->nom_prenom}",
            'model_type' => Client::class,
            'model_id' => $client->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.clients.index')
            ->with('success', 'Informations client mises à jour avec succès');
    }

    public function destroy(Client $client)
    {
        $clientName = $client->nom_prenom;
        $client->delete();

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'delete',
            'description' => "Suppression du client: {$clientName}",
            'model_type' => Client::class,
            'model_id' => $client->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('dg.clients.index')
            ->with('success', 'Client supprimé avec succès');
    }

    public function exportPdf(Request $request)
    {
        $clients = $this->buildFilteredQuery($request)
            ->with(['mutuelle', 'lastSouscription.projet'])
            ->get();

        return app(DocumentPdfService::class)
            ->render('documents.clients_export', [
                'clients' => $clients,
                'filters' => $request->only(['search', 'date_creation', 'projet_id', 'mutuelle_id']),
            ], 'a4', 'landscape')
            ->stream('clients.pdf');
    }

    public function exportExcel(Request $request)
    {
        $clients = $this->buildFilteredQuery($request)
            ->with(['mutuelle', 'lastSouscription.projet'])
            ->get();

        $response = new StreamedResponse(function () use ($clients) {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, [
                'ID',
                'Référence',
                'Nom et Prénom',
                'Téléphone',
                'Email',
                'Catégorie',
                'Mutuelle',
                'Projet',
                'Date de création'
            ], ';');

            foreach ($clients as $c) {
                fputcsv($out, [
                    $c->id,
                    $c->ref_client,
                    $c->nom_prenom,
                    $c->telephone,
                    $c->email,
                    $c->categorie_client,
                    $c->mutuelle?->nom,
                    $c->lastSouscription?->projet?->nom,
                    optional($c->created_at)->format('d/m/Y H:i')
                ], ';');
            }

            fclose($out);
        });

        $filename = 'clients.csv';
        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }

    public function createAccount(Request $request, Client $client)
    {
        if ($this->findUserForClient($client)) {
            return redirect()->route('dg.clients.show', $client)
                ->with('error', 'Ce client possède déjà un compte portail.');
        }

        $loginEmail = $this->resolveClientLoginEmail($client);
        if (! $loginEmail) {
            return redirect()->route('dg.clients.show', $client)
                ->with('error', 'Ajoutez un email au client ou une référence client avant de créer un compte.');
        }

        if (User::where('email', $loginEmail)->exists()) {
            return redirect()->route('dg.clients.show', $client)
                ->with('error', "L'adresse {$loginEmail} est déjà utilisée par un autre compte.");
        }

        $request->validate([
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $password = $request->filled('password')
            ? $request->password
            : substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789'), 0, 10);

        $user = User::create([
            'organization_id' => $client->organization_id,
            'name' => $client->nom_prenom,
            'email' => $loginEmail,
            'password' => Hash::make($password),
            'role' => User::ROLE_CLIENT,
            'telephone' => $client->telephone,
            'email_verified_at' => now(),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => "Création du compte portail client: {$user->name}",
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.clients.show', $client)
            ->with('success', 'Compte client créé avec succès.')
            ->with('client_credentials', [
                'email' => $loginEmail,
                'password' => $password,
                'nom_client' => $client->nom_prenom,
            ]);
    }

    public function editPassword(Client $client)
    {
        $user = $this->findUserForClient($client);
        if (!$user) {
            return redirect()->route('dg.clients.show', $client)
                ->with('error', 'Aucun compte utilisateur client trouvé pour ce client.');
        }

        return view('dg.clients.password', compact('client', 'user'));
    }

    public function updatePassword(Request $request, Client $client)
    {
        $user = $this->findUserForClient($client);
        if (!$user) {
            return redirect()->route('dg.clients.show', $client)
                ->with('error', 'Aucun compte utilisateur client trouvé pour ce client.');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'update',
            'description' => "Modification du mot de passe du client: {$user->name}",
            'model_type' => User::class,
            'model_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.clients.show', $client)
            ->with('success', 'Mot de passe client mis à jour avec succès');
    }

    private function buildFilteredQuery(Request $request)
    {
        $query = Client::query()->latest();

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

        if ($request->filled('mutuelle_id')) {
            $query->where('mutuelle_id', $request->mutuelle_id);
        }

        if ($request->filled('projet_id')) {
            $projetId = $request->projet_id;
            $query->whereHas('souscriptions', function($q) use ($projetId) {
                $q->where('programme', $projetId);
            });
        }

        return $query;
    }

    private function findUserForClient(Client $client): ?User
    {
        $email = $this->resolveClientLoginEmail($client);
        if ($email) {
            $u = User::where('email', $email)->first();
            if ($u && $u->isClient()) {
                return $u;
            }
        }

        return null;
    }

    private function resolveClientLoginEmail(Client $client): ?string
    {
        if (! empty($client->email)) {
            return $client->email;
        }

        if (! empty($client->ref_client)) {
            return strtolower($client->ref_client).'@jarelinstrudie.local';
        }

        return null;
    }
}
