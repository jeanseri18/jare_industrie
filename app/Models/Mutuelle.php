<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mutuelle extends Model
{
    protected $fillable = [
        'nom',
        'code',
        'description',
        'valeur_du_bien',
        'taux_reduction',
        'apport_initial',
        'est_active',
        'cree_par',
        'project_id'
    ];

    protected $casts = [
        'est_active' => 'boolean',
        'valeur_du_bien' => 'decimal:2',
        'taux_reduction' => 'decimal:2',
        'apport_initial' => 'decimal:2'
    ];

    public function creePar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function projet(): BelongsTo
    {
        return $this->belongsTo(Projet::class, 'project_id');
    }



    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}