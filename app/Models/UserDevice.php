<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserDevice extends Model
{
    protected $fillable = [
        'user_id',
        'device_id',
        'ip_address',
        'user_agent',
        'browser',
        'platform',
        'last_active_at',
        'is_trusted',
    ];

    protected $casts = [
        'last_active_at' => 'datetime',
        'is_trusted' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
