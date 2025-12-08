<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_DG = 'dg';
    const ROLE_ADMIN_TECHNIQUE = 'admin_technique';
    const ROLE_OPERATEUR = 'operateur';
    const ROLE_COMPTABLE = 'comptable';
    const ROLE_CHEF_COMMERCIAL = 'chef_commercial';
    const ROLE_CLIENT = 'client';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telephone',
        'adresse',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return in_array($this->role, [
            self::ROLE_DG,
            self::ROLE_ADMIN_TECHNIQUE,
            self::ROLE_OPERATEUR,
            self::ROLE_COMPTABLE,
            self::ROLE_CHEF_COMMERCIAL
        ]);
    }

    public function isDG()
    {
        return $this->role === self::ROLE_DG;
    }

    public function isClient()
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function souscriptions()
    {
        return $this->hasMany(Souscription::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
}
