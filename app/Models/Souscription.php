<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Souscription extends Model
{
    protected $fillable = [
        'operateur_id',
        'client_id',
        'categorie_client',
        'nom_prenom',
        'date_naissance',
        'lieu_naissance',
        'nationalite',
        'nombre_enfants',
        'ayant_droit',
        'email',
        'salaire_mensuel',
        'situation_matrimoniale',
        'nature_piece',
        'numero_piece',
        'fichier_piece',
        'programme',
        'duree_contrat_mois',
        'date_debut',
        'date_fin',
        'type_logement',
        'mode_paiement',
        'valeur_souscription',
        'apport_initial',
        'frais_souscription',
        'statut'
    ];

    protected $casts = [
        'date_naissance' => 'date',
        'date_debut' => 'date',
        'date_fin' => 'date',
        'nombre_enfants' => 'integer',
        'duree_contrat_mois' => 'integer',
        'valeur_souscription' => 'integer',
        'apport_initial' => 'integer',
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


}