<?php

namespace Database\Factories;

use App\Models\EmployeeHealthRecord;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeHealthRecordFactory extends Factory
{
    protected $model = EmployeeHealthRecord::class;

    public function definition(): array
    {
        return [
            'uuid' => (string) Str::uuid(),
            'full_name' => $this->faker->name(),
            'gender' => $this->faker->randomElement(['male', 'female']),
            'status' => 'active',
            'employee_id' => $this->faker->numerify('EMP####'),
            'company_name' => $this->faker->company(),
        ];
    }
}
