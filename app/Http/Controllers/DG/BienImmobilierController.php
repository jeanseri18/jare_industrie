<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\BienImmobilier;
use App\Models\Projet;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class BienImmobilierController extends Controller
{
    public function index(Projet $projet)
    {
        $biens = $projet->bien_immobiliers()
            ->latest()
            ->paginate(10);
            
        return view('dg.projets.biens.index', compact('projet', 'biens'));
    }

    public function create(Projet $projet)
    {
        return view('dg.projets.biens.create', compact('projet'));
    }

    public function store(Request $request, Projet $projet)
    {
        $request->validate([
            'titre' => 'required|string|max:150',
            'type' => 'required|in:duplex,appartement,villa,terrain',
            'nbre_piece' => 'nullable|integer|min:0',
            'nbre_salon' => 'nullable|integer|min:0',
            'nbre_douche' => 'nullable|integer|min:0',
            'nbre_cuisine' => 'nullable|integer|min:0',
            'nbre_place_garage' => 'nullable|integer|min:0',
            'surface_habitable' => 'nullable|numeric|min:0',
            'surface_total' => 'nullable|numeric|min:0',
            'nbre_salle_bain' => 'nullable|integer|min:0',
            'nbre_etage' => 'nullable|integer|min:0',
            'prix' => 'required|numeric|min:0',
            'frais_souscription' => 'required|numeric|min:0',
            'pourcentage_apport' => 'required|integer|min:0|max:100',
            'apport_initial' => 'nullable|numeric|min:0',
            'garage' => 'boolean',
            'piscine' => 'boolean',
            'terrasse' => 'boolean',
            'jardin' => 'boolean',
            'dependance' => 'boolean',
            'cour_avant' => 'boolean',
            'cour_arriere' => 'boolean',
            'terrasse_carrelee' => 'boolean',
            'grand_sejour_carrele' => 'boolean',
            'chambre_principale_carrelee' => 'boolean',
            'chambre_principale_salle_eau' => 'boolean',
            'chambre_principale_placards' => 'boolean',
            'chambre_2_carrelee' => 'boolean',
            'chambre_2_salle_eau' => 'boolean',
            'chambre_2_placard' => 'boolean',
            'wc_visiteur_carrele' => 'boolean',
            'grande_cuisine_carrelee' => 'boolean',
            'buanderie' => 'boolean',
            'installation_chauffe_eau' => 'boolean'
        ]);

        $bien = $projet->bien_immobiliers()->create(array_merge($request->all(), [
            'cree_par' => auth()->id()
        ]));

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'create',
            'description' => "Création du bien immobilier: {$bien->titre} pour le projet {$projet->nom}",
            'model_type' => BienImmobilier::class,
            'model_id' => $bien->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.projets.biens.index', $projet)
            ->with('success', 'Bien immobilier créé avec succès');
    }

    public function edit(BienImmobilier $bien)
    {
        $projet = $bien->projet;
        return view('dg.projets.biens.edit', compact('projet', 'bien'));
    }

    public function update(Request $request, BienImmobilier $bien)
    {
        $request->validate([
            'titre' => 'required|string|max:150',
            'type' => 'required|in:duplex,appartement,villa,terrain',
            'nbre_piece' => 'nullable|integer|min:0',
            'nbre_salon' => 'nullable|integer|min:0',
            'nbre_douche' => 'nullable|integer|min:0',
            'nbre_cuisine' => 'nullable|integer|min:0',
            'nbre_place_garage' => 'nullable|integer|min:0',
            'surface_habitable' => 'nullable|numeric|min:0',
            'surface_total' => 'nullable|numeric|min:0',
            'nbre_salle_bain' => 'nullable|integer|min:0',
            'nbre_etage' => 'nullable|integer|min:0',
            'prix' => 'required|numeric|min:0',
            'frais_souscription' => 'required|numeric|min:0',
            'pourcentage_apport' => 'required|integer|min:0|max:100',
            'apport_initial' => 'nullable|numeric|min:0',
            'garage' => 'boolean',
            'piscine' => 'boolean',
            'terrasse' => 'boolean',
            'jardin' => 'boolean',
            'dependance' => 'boolean',
            'cour_avant' => 'boolean',
            'cour_arriere' => 'boolean',
            'terrasse_carrelee' => 'boolean',
            'grand_sejour_carrele' => 'boolean',
            'chambre_principale_carrelee' => 'boolean',
            'chambre_principale_salle_eau' => 'boolean',
            'chambre_principale_placards' => 'boolean',
            'chambre_2_carrelee' => 'boolean',
            'chambre_2_salle_eau' => 'boolean',
            'chambre_2_placard' => 'boolean',
            'wc_visiteur_carrele' => 'boolean',
            'grande_cuisine_carrelee' => 'boolean',
            'buanderie' => 'boolean',
            'installation_chauffe_eau' => 'boolean'
        ]);

        $bien->update($request->all());
        $projet = $bien->projet;

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update',
            'description' => "Modification du bien immobilier: {$bien->titre} pour le projet {$projet->nom}",
            'model_type' => BienImmobilier::class,
            'model_id' => $bien->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('dg.projets.biens.index', $projet)
            ->with('success', 'Bien immobilier mis à jour avec succès');
    }

    public function destroy(BienImmobilier $bien)
    {
        $bienName = $bien->titre;
        $projet = $bien->projet;
        $bien->delete();

        // Enregistrer l'activité
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'delete',
            'description' => "Suppression du bien immobilier: {$bienName} pour le projet {$projet->nom}",
            'model_type' => BienImmobilier::class,
            'model_id' => $bien->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->route('dg.projets.biens.index', $projet)
            ->with('success', 'Bien immobilier supprimé avec succès');
    }
}