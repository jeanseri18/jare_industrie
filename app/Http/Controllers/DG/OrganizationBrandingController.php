<?php

namespace App\Http\Controllers\DG;

use App\Http\Controllers\Controller;
use App\Models\OrganizationBranding;
use App\Services\DocumentPdfService;
use App\Support\CurrentOrganization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrganizationBrandingController extends Controller
{
    public function edit()
    {
        $organization = CurrentOrganization::get();
        $branding = $organization->branding ?? OrganizationBranding::create([
            'organization_id' => $organization->id,
        ]);

        return view('dg.parametres.identite', compact('organization', 'branding'));
    }

    public function update(Request $request)
    {
        $organization = CurrentOrganization::get();
        $branding = $organization->branding;

        $validated = $request->validate([
            'legal_name' => 'nullable|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'primary_color' => 'nullable|string|max:7',
            'secondary_color' => 'nullable|string|max:7',
            'accent_color' => 'nullable|string|max:7',
            'director_name' => 'nullable|string|max:255',
            'director_title' => 'nullable|string|max:255',
            'rccm' => 'nullable|string|max:100',
            'cc' => 'nullable|string|max:100',
            'tax_id' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:50',
            'whatsapp' => 'nullable|string|max:50',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'footer_text' => 'nullable|string|max:2000',
            'contract_clauses' => 'nullable|string',
            'logo' => 'nullable|image|max:2048',
            'watermark' => 'nullable|image|max:4096',
            'signature' => 'nullable|image|max:2048',
        ]);

        $branding->fill(collect($validated)->except(['logo', 'watermark', 'signature'])->toArray());

        $basePath = 'organizations/'.$organization->id;

        foreach (['logo' => 'logo', 'watermark' => 'watermark', 'signature' => 'signature'] as $field => $name) {
            if ($request->hasFile($field)) {
                $path = $request->file($field)->store($basePath, 'public');
                $branding->{$name.'_path'} = $path;
            }
        }

        $branding->save();

        return redirect()->route('dg.parametres.identite')->with('success', 'Identité visuelle mise à jour.');
    }

    public function preview(DocumentPdfService $pdfService)
    {
        return $pdfService
            ->render('documents.fiche_souscription', [
                'souscription' => $this->previewSouscription(),
            ])
            ->stream('apercu_fiche_souscription.pdf');
    }

    private function previewSouscription()
    {
        $s = new \App\Models\Souscription([
            'ref_souscription' => 'SOUS-PREVIEW',
            'nom' => 'DUPONT',
            'prenom' => 'Jean',
            'nom_prenom' => 'Jean DUPONT',
            'email' => 'preview@example.com',
            'telephone' => '+225 07 00 00 00 00',
            'valeur_souscription' => 25000000,
            'apport_initial' => 2500000,
            'frais_souscription' => 500000,
            'mode_paiement' => 'Mensualités',
            'type_logement' => 'VILLA F4',
            'statut' => 'en_attente',
            'created_at' => now(),
        ]);
        $s->id = 0;
        $s->setRelation('client', new \App\Models\Client([
            'nom' => 'DUPONT', 'prenom' => 'Jean', 'email' => 'preview@example.com',
        ]));
        $s->setRelation('projet', new \App\Models\Projet(['nom' => 'Programme Démo']));
        $s->setRelation('bienImmobilier', null);
        $s->setRelation('operateur', null);

        return $s;
    }
}
