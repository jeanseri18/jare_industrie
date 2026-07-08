<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\BienImmobilier;
use App\Models\Projet;
use Illuminate\Http\Request;

class BienImmobilierController extends Controller
{
    public function index(Projet $projet)
    {
        if ($projet->projetLots()->doesntExist()) {
            return redirect()->route('dg.projets.index')
                ->with('error', 'Créez d’abord au moins un lot pour ce projet (menu « Îlots & lots ») avant d’accéder aux biens.');
        }

        $biens = $projet->bien_immobiliers()
            ->latest()
            ->paginate(config('pagination.per_page'))->withQueryString();
            
        return view('dg.projets.biens.index', compact('projet', 'biens'));
    }

    public function create(Projet $projet)
    {
        if ($projet->projetLots()->doesntExist()) {
            return redirect()->route('dg.projets.index')
                ->with('error', 'Créez d’abord au moins un lot pour ce projet (menu « Îlots & lots ») avant d’ajouter des biens.');
        }

        return view('dg.projets.biens.create', compact('projet'));
    }

    public function store(Request $request, Projet $projet)
    {
        if ($projet->projetLots()->doesntExist()) {
            return redirect()->route('dg.projets.index')
                ->with('error', 'Créez d’abord au moins un lot pour ce projet avant d’ajouter des biens.');
        }

        $request->validate([
            'titre' => 'required|string|max:150',
            'type' => 'required|in:duplex,appartement,villa,terrain,etage,villa basse,villa +R1,autre',
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
            'installation_chauffe_eau' => 'boolean',
            'ilot' => 'nullable|string|max:50',
        ]);

        $data = $request->all();
        unset($data['ilot']);
        $typeMap = [
            'villa' => 'villa basse',
            'Villa' => 'villa basse',
        ];
        $data['type'] = $typeMap[$data['type']] ?? $data['type'];
        if (isset($data['nbre_place_garage'])) {
            $data['nbre_placegarage'] = $data['nbre_place_garage'];
            unset($data['nbre_place_garage']);
        }
        if (isset($data['terrasse'])) {
            $data['terasse'] = $data['terrasse'];
            unset($data['terrasse']);
        }

        $bien = $projet->bien_immobiliers()->create($data);

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

        $url = route('dg.projets.biens.apres_creation', [$projet, $bien]);
        if ($request->filled('ilot')) {
            $url .= '?ilot='.urlencode((string) $request->input('ilot'));
        }

        return redirect($url);
    }

    /**
     * Après création d’un bien : liste des biens, autre bien, ou retour îlots / lots.
     */
    public function apresCreation(Projet $projet, BienImmobilier $bien, Request $request)
    {
        if ((int) $bien->idprojet !== (int) $projet->id) {
            abort(404);
        }

        $ilot = $request->query('ilot');

        return view('dg.projets.biens.apres_creation', compact('projet', 'bien', 'ilot'));
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
            'type' => 'required|in:duplex,appartement,villa,terrain,etage,villa basse,villa +R1,autre',
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

        $data = $request->all();
        $typeMap = [
            'villa' => 'villa basse',
            'Villa' => 'villa basse',
        ];
        $data['type'] = $typeMap[$data['type']] ?? $data['type'];
        if (isset($data['nbre_place_garage'])) {
            $data['nbre_placegarage'] = $data['nbre_place_garage'];
            unset($data['nbre_place_garage']);
        }
        if (isset($data['terrasse'])) {
            $data['terasse'] = $data['terrasse'];
            unset($data['terrasse']);
        }

        $bien->update($data);
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
