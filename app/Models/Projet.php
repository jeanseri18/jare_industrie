<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Projet extends Model
{
    const STATUT_EN_COURS = 'en_cours';
    const STATUT_TERMINE = 'termine';
    const STATUT_ANNULE = 'annule';

    protected $fillable = [
        'nom',
        'description',
        'adresse',
        'ville',
        'date_debut',
        'date_fin_prevue',
        'date_fin_reelle',
        'statut',
        'nombre_logements',
        'cout_total',
        'avancement',
    ];

    protected $casts = [
        'date_debut' => 'date',
        'date_fin_prevue' => 'date',
        'date_fin_reelle' => 'date',
        'cout_total' => 'decimal:2',
        'avancement' => 'integer',
    ];

    public function logements(): HasMany
    {
        return $this->hasMany(Logement::class);
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class);
    }

    public function isEnCours(): bool
    {
        return $this->statut === self::STATUT_EN_COURS;
    }

    public function isTermine(): bool
    {
        return $this->statut === self::STATUT_TERMINE;
    }
}
