<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LettreAttribution extends Model
{
    protected $fillable = [
        'souscription_id',
        'logement_id',
        'numero_attribution',
        'date_attribution',
        'date_signature',
        'fichier',
        'signe_par',
        'observations',
    ];

    protected $casts = [
        'date_attribution' => 'date',
        'date_signature' => 'date',
    ];

    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class);
    }

    public function logement(): BelongsTo
    {
        return $this->belongsTo(Logement::class);
    }
}
