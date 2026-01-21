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
        'nb_logements',
        'est_actif',
        'est_mutuelle',
        'mutuelle_id',
        'cree_par'
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
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
}