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
        $ua = request()?->userAgent() ?? '';
        $metadata = self::parseUserAgent($ua);

        return ActivityLog::create([
            'causer_id' => $causer?->getAuthIdentifier(),
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject?->getMorphClass(),
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => request()?->ip(),
            'user_agent' => $ua,
            'browser' => $metadata['browser'],
            'platform' => $metadata['platform'],
        ]);
    }

    public static function parseUserAgent(string $ua): array
    {
        $browser = 'Unknown';
        $platform = 'Unknown';

        // Basic Platform detection
        if (preg_match('/linux/i', $ua)) {
            $platform = 'Linux';
        } elseif (preg_match('/macintosh|mac os x/i', $ua)) {
            $platform = 'Mac';
        } elseif (preg_match('/windows|win32/i', $ua)) {
            $platform = 'Windows';
        } elseif (preg_match('/iphone|ipad/i', $ua)) {
            $platform = 'iOS';
        } elseif (preg_match('/android/i', $ua)) {
            $platform = 'Android';
        }

        // Basic Browser detection
        if (preg_match('/msie/i', $ua) && !preg_match('/opera/i', $ua)) {
            $browser = 'Internet Explorer';
        } elseif (preg_match('/firefox/i', $ua)) {
            $browser = 'Firefox';
        } elseif (preg_match('/chrome/i', $ua)) {
            $browser = 'Chrome';
        } elseif (preg_match('/safari/i', $ua)) {
            $browser = 'Safari';
        } elseif (preg_match('/opera/i', $ua)) {
            $browser = 'Opera';
        } elseif (preg_match('/netscape/i', $ua)) {
            $browser = 'Netscape';
        }

        return compact('browser', 'platform');
    }

    public static function logWithChanges(
        ?Authenticatable $causer,
        Model $model,
        string $action,
        string $description = '',
        array $extraProperties = []
    ): ActivityLog {
        $properties = $extraProperties;

        $isUpdate = str_ends_with($action, '.updated');
        $isCreate = str_ends_with($action, '.created');

        if ($isUpdate) {
            $changes = self::getModelChanges($model);
            $properties = array_merge($properties, $changes);
        } elseif ($isCreate) {
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
