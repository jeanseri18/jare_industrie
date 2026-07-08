<?php

namespace App\Support;

use App\Models\Organization;

class CurrentOrganization
{
    public static function get(): ?Organization
    {
        if (! app()->bound('currentOrganization')) {
            return null;
        }

        $org = app('currentOrganization');

        return $org instanceof Organization ? $org : null;
    }

    public static function set(?Organization $organization): void
    {
        if ($organization) {
            app()->instance('currentOrganization', $organization);
        } else {
            app()->forgetInstance('currentOrganization');
        }
    }

    public static function id(): ?int
    {
        return static::get()?->id;
    }

    public static function branding()
    {
        return static::get()?->branding;
    }
}
