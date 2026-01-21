<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApportInitial extends Model
{
    protected $table = 'apports_initiaux';

    protected $fillable = [
        'id_souscription',
        'id_projet',
        'montant',
        'montant_paye',
        'montant_reste',
        'id_comptable',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'montant_paye' => 'decimal:2',
        'montant_reste' => 'decimal:2',
    ];

    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class, 'id_souscription');
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'id_projet');
    }

    public function comptable(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_comptable');
    }
}