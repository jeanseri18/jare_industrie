<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    const TYPE_INDIVIDUEL = 'individuel';
    const TYPE_MUTUELLE = 'mutuelle';
    const TYPE_INDIVIDUEL_BANQUE = 'individuel-banque';

    protected $fillable = [
        'user_id',
        'type_client',
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'ville',
        'code_postal',
        'pays',
        'date_naissance',
        'lieu_naissance',
        'profession',
        'salaire_mensuel',
        'situation_familiale',
        'nombre_enfants',
        'nom_mutuelle',
        'registre_commerce',
        'nif',
        'statut',
        'date_adhesion',
        'montant_total_souscription',
        'montant_paye',
        'montant_restant',
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_adhesion' => 'date',
        'salaire_mensuel' => 'decimal:2',
        'montant_total_souscription' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'montant_restant' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class);
    }

    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function isMutuelle(): bool
    {
        return $this->type_client === self::TYPE_MUTUELLE;
    }

    public function isIndividuel(): bool
    {
        return $this->type_client === self::TYPE_INDIVIDUEL;
    }

    public function isIndividuelBanque(): bool
    {
        return $this->type_client === self::TYPE_INDIVIDUEL_BANQUE;
    }
}
