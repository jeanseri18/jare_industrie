<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ActivityLog;
use App\Models\Mutuelle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()
            ->paginate(10);
            
        return view('dg.clients.index', compact('clients'));
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
            'user_id' => auth()->id(),
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
        
        return view('dg.clients.show', compact('client'));
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
            'user_id' => auth()->id(),
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
            'user_id' => auth()->id(),
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
}