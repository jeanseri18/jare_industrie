<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Projet extends Model
{
    protected $fillable = [
        'nom',
        'localisation',
        'superficie',
        'isduplex',
        'isterrains',
        'isvillabase',
        'isappartement',
        'prix_terrains',
        'prix_duplex',
        'prix_villa',
        'prix_appartement',
        'nb_logements',
        'pourcentage_apport',
        'frais_souscription',
        'est_actif',
        'est_mutuelle',
        'mutuelle_id',
        'cree_par'
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
        'isduplex' => 'boolean',
        'isterrains' => 'boolean',
        'isvillabase' => 'boolean',
        'isappartement' => 'boolean',
        'prix_terrains' => 'decimal:2',
        'prix_duplex' => 'decimal:2',
        'prix_villa' => 'decimal:2',
        'prix_appartement' => 'decimal:2',
        'est_actif' => 'boolean',
        'est_mutuelle' => 'boolean',
        'frais_souscription' => 'integer'
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
        return $this->hasMany(Souscription::class);
    }
}