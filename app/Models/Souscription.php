<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Souscription extends Model
{
    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_VALIDEE = 'validee';
    const STATUT_REFUSEE = 'refusee';
    const STATUT_ANNULEE = 'annulee';

    protected $fillable = [
        'user_id',
        'client_id',
        'projet_id',
        'logement_id',
        'numero_souscription',
        'date_souscription',
        'statut',
        'montant_total',
        'montant_paye',
        'montant_restant',
        'date_validation',
        'valide_par',
        'observations',
    ];

    protected $casts = [
        'date_souscription' => 'date',
        'date_validation' => 'date',
        'montant_total' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'montant_restant' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class);
    }

    public function logement(): BelongsTo
    {
        return $this->belongsTo(Logement::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function attestation(): HasOne
    {
        return $this->hasOne(Attestation::class);
    }

    public function lettreAttribution(): HasOne
    {
        return $this->hasOne(LettreAttribution::class);
    }

    public function workflowEtats(): HasMany
    {
        return $this->hasMany(WorkflowEtat::class);
    }

    public function isValidee(): bool
    {
        return $this->statut === self::STATUT_VALIDEE;
    }

    public function isEnAttente(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }
}
