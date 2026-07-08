<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\Mutuelle;
use App\Models\Projet;
use App\Models\BienImmobilier;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class MutuelleController extends Controller
{
    public function index()
    {
        $mutuelles = Mutuelle::with('projet')
            ->latest()
            ->paginate(config('pagination.per_page'))->withQueryString();
            
        return view('dg.mutuelles.index', compact('mutuelles'));
    }

    public function getBiens(Projet $projet)
    {
        $biens = $projet->bien_immobiliers;
        return response()->json($biens);
    }

    public function create()
    {
        $projets = Projet::where('est_actif', true)->pluck('nom', 'id');
        return view('dg.mutuelles.create', compact('projets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:mutuelles',
            'description' => 'nullable|string',
            'est_active' => 'boolean',
            'project_id' => 'required|exists:projets,id',
            'site_web' => 'nullable|string|max:255',
            'nom_contact' => 'nullable|string|max:255',
            'telephone_contact' => 'nullable|string|max:20',
            'email_contact' => 'nullable|email|max:255',
            'biens' => 'nullable|array',
            'biens.*' => 'nullable|numeric|min:0'
        ]);

        $mutuelle = Mutuelle::create(array_merge(
            $request->except('biens'),
            ['cree_par' => auth()->id()]
        ));

        if ($request->has('biens')) {
            $syncData = [];
            foreach ($request->biens as $bienId => $prix) {
                if ($prix !== null) {
                    $syncData[$bienId] = ['prix_special' => $prix];
                }
            }
            $mutuelle->biens()->sync($syncData);
        }

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'description' => "Création de la mutuelle: {$mutuelle->nom}",
            'model_type' => Mutuelle::class,
            'model_id' => $mutuelle->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.mutuelles.index')
            ->with('success', 'Mutuelle créée avec succès');
    }

    public function edit(Mutuelle $mutuelle)
    {
        $mutuelle->load('biens');
        $projets = Projet::where('est_actif', true)->pluck('nom', 'id');
        return view('dg.mutuelles.edit', compact('mutuelle', 'projets'));
    }

    public function update(Request $request, Mutuelle $mutuelle)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:mutuelles,code,' . $mutuelle->id,
            'description' => 'nullable|string',
            'est_active' => 'boolean',
            'project_id' => 'required|exists:projets,id',
            'site_web' => 'nullable|string|max:255',
            'nom_contact' => 'nullable|string|max:255',
            'telephone_contact' => 'nullable|string|max:20',
            'email_contact' => 'nullable|email|max:255',
            'biens' => 'nullable|array',
            'biens.*' => 'nullable|numeric|min:0'
        ]);

        $mutuelle->update($request->except('biens'));

        if ($request->has('biens')) {
            $syncData = [];
            foreach ($request->biens as $bienId => $prix) {
                if ($prix !== null) {
                    $syncData[$bienId] = ['prix_special' => $prix];
                }
            }
            $mutuelle->biens()->sync($syncData);
        }

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'description' => "Modification de la mutuelle: {$mutuelle->nom}",
            'model_type' => Mutuelle::class,
            'model_id' => $mutuelle->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.mutuelles.index')
            ->with('success', 'Mutuelle mise à jour avec succès');
    }

    public function destroy(Mutuelle $mutuelle)
    {
        $mutuelleName = $mutuelle->nom;
        $mutuelle->delete();

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'description' => "Suppression de la mutuelle: {$mutuelleName}",
            'model_type' => Mutuelle::class,
            'model_id' => $mutuelle->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('dg.mutuelles.index')
            ->with('success', 'Mutuelle supprimée avec succès');
    }
}
