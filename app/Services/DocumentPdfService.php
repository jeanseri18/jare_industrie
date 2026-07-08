<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationBranding;
use App\Models\Paiement;
use App\Models\Souscription;
use App\Support\CurrentOrganization;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfDocument;

class DocumentPdfService
{
    public function brand(): ?OrganizationBranding
    {
        $org = CurrentOrganization::get();
        if ($org instanceof Organization) {
            $org->loadMissing('branding');
            if ($org->branding) {
                return $org->branding;
            }
        }

        return OrganizationBranding::query()->orderBy('organization_id')->first();
    }

    public function resolveBrand(array $data = []): ?OrganizationBranding
    {
        if (($data['brand'] ?? null) instanceof OrganizationBranding) {
            return $data['brand'];
        }

        if (($data['souscription'] ?? null) instanceof Souscription) {
            return $this->forSouscription($data['souscription']);
        }

        if (($data['paiement'] ?? null) instanceof Paiement) {
            return $this->forPaiement($data['paiement']);
        }

        return $this->brand();
    }

    /** @param  array<string, mixed>  $data */
    public function withBrand(array $data): array
    {
        return array_merge($data, [
            'brand' => $this->resolveBrand($data),
        ]);
    }

    public function render(string $view, array $data = [], string $paper = 'a4', string $orientation = 'portrait'): DomPdfDocument
    {
        $pdf = Pdf::loadView($view, $this->withBrand($data))->setPaper($paper, $orientation);

        return $pdf;
    }

    public function stream(string $view, array $data, string $filename, string $paper = 'a4', string $orientation = 'portrait')
    {
        return $this->render($view, $data, $paper, $orientation)->stream($filename);
    }

    public function forSouscription(Souscription $souscription): ?OrganizationBranding
    {
        $souscription->loadMissing('organization.branding');

        if ($souscription->organization?->branding) {
            return $souscription->organization->branding;
        }

        if ($souscription->organization_id) {
            return OrganizationBranding::where('organization_id', $souscription->organization_id)->first();
        }

        return $this->brand();
    }

    public function forPaiement(Paiement $paiement): ?OrganizationBranding
    {
        $paiement->loadMissing('souscription.organization.branding');

        if ($paiement->souscription) {
            return $this->forSouscription($paiement->souscription);
        }

        return $this->brand();
    }

    public function bindOrganizationFromSouscription(Souscription $souscription): void
    {
        $souscription->loadMissing('organization.branding');

        if ($souscription->organization instanceof Organization) {
            CurrentOrganization::set($souscription->organization);
        }
    }

    public function bindOrganizationFromPaiement(Paiement $paiement): void
    {
        $paiement->loadMissing('souscription.organization.branding');

        if ($paiement->souscription) {
            $this->bindOrganizationFromSouscription($paiement->souscription);
        }
    }
}
