<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    protected static array $sensitiveFields = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'stripe_id',
        'card_brand',
        'card_last_four',
    ];

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

    public static function logWithChanges(
        ?Authenticatable $causer,
        Model $model,
        string $action,
        string $description = '',
        array $extraProperties = []
    ): ActivityLog {
        $properties = $extraProperties;

        if ($action === 'user.updated' || $action === 'role.updated' || $action === 'permission.updated') {
            $changes = self::getModelChanges($model);
            $properties = array_merge($properties, $changes);
        } elseif ($action === 'user.created' || $action === 'role.created' || $action === 'permission.created') {
            $properties['new'] = self::filterSensitiveFields($model->toArray());
        }

        return self::log($causer, $action, $model, $description, $properties);
    }

    public static function getModelChanges(Model $model): array
    {
        $old = [];
        $new = [];

        // Use getDirty() to get what is ABOUT to be changed
        $changes = $model->getDirty();

        foreach ($changes as $attribute => $newValue) {
            if (in_array($attribute, self::$sensitiveFields)) {
                continue;
            }

            $oldValue = $model->getOriginal($attribute);

            // Skip if value isn't actually different (strict check)
            if ($oldValue === $newValue) {
                continue;
            }

            $old[$attribute] = $oldValue;
            $new[$attribute] = $newValue;
        }

        return [
            'old' => $old,
            'new' => $new,
        ];
    }

    protected static function filterSensitiveFields(array $data): array
    {
        return array_diff_key($data, array_flip(self::$sensitiveFields));
    }
}
