<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'description',
        'model_type',
        'model_id',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getActionLabelAttribute(): string
    {
        return match($this->action) {
            'create' => 'Création',
            'update' => 'Modification',
            'delete' => 'Suppression',
            'login' => 'Connexion',
            'logout' => 'Déconnexion',
            'backup' => 'Sauvegarde',
            'restore' => 'Restauration',
            default => ucfirst($this->action)
        };
    }

    public function getActionClassAttribute(): string
    {
        return match($this->action) {
            'create' => 'success',
            'update' => 'warning',
            'delete' => 'danger',
            'login' => 'info',
            'logout' => 'secondary',
            'backup' => 'primary',
            'restore' => 'primary',
            default => 'primary'
        };
    }

    public function scopeFilterByUser($query, $userId)
    {
        return $userId ? $query->where('user_id', $userId) : $query;
    }

    public function scopeFilterByAction($query, $action)
    {
        return $action ? $query->where('action', $action) : $query;
    }

    public function scopeFilterByDateRange($query, $from, $to)
    {
        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }
        return $query;
    }
}