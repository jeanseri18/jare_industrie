<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Souscription;
use App\Models\AttributionLot;
use App\Models\ValidationFinale;
use App\Models\Paiement;

class SouscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Souscription $souscription)
    {
        $souscription->load(['projet', 'attributionLot']);
        return view('dg.attribution.show', compact('souscription'));
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
    public function attribuer(Request $request, Souscription $souscription)
    {
        $request->validate([
            'numero_page_guide' => 'required|string|max:50',
            'lot' => 'required|string|max:50',
            'ilot' => 'required|string|max:50',
            'numero_villa' => 'required|string|max:50',
            'observations_internes' => 'nullable|string|max:1000',
        ]);

        AttributionLot::create([
            'idProjet' => $souscription->programme,
            'id_souscription' => $souscription->id,
            'type_logement' => $souscription->type_logement,
            'numero_page_guide' => $request->numero_page_guide,
            'lot' => $request->lot,
            'ilot' => $request->ilot,
            'numero_villa' => $request->numero_villa,
            'observations_internes' => $request->observations_internes,
        ]);

        return redirect()->route('dg.attribution.index')
            ->with('success', "Attribution enregistrée pour la souscription {$souscription->ref_souscription}.");
    }

    /**
     * Affichage du formulaire de validation finale (DG)
     */
    public function confirmationForm(Souscription $souscription)
    {
        // Charger projet, client, paiements et attribution
        $souscription->load(['projet', 'client', 'paiements', 'attributionLot']);

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
            'lieu_naissance' => 'required|string|max:255',
            'numero_telephone' => 'required|string|max:50',
            'email' => 'required|email|max:255',
            'lieu_residence' => 'required|string|max:255',
            'profession' => 'required|string|max:255',
            'montant_total_paye' => 'required|numeric',
            'date_dernier_paiement' => 'required|date'
            // lot et ilot_attribue retirés de la validation: récupérés directement depuis AttributionLot
        ]);

        // Récupérer les informations de lot/îlot depuis l'attribution existante
        $attrib = $souscription->attributionLot; // eager loaded dans confirmationForm, mais on sécurise
        $lot = $attrib?->lot;
        $ilotAttribue = $attrib?->ilot;

        ValidationFinale::create([
            'idsouscription' => $souscription->id,
            'nom_client' => $request->nom_client,
            'prenom_client' => $request->prenom_client,
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
            ->with('success', 'Validation finale enregistrée. La lettre définitive sera générée prochainement.');
    }
}
