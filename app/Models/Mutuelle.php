<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mutuelle extends Model
{
    protected $fillable = [
        'client_id',
        'nom_mutuelle',
        'registre_commerce',
        'nif',
        'adresse_siege',
        'telephone_mutuelle',
        'email_mutuelle',
        'president_nom',
        'president_telephone',
        'date_creation',
        'nombre_membres',
    ];

    protected $casts = [
        'date_creation' => 'date',
        'nombre_membres' => 'integer',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
