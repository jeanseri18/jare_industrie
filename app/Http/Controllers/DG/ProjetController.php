<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\ActivityLog;
use App\Models\Mutuelle;
use Illuminate\Http\Request;

class ProjetController extends Controller
{
    public function index()
    {
        $projets = Projet::with('creePar')
            ->latest()
            ->paginate(10);
            
        return view('dg.projets.index', compact('projets'));
    }

    public function create()
    {
        $mutuelles = Mutuelle::where('est_active', true)->get();
        return view('dg.projets.create', compact('mutuelles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'localisation' => 'nullable|string|max:255',
            'superficie' => 'nullable|numeric|min:0',
            'isduplex' => 'boolean',
            'isterrains' => 'boolean',
            'isvillabase' => 'boolean',
            'isappartement' => 'boolean',
            'prix_terrains' => 'nullable|numeric|min:0',
            'prix_duplex' => 'nullable|numeric|min:0',
            'prix_villa' => 'nullable|numeric|min:0',
            'prix_appartement' => 'nullable|numeric|min:0',
            'nb_logements' => 'nullable|integer|min:0',
            'pourcentage_apport' => 'required|integer|min:0|max:100',
            'frais_souscription' => 'required|numeric|min:0',
            'est_actif' => 'boolean',
            'est_mutuelle' => 'boolean',
            'mutuelle_id' => 'nullable|exists:mutuelles,id'
        ]);

        $projet = Projet::create(array_merge($request->all(), [
            'cree_par' => auth()->id()
        ]));

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'description' => "Création du projet: {$projet->nom}",
            'model_type' => Projet::class,
            'model_id' => $projet->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.projets.index')
            ->with('success', 'Projet créé avec succès');
    }

    public function edit(Projet $projet)
    {
        $mutuelles = Mutuelle::where('est_active', true)->get();
        return view('dg.projets.edit', compact('projet', 'mutuelles'));
    }

    public function update(Request $request, Projet $projet)
    {
        $request->validate([
            'nom' => 'required|string|max:150',
            'localisation' => 'nullable|string|max:255',
            'superficie' => 'nullable|numeric|min:0',
            'isduplex' => 'boolean',
            'isterrains' => 'boolean',
            'isvillabase' => 'boolean',
            'isappartement' => 'boolean',
            'prix_terrains' => 'nullable|numeric|min:0',
            'prix_duplex' => 'nullable|numeric|min:0',
            'prix_villa' => 'nullable|numeric|min:0',
            'prix_appartement' => 'nullable|numeric|min:0',
            'nb_logements' => 'nullable|integer|min:0',
            'pourcentage_apport' => 'required|integer|min:0|max:100',
            'frais_souscription' => 'required|numeric|min:0',
            'est_actif' => 'boolean',
            'est_mutuelle' => 'boolean',
            'mutuelle_id' => 'nullable|exists:mutuelles,id'
        ]);

        $projet->update($request->all());

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'description' => "Modification du projet: {$projet->nom}",
            'model_type' => Projet::class,
            'model_id' => $projet->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.projets.index')
            ->with('success', 'Projet mis à jour avec succès');
    }

    public function destroy(Projet $projet)
    {
        $projetName = $projet->nom;
        $projet->delete();

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'description' => "Suppression du projet: {$projetName}",
            'model_type' => Projet::class,
            'model_id' => $projet->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('dg.projets.index')
            ->with('success', 'Projet supprimé avec succès');
    }
}