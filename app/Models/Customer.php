<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, HasUuid, SoftDeletes;

    protected $fillable = [
        'customer_code',
        'first_name',
        'middle_name',
        'last_name',
        'display_name',
        'mobile',
        'email',
        'alternate_email',
        'whatsapp_number',
        'phone_number_2',
        'relative_phone',
        'source',
        'type',
        'category',
        'customer_group',
        'company_name',
        'gst_number',
        'pan_number',
        'land_area',
        'land_unit',
        'crops',
        'irrigation_type',
        'credit_limit',
        'outstanding_balance',
        'overdue_amount',
        'credit_score',
        'payment_terms_days',
        'credit_valid_till',
        'last_payment_date',
        'lifetime_value',
        'average_order_value',
        'aadhaar_last4',
        'aadhaar_number_hash',
        'kyc_completed',
        'kyc_status',
        'kyc_verified_at',
        'kyc_rejected_reason',
        'pan_verified',
        'gst_verified',
        'first_purchase_at',
        'last_purchase_at',
        'orders_count',
        'last_contacted_at',
        'lead_status',
        'status',
        'is_blacklisted',
        'internal_notes',
        'tags',
        'meta',
        'primary_address_id',
        'assigned_to',
        'referred_by',
        'external_id',
        'created_by',
        'updated_by',
        'deleted_by',
        'last_login_at',
        'otp_verified_at',
    ];

    protected $casts = [
        'crops' => 'array',
        'irrigation_type' => 'array',
        'tags' => 'array',
        'meta' => 'array',
        'credit_limit' => 'decimal:2',
        'outstanding_balance' => 'decimal:2',
        'overdue_amount' => 'decimal:2',
        'lifetime_value' => 'decimal:2',
        'average_order_value' => 'decimal:2',
        'land_area' => 'decimal:2',
        'kyc_completed' => 'boolean',
        'pan_verified' => 'boolean',
        'gst_verified' => 'boolean',
        'status' => 'string',
        'is_blacklisted' => 'boolean',
        'kyc_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'otp_verified_at' => 'datetime',
        'credit_valid_till' => 'date',
        'last_payment_date' => 'date',
        'first_purchase_at' => 'date',
        'last_purchase_at' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            if (empty($customer->customer_code)) {
                $customer->customer_code = 'CUST-' . strtoupper(\Illuminate\Support\Str::random(8));
            }

            // ✅ FIX: prevent null/extra space issue
            if (empty($customer->display_name)) {
                $customer->display_name = trim(
                    ($customer->first_name ?? '') . ' ' . ($customer->middle_name ?? '') . ' ' . ($customer->last_name ?? '')
                );
                $customer->display_name = preg_replace('/\s+/', ' ', $customer->display_name); // Clean up extra spaces
            }
        });
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function primaryAddress(): BelongsTo
    {
        return $this->belongsTo(CustomerAddress::class, 'primary_address_id');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function referredBy(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'referred_by');
    }
}