<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Logement extends Model
{
    const STATUT_DISPONIBLE = 'disponible';
    const STATUT_RESERVE = 'reserve';
    const STATUT_ATTRIBUE = 'attribue';
    const STATUT_PAYE = 'paye';

    protected $fillable = [
        'projet_id',
        'numero',
        'type',
        'superficie',
        'nombre_pieces',
        'nombre_chambres',
        'nombre_salles_bain',
        'prix',
        'description',
        'statut',
        'etage',
        'orientation',
        'balcon',
        'terrasse',
        'parking',
    ];

    protected $casts = [
        'superficie' => 'decimal:2',
        'prix' => 'decimal:2',
        'balcon' => 'boolean',
        'terrasse' => 'boolean',
        'parking' => 'boolean',
    ];

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    public function souscription(): HasOne
    {
        return $this->hasOne(Souscription::class);
    }

    public function lettreAttribution(): HasOne
    {
        return $this->hasOne(LettreAttribution::class);
    }

    public function isDisponible(): bool
    {
        return $this->statut === self::STATUT_DISPONIBLE;
    }

    public function isAttribue(): bool
    {
        return $this->statut === self::STATUT_ATTRIBUE;
    }
}
