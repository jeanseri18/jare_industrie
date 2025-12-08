<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attestation extends Model
{
    protected $fillable = [
        'souscription_id',
        'numero_attestation',
        'date_emission',
        'fichier',
        'valide_par',
        'observations',
    ];

    protected $casts = [
        'date_emission' => 'date',
    ];

    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class);
    }
}
