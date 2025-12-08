<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkflowEtat extends Model
{
    const PHASE_FRAIS_DOSSIER = 'frais_dossier';
    const PHASE_APPORT_INITIAL = 'apport_initial';
    const PHASE_ATTRIBUTION = 'attribution';
    const PHASE_PAIEMENT_PROJET = 'paiement_projet';

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_VALIDE = 'valide';
    const STATUT_REJETE = 'rejete';
    const STATUT_ANNULE = 'annule';

    protected $fillable = [
        'souscription_id',
        'phase',
        'statut',
        'traite_par',
        'date_traitement',
        'observations',
        'montant_requis',
        'montant_paye',
        'accepte_tempérament',
    ];

    protected $casts = [
        'date_traitement' => 'datetime',
        'montant_requis' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'accepte_tempérament' => 'boolean',
    ];

    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class);
    }

    public function traitePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traite_par');
    }

    public function estPhaseFraisDossier(): bool
    {
        return $this->phase === self::PHASE_FRAIS_DOSSIER;
    }

    public function estPhaseApportInitial(): bool
    {
        return $this->phase === self::PHASE_APPORT_INITIAL;
    }

    public function estPhaseAttribution(): bool
    {
        return $this->phase === self::PHASE_ATTRIBUTION;
    }

    public function estPhasePaiementProjet(): bool
    {
        return $this->phase === self::PHASE_PAIEMENT_PROJET;
    }

    public function estValide(): bool
    {
        return $this->statut === self::STATUT_VALIDE;
    }

    public function estEnAttente(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    public function accepteTempérament(): bool
    {
        return $this->accepte_tempérament;
    }
}