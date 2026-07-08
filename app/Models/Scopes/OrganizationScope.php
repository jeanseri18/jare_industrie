<?php

namespace App\Models\Scopes;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrganizationScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        if (! app()->bound('currentOrganization')) {
            return;
        }

        $org = app('currentOrganization');

        if (! $org instanceof Organization) {
            return;
        }

        $builder->where($model->getTable().'.organization_id', $org->id);
    }
}
