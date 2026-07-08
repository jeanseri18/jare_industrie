<?php

namespace App\Services;

use App\Models\Client;
use App\Models\Souscription;
use App\Support\CurrentOrganization;

class ReferenceGenerator
{
    public static function nextClientRef(): string
    {
        $orgId = CurrentOrganization::id();
        $query = Client::withoutGlobalScopes()->orderByDesc('id');
        if ($orgId) {
            $query->where('organization_id', $orgId);
        }
        $nextNumber = ($query->value('id') ?? 0) + 1;

        return 'CLI-'.str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public static function nextSouscriptionRef(): string
    {
        $orgId = CurrentOrganization::id();
        $query = Souscription::withoutGlobalScopes()->orderByDesc('id');
        if ($orgId) {
            $query->where('organization_id', $orgId);
        }
        $nextNumber = ($query->value('id') ?? 0) + 1;

        return 'SOUS-'.str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
