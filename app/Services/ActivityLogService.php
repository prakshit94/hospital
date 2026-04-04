<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public static function log(
        ?Authenticatable $causer,
        string $action,
        ?Model $subject = null,
        string $description = '',
        array $properties = [],
    ): ActivityLog {
        return ActivityLog::create([
            'causer_id' => $causer?->getAuthIdentifier(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
