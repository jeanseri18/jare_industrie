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
        'taux_reduction',
        'est_active',
        'cree_par',
        'project_id',
        'site_web',
        'nom_contact',
        'telephone_contact',
        'email_contact'
    ];

    protected $casts = [
        'est_active' => 'boolean',
        'taux_reduction' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($mutuelle) {
            if (empty($mutuelle->code)) {
                $mutuelle->code = 'MUT-' . str_pad(static::max('id') + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

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