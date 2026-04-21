<?php

namespace Database\Seeders;

use App\Models\EmployeeHealthRecord;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeeHealthRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'admin@example.com')->first() ?? User::first();
        
        $companies = Company::all();
        if ($companies->isEmpty()) {
            $this->call(CompanySeeder::class);
            $companies = Company::all();
        }

        $doctors = [
            ['name' => 'Dr. Amit Shah', 'qual' => 'MBBS, MD (Occupational Health)', 'seal' => 'Reg No: 123456'],
            ['name' => 'Dr. Priya Verma', 'qual' => 'MBBS, DNB', 'seal' => 'Reg No: 654321'],
            ['name' => 'Dr. Suresh Raina', 'qual' => 'MBBS, AFIH', 'seal' => 'Reg No: 987654']
        ];

        for ($i = 1; $i <= 20; $i++) {
            $doc = $doctors[array_rand($doctors)];
            $company = $companies->random();
            
            $gender = fake()->randomElement(['male', 'female']);
            $isMarried = fake()->boolean(70);
            
            EmployeeHealthRecord::create([
                'uuid' => (string) Str::uuid(),
                'company_id' => $company->id,
                'company_name' => $company->name,
                'employee_id' => strtoupper($company->code) . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'full_name' => fake()->name($gender),
                'gender' => $gender,
                'dob' => fake()->date('Y-m-d', '-45 years'),
                'mobile' => '9' . fake()->numerify('#########'),
                'email' => fake()->unique()->safeEmail(),
                'blood_group' => fake()->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
                'height' => fake()->numberBetween(150, 195),
                'weight' => fake()->numberBetween(55, 110),
                'bp_systolic' => fake()->numberBetween(110, 150),
                'bp_diastolic' => fake()->numberBetween(70, 100),
                'heart_rate' => fake()->numberBetween(65, 95),
                'temperature' => fake()->randomFloat(1, 97, 99.2),
                'spo2' => fake()->numberBetween(96, 99),
                'status' => 'active',
                
                // Personal Info
                'identification_mark' => 'Mole on ' . fake()->randomElement(['neck', 'left arm', 'right shoulder']),
                'father_name' => fake()->name('male'),
                'marital_status' => $isMarried ? 'Married' : 'Single',
                'husband_name' => ($gender === 'female' && $isMarried) ? fake()->name('male') : null,
                'address' => fake()->address(),
                'dependent' => fake()->numberBetween(0, 5),
                'joining_date' => fake()->date('Y-m-d', '-5 years'),
                'examination_date' => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
                'department' => fake()->randomElement(['Operations', 'Maintenance', 'Quality Control', 'Safety', 'Administration']),
                'designation' => fake()->randomElement(['Senior Engineer', 'Operator', 'Safety Officer', 'Clerk', 'Helper']),
                
                // History
                'medical_history' => fake()->randomElement(['NAD', 'History of seasonal allergy', 'NAD', 'None']),
                'habits' => fake()->randomElement(['Non-smoker', 'Non-smoker', 'Occasional Smoker', 'Tobacco Chewing']),
                'prev_occ_history' => 'No major exposure in previous roles.',
                
                // Physical
                'chest_before' => '88',
                'chest_after' => '93',
                'respiration_rate' => '16',
                'colour_vision' => 'Normal',
                'near_vision_right' => 'N/6',
                'near_vision_left' => 'N/6',
                'distant_vision_right' => '6/6',
                'distant_vision_left' => '6/6',
                
                // Clinical
                'eye' => 'NAD',
                'nose' => 'Normal',
                'throat' => 'Clear',
                'skin' => 'Clear',
                'teeth' => 'Normal',
                'anaemia' => 'No',
                'jaundice' => 'No',
                'cyanosis' => 'No',
                'clubbing' => 'No',
                'oedema' => 'No',
                
                // Lab
                'hb' => fake()->randomFloat(1, 12, 16),
                'wbc_tc' => fake()->numberBetween(4500, 11000),
                'platelet' => fake()->numberBetween(150000, 400000),
                'fbs' => fake()->numberBetween(80, 110),
                'urine_sugar' => 'Nil',
                'urine_albumin' => 'Nil',
                
                // Admin
                'health_status' => fake()->randomElement(['Fit', 'Fit', 'Fit', 'Fit', 'Fit', 'Temporarily Unfit']),
                'doctor_name' => $doc['name'],
                'doctor_qualification' => $doc['qual'],
                'doctor_signature' => 'Signed',
                'doctor_seal' => $doc['seal'],
                'doctor_remarks' => 'Fit for duty in current role.',
                'created_by' => $user->id,
            ]);
        }
    }
}
