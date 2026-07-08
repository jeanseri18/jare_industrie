<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Organization extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'subdomain',
        'is_active',
        'plan',
        'settings',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    public function branding(): HasOne
    {
        return $this->hasOne(OrganizationBranding::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function projets(): HasMany
    {
        return $this->hasMany(Projet::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function mutuelles(): HasMany
    {
        return $this->hasMany(Mutuelle::class);
    }

    public function souscriptions(): HasMany
    {
        return $this->hasMany(Souscription::class);
    }

    public static function findBySubdomain(string $subdomain): ?self
    {
        return static::where('subdomain', $subdomain)->where('is_active', true)->first();
    }

    public static function findBySlug(string $slug): ?self
    {
        return static::where('slug', $slug)->where('is_active', true)->first();
    }
}
