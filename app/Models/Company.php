<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'address',
        'contact_person',
        'contact_number',
        'email',
        'is_active',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function healthCheckups()
    {
        return $this->hasManyThrough(HealthCheckup::class, Employee::class);
    }
}
