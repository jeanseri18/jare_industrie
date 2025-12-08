<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    const TYPE_CNI = 'CNI';
    const TYPE_PASSEPORT = 'passeport';
    const TYPE_JUSTIFICATIF_MUTUELLE = 'justificatif_mutuelle';
    const TYPE_JUSTIFICATIF_BANCAIRE = 'justificatif_bancaire';
    const TYPE_ACTE_NAISSANCE = 'acte_naissance';
    const TYPE_CERTIFICAT_MARIAGE = 'certificat_mariage';
    const TYPE_AUTRE = 'autre';

    const STATUT_EN_ATTENTE = 'en_attente';
    const STATUT_VALIDE = 'valide';
    const STATUT_REJETE = 'rejete';

    protected $fillable = [
        'client_id',
        'type_document',
        'nom_fichier',
        'chemin_fichier',
        'extension',
        'taille',
        'statut',
        'valide_par',
        'date_validation',
        'observations',
    ];

    protected $casts = [
        'date_validation' => 'datetime',
        'taille' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function validePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function estValide(): bool
    {
        return $this->statut === self::STATUT_VALIDE;
    }

    public function estEnAttente(): bool
    {
        return $this->statut === self::STATUT_EN_ATTENTE;
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->chemin_fichier);
    }
}