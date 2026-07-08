<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjetIlot extends Model
{
    protected $table = 'projet_ilots';

    protected $fillable = [
        'id_projet',
        'ilot',
        'superficie_totale',
    ];

    protected $casts = [
        'superficie_totale' => 'decimal:2',
    ];

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'id_projet');
    }
}
