<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BienImmobilier extends Model
{
    protected $fillable = [
        'idprojet',
        'titre',
        'nbre_piece',
        'nbre_salon',
        'nbre_douche',
        'nbre_cuisine',
        'nbre_placegarage',
        'surface_habitable',
        'surface_total',
        'nbre_salle_bain',
        'nbre_etage',
        'type',
        'prix',
        'frais_souscription',
        'pourcentage_apport',
        'apport_initial',
        'garage',
        'piscine',
        'terasse',
        'jardin',
        'dependance',
        'cour_avant',
        'cour_arriere',
        'terrasse_carrelee',
        'grand_sejour_carrele',
        'chambre_principale_carrelee',
        'chambre_principale_salle_eau',
        'chambre_principale_placards',
        'chambre_2_carrelee',
        'chambre_2_salle_eau',
        'chambre_2_placard',
        'wc_visiteur_carrele',
        'grande_cuisine_carrelee',
        'buanderie',
        'installation_chauffe_eau'
    ];

    protected $casts = [
        'nbre_piece' => 'integer',
        'nbre_salon' => 'integer',
        'nbre_douche' => 'integer',
        'nbre_cuisine' => 'integer',
        'nbre_placegarage' => 'integer',
        'surface_habitable' => 'decimal:2',
        'surface_total' => 'decimal:2',
        'nbre_salle_bain' => 'integer',
        'nbre_etage' => 'integer',
        'prix' => 'decimal:2',
        'frais_souscription' => 'integer',
        'pourcentage_apport' => 'decimal:2',
        'apport_initial' => 'decimal:2',
        'garage' => 'boolean',
        'piscine' => 'boolean',
        'terasse' => 'boolean',
        'jardin' => 'boolean',
        'dependance' => 'boolean',
        'cour_avant' => 'boolean',
        'cour_arriere' => 'boolean',
        'terrasse_carrelee' => 'boolean',
        'grand_sejour_carrele' => 'boolean',
        'chambre_principale_carrelee' => 'boolean',
        'chambre_principale_salle_eau' => 'boolean',
        'chambre_principale_placards' => 'boolean',
        'chambre_2_carrelee' => 'boolean',
        'chambre_2_salle_eau' => 'boolean',
        'chambre_2_placard' => 'boolean',
        'wc_visiteur_carrele' => 'boolean',
        'grande_cuisine_carrelee' => 'boolean',
        'buanderie' => 'boolean',
        'installation_chauffe_eau' => 'boolean'
    ];

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'idprojet');
    }

    public function mutuelles(): BelongsToMany
    {
        return $this->belongsToMany(Mutuelle::class, 'bien_immobilier_mutuelle')
                    ->withPivot('prix_special')
                    ->withTimestamps();
    }
}