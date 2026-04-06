<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'type',
        'label',
        'contact_name',
        'contact_phone',
        'address_line1',
        'address_line2',
        'landmark',
        'full_address',
        'village_id',
        'village',
        'taluka',
        'district',
        'state',
        'country',
        'pincode',
        'post_office',
        'latitude',
        'longitude',
        'delivery_instructions',
        'is_default',
        'is_verified',
        'verified_at',
        'route_code',
        'zone',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'is_default' => 'boolean',
        'is_verified' => 'boolean',
        'verified_at' => 'datetime',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function village(): BelongsTo
    {
        return $this->belongsTo(Village::class);
    }
}
