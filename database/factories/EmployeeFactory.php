<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        return [
            'uuid' => (string) Str::uuid(),
            'company_id' => Company::factory(),
            'employee_id' => $this->faker->unique()->numerify('EMP####'),
            'full_name' => $this->faker->name($gender),
            'gender' => $gender,
            'dob' => $this->faker->date('Y-m-d', '-20 years'),
            'mobile' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'blood_group' => $this->faker->randomElement(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']),
            'father_name' => $this->faker->name('male'),
            'marital_status' => $this->faker->randomElement(['Single', 'Married', 'Unmarried']),
            'husband_name' => $gender === 'female' ? $this->faker->name('male') : 'NA',
            'address' => $this->faker->address(),
            'identification_mark' => $this->faker->sentence(3),
            'joining_date' => $this->faker->date('Y-m-d', '-5 years'),
            'department' => $this->faker->randomElement(['Production', 'Maintenance', 'Quality', 'HR', 'IT']),
            'designation' => $this->faker->jobTitle(),
            'habits' => $this->faker->randomElement(['None', 'Smoking', 'Alcohol', 'Tobacco']),
            'dependent' => $this->faker->randomElement(['None', '1', '2', '3+']),
            'prev_occ_history' => $this->faker->sentence(5),
            'status' => 'active',
        ];
    }
}
