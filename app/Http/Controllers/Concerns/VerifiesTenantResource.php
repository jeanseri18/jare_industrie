<?php

namespace App\Http\Controllers\Concerns;

use App\Models\Organization;
use App\Models\Souscription;
use App\Support\CurrentOrganization;

trait VerifiesTenantResource
{
    protected function ensureSouscriptionBelongsToTenant(Souscription $souscription): void
    {
        $org = CurrentOrganization::get();

        if (! $org instanceof Organization) {
            return;
        }

        if ((int) $souscription->organization_id !== (int) $org->id) {
            abort(404);
        }
    }

    protected function ensurePaiementBelongsToTenant($paiement): void
    {
        $paiement->loadMissing('souscription');

        if ($paiement->souscription) {
            $this->ensureSouscriptionBelongsToTenant($paiement->souscription);
        }
    }
}
