<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\HealthCheckup;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class HealthCheckupSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::all();
        $admin = User::first();

        foreach ($companies as $company) {
            // Create 5 employees for each company
            Employee::factory()
                ->count(5)
                ->create(['company_id' => $company->id])
                ->each(function ($employee) use ($admin) {
                    // Create 1-3 checkups for each employee
                    HealthCheckup::factory()
                        ->count(rand(1, 3))
                        ->create([
                            'employee_id' => $employee->id,
                            'created_by' => $admin->id ?? 1
                        ]);
                });
        }
    }
}
