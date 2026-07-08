<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\Projet;
use App\Models\ActivityLog;
use App\Models\Mutuelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjetController extends Controller
{
    public function index()
    {
        $projets = Projet::with('creePar')
            ->withCount(['projetLots', 'bien_immobiliers'])
            ->latest()
            ->paginate(config('pagination.per_page'))->withQueryString();
            
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
            'numero_agrement' => 'nullable|string|max:100',
            'date_agrement' => 'nullable|date',
            'localisation' => 'nullable|string|max:255',
            'titre_foncier' => 'nullable|string|max:150',
            'circonscription_fonciere' => 'nullable|string|max:150',
            'superficie' => 'nullable|numeric|min:0',
            'nb_logements' => 'nullable|integer|min:0',
            'est_actif' => 'boolean',
            'est_mutuelle' => 'boolean',
            'mutuelle_id' => 'nullable|exists:mutuelles,id'
        ]);

        $projet = Projet::create(array_merge($request->all(), [
            'cree_par' => Auth::id()
        ]));

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'create',
            'description' => "Création du projet: {$projet->nom}",
            'model_type' => Projet::class,
            'model_id' => $projet->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.projets.apres_creation', $projet);
    }

    /**
     * Proposition après création : saisir les lots ou revenir à la liste.
     */
    public function apresCreation(Projet $projet)
    {
        return view('dg.projets.apres_creation', compact('projet'));
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
            'numero_agrement' => 'nullable|string|max:100',
            'date_agrement' => 'nullable|date',
            'localisation' => 'nullable|string|max:255',
            'titre_foncier' => 'nullable|string|max:150',
            'circonscription_fonciere' => 'nullable|string|max:150',
            'superficie' => 'nullable|numeric|min:0',
            'nb_logements' => 'nullable|integer|min:0',
            'est_actif' => 'boolean',
            'est_mutuelle' => 'boolean',
            'mutuelle_id' => 'nullable|exists:mutuelles,id'
        ]);

        $projet->update($request->all());

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => Auth::id(),
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
            'user_id' => Auth::id(),
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
