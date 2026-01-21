<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ValidationFinale extends Model
{
    protected $table = 'validation_finale';
    
    protected $fillable = [
        'idsouscription',
        'nom_client',
        'prenom_client',
        'date_naissance',
        'lieu_naissance',
        'numero_telephone',
        'email',
        'lieu_residence',
        'profession',
        'idProjet',
        'montant_total_paye',
        'date_dernier_paiement',
        'lot',
        'ilot_attribue'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_dernier_paiement' => 'date',
        'montant_total_paye' => 'decimal:2'
    ];

    public function souscription(): BelongsTo
    {
        return $this->belongsTo(Souscription::class, 'idsouscription');
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'idProjet');
    }
}