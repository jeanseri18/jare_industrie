<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    const TYPE_FRAIS_SOUSCRIPTION = 'frais_souscription';
    const TYPE_CONTRIBUTION_INITIALE = 'contribution_initiale';
    const TYPE_ECHEANCE = 'echeance';
    const TYPE_FINAL = 'final';
    const TYPE_TEMPERAMENT = 'tempérament';

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_PAYE = 'paye';
    const STATUT_ANNULE = 'annule';
    const STATUT_REMBOURSE = 'rembourse';

    protected $fillable = [
        'user_id',
        'client_id',
        'souscription_id',
        'numero_paiement',
        'type',
        'montant',
        'date_paiement',
        'date_echeance',
        'statut',
        'methode_paiement',
        'reference_paiement',
        'observations',
        'recu_paiement',
        'est_tempérament',
        'montant_total_tempérament',
        'nombre_tranches',
        'montant_tranche',
        'statut_tempérament',
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'date_echeance' => 'date',
        'montant' => 'decimal:2',
        'est_tempérament' => 'boolean',
        'montant_total_tempérament' => 'decimal:2',
        'montant_tranche' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class);
    }

    public function estPaye(): bool
    {
        return $this->statut === self::STATUT_PAYE;
    }

    public function estTempérament(): bool
    {
        return $this->est_tempérament;
    }

    public function estTempéramentSoldé(): bool
    {
        return $this->est_tempérament && $this->statut_tempérament === 'soldé';
    }

    public function isEnAttente(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }
}
