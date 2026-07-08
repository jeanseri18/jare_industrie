<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use App\Support\CurrentOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mutuelle extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'nom',
        'code',
        'description',
        'taux_reduction',
        'est_active',
        'cree_par',
        'project_id',
        'site_web',
        'nom_contact',
        'telephone_contact',
        'email_contact'
    ];

    protected $casts = [
        'est_active' => 'boolean',
        'taux_reduction' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mutuelle) {
            if (empty($mutuelle->code)) {
                $orgId = $mutuelle->organization_id ?? CurrentOrganization::id();
                $query = static::withoutGlobalScopes();
                if ($orgId) {
                    $query->where('organization_id', $orgId);
                }
                $next = ($query->max('id') ?? 0) + 1;
                $mutuelle->code = 'MUT-'.str_pad((string) $next, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function creePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'project_id');
    }



    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function biens(): BelongsToMany
    {
        return $this->belongsToMany(BienImmobilier::class, 'bien_immobilier_mutuelle')
                    ->withPivot('prix_special')
                    ->withTimestamps();
    }
}