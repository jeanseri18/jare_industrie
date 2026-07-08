<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ROLE_SUPER_ADMIN = 'super_admin';
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
        'organization_id',
        'name',
        'email',
        'password',
        'role',
        'telephone',
        'adresse',
        'profile_photo',
        'nom',
        'prenom',
        'requires_dg_validation',
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
            'requires_dg_validation' => 'boolean',
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

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function scopeForOrganization($query, ?int $organizationId)
    {
        if ($organizationId) {
            return $query->where('organization_id', $organizationId);
        }

        return $query;
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

    // Accesseurs pour nom et prenom
    public function getNomAttribute()
    {
        if (isset($this->attributes['nom'])) {
            return $this->attributes['nom'];
        }
        
        $nameParts = explode(' ', $this->attributes['name']);
        return count($nameParts) > 1 ? end($nameParts) : $this->attributes['name'];
    }

    public function getPrenomAttribute()
    {
        if (isset($this->attributes['prenom'])) {
            return $this->attributes['prenom'];
        }
        
        $nameParts = explode(' ', $this->attributes['name']);
        return count($nameParts) > 1 ? $nameParts[0] : $this->attributes['name'];
    }

    public function initials(): string
    {
        $prenom = trim($this->attributes['prenom'] ?? '');
        $nom = trim($this->attributes['nom'] ?? '');

        if ($prenom !== '' && $nom !== '' && mb_strtolower($prenom) !== mb_strtolower($nom)) {
            return mb_strtoupper(mb_substr($prenom, 0, 1).mb_substr($nom, 0, 1));
        }

        $parts = preg_split('/\s+/', trim($this->attributes['name'] ?? '')) ?: [];
        $parts = array_values(array_filter($parts));

        if (count($parts) >= 2) {
            return mb_strtoupper(mb_substr($parts[0], 0, 1).mb_substr($parts[count($parts) - 1], 0, 1));
        }

        $name = $parts[0] ?? '?';

        return mb_strtoupper(mb_substr($name, 0, 2));
    }

    public function profilePhotoUrl(): ?string
    {
        $photo = $this->attributes['profile_photo'] ?? null;

        if (! $photo) {
            return null;
        }

        if (str_starts_with($photo, 'http://') || str_starts_with($photo, 'https://')) {
            return $photo;
        }

        return asset('storage/'.$photo);
    }
}
