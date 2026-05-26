<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'company_id',
        'employee_id',
        'full_name',
        'gender',
        'dob',
        'mobile',
        'email',
        'blood_group',
        'father_name',
        'marital_status',
        'husband_name',
        'address',
        'identification_mark',
        'joining_date',
        'department',
        'designation',
        'habits',
        'dependent',
        'prev_occ_history',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'joining_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function checkups()
    {
        return $this->hasMany(HealthCheckup::class)->latest();
    }
}
