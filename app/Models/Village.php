<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Village extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'uuid',
        'village_name',
        'pincode',
        'post_so_name',
        'taluka_name',
        'district_name',
        'state_name',
        'state_code',
        'district_code',
        'taluka_code',
        'village_code',
        'latitude',
        'longitude',
        'is_serviceable',
        'delivery_days',
        'priority',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_serviceable' => 'boolean',
        'is_active' => 'boolean',
        'delivery_days' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }
}
