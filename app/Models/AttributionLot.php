<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributionLot extends Model
{
    use HasFactory;

    protected $table = 'attribution_lots';

    protected $fillable = [
        'idProjet',
        'id_souscription',
        'type_logement',
        'numero_page_guide',
        'lot',
        'ilot',
        'numero_villa',
        'observations_internes'
    ];

    protected $casts = [
        'observations_internes' => 'string'
    ];

    public function projet()
    {
        return $this->belongsTo(Projet::class, 'idProjet');
    }

    public function souscription()
    {
        return $this->belongsTo(Souscription::class, 'id_souscription');
    }
}