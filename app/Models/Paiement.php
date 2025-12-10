<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Paiement extends Model
{
    protected $fillable = [
        'dossier_id',
        'type',
        'montant',
        'mode',
        'reference',
        'date_paiement',
        'comptable_id',
        'valide_at',
        'cree_at'
    ];

    protected $casts = [
        'date_paiement' => 'date',
        'valide_at' => 'datetime',
        'cree_at' => 'datetime',
        'montant' => 'decimal:2'
    ];

    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class, 'dossier_id');
    }

    public function comptable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'comptable_id');
    }
}