<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Client extends Model
{
    protected $fillable = [
        'ref_client',
        'nom',
        'prenom',
        'nom_prenom',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'categorie_client',
        'nombre_enfants',
        'ayant_droit',
        'situation_matrimoniale',
        'nom_conjoint',
        'telephone_conjoint',
        'telephone',
        'email',
        'salaire_mensuel',
        'nature_piece',
        'numero_piece',
        'fichier_piece',
        'mutuelle_id'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'nombre_enfants' => 'integer',
        'salaire_mensuel' => 'integer'
    ];

    public function mutuelle(): BelongsTo
    {
        return $this->belongsTo(Mutuelle::class);
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class);
    }

    public function lastSouscription(): HasOne
    {
        return $this->hasOne(Souscription::class)->latestOfMany();
    }
}
