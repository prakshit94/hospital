<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'gender',
        'phone',
        'location',
        'department',
        'job_title',
        'profile_image',
        'email',
        'password',
        'status',
        'last_login_at',
        'last_active_at',
        'notifications_read_at',
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
            'last_login_at' => 'datetime',
            'last_active_at' => 'datetime',
            'notifications_read_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'causer_id');
    }

    public function hasRole(string $role): bool
    {
        return $this->roles->contains(fn (Role $item) => $item->slug === $role || $item->name === $role);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles
            ->flatMap(fn (Role $role) => $role->permissions)
            ->contains(fn (Permission $item) => $item->slug === $permission || $item->name === $permission);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return collect($permissions)->contains(fn (string $permission) => $this->hasPermission($permission));
    }

    public function permissionSlugs(): array
    {
        return $this->roles
            ->flatMap(fn (Role $role) => $role->permissions)
            ->pluck('slug')
            ->unique()
            ->values()
            ->all();
    }

    public function primaryRole(): ?Role
    {
        return $this->roles->sortBy('name')->first();
    }

    public function getIsOnlineAttribute(): bool
    {
        return $this->last_active_at && $this->last_active_at->diffInMinutes(now()) <= 5;
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($user) {
            if ($user->first_name && $user->last_name && empty($user->name)) {
                $user->name = trim("{$user->first_name} {$user->last_name}");
            }
        });
    }
}
