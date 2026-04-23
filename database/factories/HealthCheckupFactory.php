<?php

namespace Database\Factories;

use App\Models\HealthCheckup;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class HealthCheckupFactory extends Factory
{
    protected $model = HealthCheckup::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'employee_id' => Employee::factory(),
            'examination_date' => now(),
            'height' => $this->faker->numberBetween(150, 190),
            'weight' => $this->faker->numberBetween(50, 100),
            'bp_systolic' => $this->faker->numberBetween(110, 140),
            'bp_diastolic' => $this->faker->numberBetween(70, 90),
            'health_status' => 'Fit',
            'created_by' => User::factory(),
        ];
    }
}
