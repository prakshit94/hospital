<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'causer_id',
        'action',
        'description',
        'subject_type',
        'subject_id',
        'properties',
        'ip_address',
        'user_agent',
    ];

    /**
     * Cast attributes
     */
    protected function casts(): array
    {
        return [
            'properties' => 'array', // ✅ always array
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Relationships
     */
    public function causer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * ✅ Helper: safely get old values
     */
    public function getOldAttributes(): array
    {
        $old = data_get($this->properties, 'old', []);
        return is_array($old) ? $old : [];
    }

    /**
     * ✅ Helper: safely get new values
     */
    public function getNewAttributes(): array
    {
        $new = data_get($this->properties, 'attributes', []);
        return is_array($new) ? $new : [];
    }

    /**
     * ✅ Helper: count changed fields safely
     */
    public function getChangedCount(): int
    {
        return count($this->getOldAttributes());
    }

    /**
     * ✅ Optional: readable action label
     */
    public function getActionLabel(): string
    {
        return ucwords(str_replace(['.', '_'], ' ', $this->action));
    }

    /**
     * ✅ Optional: tone helper (UI friendly)
     */
    public function getTone(): string
    {
        return match (true) {
            str_contains($this->action, 'created') => 'success',
            str_contains($this->action, 'updated') => 'primary',
            str_contains($this->action, 'deleted') => 'danger',
            str_contains($this->action, 'status') => 'primary',
            default => 'muted',
        };
    }
}