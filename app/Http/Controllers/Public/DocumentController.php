<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Concerns\VerifiesTenantResource;
use App\Http\Controllers\Controller;
use App\Models\Paiement;
use App\Models\Souscription;
use App\Models\ValidationFinale;
use App\Services\DocumentPdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    use VerifiesTenantResource;

    public function __construct(private DocumentPdfService $pdfService) {}

    public function recu(Paiement $paiement)
    {
        $this->ensurePaiementBelongsToTenant($paiement);
        $this->pdfService->bindOrganizationFromPaiement($paiement);
        $paiement->load(['souscription.client', 'souscription.projet', 'comptable']);
        $asPdf = true;

        return $this->pdfService
            ->render('comptable.recu', compact('paiement', 'asPdf'), 'a5', 'landscape')
            ->stream("Recu_{$paiement->reference}.pdf");
    }

    public function preuvePaiement(Paiement $paiement)
    {
        $this->ensurePaiementBelongsToTenant($paiement);

        if (empty($paiement->preuve_paiement)) {
            abort(404);
        }

        $path = ltrim((string) $paiement->preuve_paiement, '/');

        if (! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        return response()->file(Storage::disk('public')->path($path));
    }

    public function attestationReservation(Souscription $souscription)
    {
        $this->ensureSouscriptionBelongsToTenant($souscription);
        $this->pdfService->bindOrganizationFromSouscription($souscription);
        $souscription->load(['client', 'projet', 'attributionLot', 'bienImmobilier']);

        if (! $souscription->attributionLot) {
            abort(404);
        }

        return $this->pdfService
            ->render('documents.attestation_reservation', compact('souscription'))
            ->stream("Attestation_Reservation_{$souscription->ref_souscription}.pdf");
    }

    public function ficheSouscription(Souscription $souscription)
    {
        $this->ensureSouscriptionBelongsToTenant($souscription);
        $this->pdfService->bindOrganizationFromSouscription($souscription);
        $souscription->load(['client.mutuelle', 'projet.bien_immobiliers', 'bienImmobilier', 'operateur']);

        return $this->pdfService
            ->render('documents.fiche_souscription', compact('souscription'))
            ->stream("Fiche_Souscription_{$souscription->ref_souscription}.pdf");
    }

    public function contratReservation(Souscription $souscription)
    {
        $this->ensureSouscriptionBelongsToTenant($souscription);
        $this->pdfService->bindOrganizationFromSouscription($souscription);
        $souscription->load(['client', 'projet', 'bienImmobilier']);

        return $this->pdfService
            ->render('documents.contrat_reservation', compact('souscription'))
            ->stream("Contrat_Reservation_{$souscription->ref_souscription}.pdf");
    }

    public function lettreDefinitive(Souscription $souscription)
    {
        $this->ensureSouscriptionBelongsToTenant($souscription);
        $this->pdfService->bindOrganizationFromSouscription($souscription);
        $validation = ValidationFinale::where('idsouscription', $souscription->id)->firstOrFail();
        $souscription->load(['client', 'projet', 'attributionLot', 'bienImmobilier']);

        return $this->pdfService
            ->render('documents.lettre_definitive', compact('souscription', 'validation'))
            ->stream("Lettre_Definitive_{$souscription->ref_souscription}.pdf");
    }

    public function etatVersements(Request $request, Souscription $souscription)
    {
        $this->ensureSouscriptionBelongsToTenant($souscription);
        $this->pdfService->bindOrganizationFromSouscription($souscription);
        $souscription->load(['client', 'projet', 'attributionLot', 'paiements']);
        $paiements = $souscription->paiements()->where('statut', 'payé')->orderBy('date_paiement')->get();

        return view('documents.etat_versements', $this->pdfService->withBrand(compact('souscription', 'paiements')));
    }
}
