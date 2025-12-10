<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatabaseBackup extends Model
{
    protected $fillable = [
        'user_id',
        'filename',
        'type',
        'size',
        'path',
        'tables',
        'compressed',
        'status',
        'started_at',
        'completed_at',
        'error_message',
    ];

    protected $casts = [
        'size' => 'integer',
        'compressed' => 'boolean',
        'tables' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'full' => 'Complète',
            'structure' => 'Structure uniquement',
            'data' => 'Données uniquement',
            default => ucfirst($this->type)
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'En attente',
            'running' => 'En cours',
            'completed' => 'Terminée',
            'failed' => 'Échouée',
            default => ucfirst($this->status)
        };
    }

    public function getStatusClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'running' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'secondary'
        };
    }

    public function getDurationAttribute(): ?string
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        $duration = $this->started_at->diffInSeconds($this->completed_at);
        
        if ($duration < 60) {
            return $duration . 's';
        } elseif ($duration < 3600) {
            return round($duration / 60, 2) . 'min';
        } else {
            return round($duration / 3600, 2) . 'h';
        }
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}