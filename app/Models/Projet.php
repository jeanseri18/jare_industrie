<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Projet extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'nom',
        'numero_agrement',
        'date_agrement',
        'localisation',
        'titre_foncier',
        'circonscription_fonciere',
        'superficie',
        'nb_logements',
        'est_actif',
        'est_mutuelle',
        'mutuelle_id',
        'cree_par'
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
        'date_agrement' => 'date',
        'est_actif' => 'boolean',
        'est_mutuelle' => 'boolean'
    ];

    public function mutuelle(): BelongsTo
    {
        return $this->belongsTo(Mutuelle::class);
    }

    public function creePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class, 'programme', 'id');
    }

    public function bien_immobiliers(): HasMany
    {
        return $this->hasMany(BienImmobilier::class, 'idprojet');
    }

    public function projetLots(): HasMany
    {
        return $this->hasMany(ProjetLot::class, 'id_projet');
    }

    public function projetIlots(): HasMany
    {
        return $this->hasMany(ProjetIlot::class, 'id_projet');
    }
}
