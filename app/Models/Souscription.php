<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Souscription extends Model
{
    protected $fillable = [
        'ref_souscription',
        'operateur_id',
        'client_id',
        'categorie_client',
        'nom',
        'prenom',
        'nom_prenom',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'nombre_enfants',
        'ayant_droit',
        'email',
        'telephone',
        'salaire_mensuel',
        'situation_matrimoniale',
        'nom_conjoint',
        'telephone_conjoint',
        'nature_piece',
        'numero_piece',
        'fichier_piece',
        'programme',
        'duree_contrat_mois',
        'date_debut',
        'date_fin',
        'type_logement',
        'bien_immobilier_id',
        'prix_logement',
        'mode_paiement',
        'valeur_souscription',
        'apport_initial',
        'apport_initial_paye_par_client',
        'frais_souscription',
        'statut',
        'statut_correction',
        'statut_precedent'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'nombre_enfants' => 'integer',
        'duree_contrat_mois' => 'integer',
        'valeur_souscription' => 'integer',
        'apport_initial' => 'integer',
        'apport_initial_paye_par_client' => 'boolean',
        'frais_souscription' => 'integer',
        'ayant_droit' => 'array'
    ];



    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class, 'dossier_id');
    }

    public function operateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'operateur_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'programme', 'id');
    }

    public function bienImmobilier(): BelongsTo
    {
        return $this->belongsTo(BienImmobilier::class, 'bien_immobilier_id');
    }

    public function attributionLot(): HasOne
    {
        return $this->hasOne(AttributionLot::class, 'id_souscription');
    }

    public function fraisDossier(): HasOne
    {
        return $this->hasOne(FraisDossier::class, 'id_souscription');
    }

    public function apportInitial(): HasOne
    {
        return $this->hasOne(ApportInitial::class, 'id_souscription');
    }
}
