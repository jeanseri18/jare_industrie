<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'client_id',
        'titre',
        'message',
        'type',
        'lu',
    ];

    protected $casts = [
        'lu' => 'boolean',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function scopeUnread($query)
    {
        return $query->where('lu', false);
    }
}