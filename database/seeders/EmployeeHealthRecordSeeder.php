<?php

namespace Database\Seeders;

use App\Models\EmployeeHealthRecord;
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
        $user = User::first() ?? User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@lifecare.com',
            'password' => bcrypt('password'),
        ]);

        $companies = ['TechCorp Solutions', 'Global Logistics', 'Sunrise Manufacturing', 'Nexus Industries'];
        $doctors = [
            ['name' => 'Amit Shah', 'qual' => 'MBBS, MD (Occupational Health)', 'seal' => 'Reg No: 123456'],
            ['name' => 'Priya Verma', 'qual' => 'MBBS, DNB', 'seal' => 'Reg No: 654321'],
            ['name' => 'Suresh Raina', 'qual' => 'MBBS, AFIH', 'seal' => 'Reg No: 987654']
        ];

        for ($i = 1; $i <= 10; $i++) {
            $doc = $doctors[array_rand($doctors)];
            
            EmployeeHealthRecord::create([
                'uuid' => (string) Str::uuid(),
                'company_name' => $companies[array_rand($companies)],
                'employee_id' => 'EMP-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'full_name' => fake()->name(),
                'gender' => fake()->randomElement(['male', 'female']),
                'dob' => fake()->date('Y-m-d', '-20 years'),
                'mobile' => '98' . fake()->numerify('########'),
                'email' => fake()->unique()->safeEmail(),
                'blood_group' => fake()->randomElement(['A+', 'B+', 'O+', 'AB+', 'A-', 'B-', 'O-', 'AB-']),
                'height' => fake()->numberBetween(150, 190),
                'weight' => fake()->numberBetween(50, 100),
                'bmi' => fake()->randomFloat(2, 18, 30),
                'bp_systolic' => fake()->numberBetween(110, 140),
                'bp_diastolic' => fake()->numberBetween(70, 90),
                'heart_rate' => fake()->numberBetween(60, 100),
                'temperature' => fake()->randomFloat(1, 97, 99),
                'spo2' => fake()->numberBetween(95, 100),
                'medical_history' => 'General health is good.',
                'current_medication' => 'None',
                'allergies' => 'None',
                'physical_exam' => 'NAD',
                'diagnosis' => 'Healthy',
                'advice' => 'Routine follow-up after 6 months.',
                'created_by' => $user->id,
                'status' => 'active',

                // Expanded Info
                'identification_mark' => 'Mole on ' . fake()->randomElement(['right arm', 'left cheek', 'forehead']),
                'father_name' => fake()->name('male'),
                'marital_status' => fake()->randomElement(['Married', 'Single', 'Unmarried']),
                'husband_name' => null,
                'address' => fake()->address(),
                'dependent' => fake()->numberBetween(0, 4),
                'joining_date' => fake()->date('Y-m-d', '-2 years'),
                'examination_date' => date('Y-m-d'),
                'department' => fake()->randomElement(['Production', 'IT', 'HR', 'Logistics', 'Quality']),
                'designation' => fake()->randomElement(['Manager', 'Supervisor', 'Engineer', 'Technician']),
                'habits' => 'None',
                'prev_occ_history' => 'No prior occupational hazards',
                
                // Physical/Vision
                'chest_before' => '85',
                'chest_after' => '90',
                'respiration_rate' => '18',
                'right_eye_specs' => 'Normal',
                'left_eye_specs' => 'Normal',
                'near_vision_right' => 'N/6',
                'near_vision_left' => 'N/6',
                'distant_vision_right' => '6/6',
                'distant_vision_left' => '6/6',
                'colour_vision' => 'Normal',

                // Local Examination
                'eye' => 'Normal',
                'nose' => 'Normal',
                'ear' => 'Normal',
                'conjunctiva' => 'Normal',
                'tongue' => 'Normal',
                'nails' => 'Normal',
                'throat' => 'Normal',
                'skin' => 'Normal',
                'teeth' => 'Normal',
                'pefr' => '480',
                'eczema' => 'No',
                'cyanosis' => 'No',
                'jaundice' => 'No',
                'anaemia' => 'No',
                'oedema' => 'No',
                'clubbing' => 'No',
                'allergy_status' => 'No',
                'lymphnode' => 'No',

                // Medical History Details
                'hypertension' => 'No',
                'diabetes' => 'No',
                'dyslipidemia' => 'No',
                'radiation_effect' => 'No',
                'vertigo' => 'No',
                'tuberculosis' => 'No',
                'thyroid_disorder' => 'No',
                'epilepsy' => 'No',
                'asthma' => 'No',
                'heart_disease' => 'No',
                'past_history' => 'No significant past history.',
                'present_complain' => 'None',

                // Family History
                'family_father' => 'Healthy',
                'family_mother' => 'Healthy',
                'family_brother' => 'NAD',
                'family_sister' => 'NAD',

                // Systemic
                'resp_system' => 'Normal breath sounds',
                'genito_urinary' => 'Normal',
                'cvs' => 'S1 S2 Normal',
                'cns' => 'Intact',
                'per_abdomen' => 'Soft',
                'ent' => 'Normal',

                // Investigations
                'pft' => 'Normal',
                'xray_chest' => 'Normal',
                'vertigo_test' => 'Negative',
                'audiometry' => 'Normal hearing',
                'ecg' => 'Normal Sinus Rhythm',

                // Lab Reports
                'hb' => '14.8',
                'wbc_tc' => '8200',
                'parasite_dc' => 'Negative',
                'rbc' => '5.1',
                'platelet' => '280000',
                'esr' => '12',
                'fbs' => '88',
                'pp2bs' => '120',
                'sgpt' => '32',
                's_creatinine' => '0.85',
                'rbs' => '110',
                's_chol' => '180',
                's_trg' => '130',
                's_hdl' => '45',
                's_ldl' => '110',
                'ch_ratio' => '4.0',

                // Urine
                'urine_colour' => 'Pale Yellow',
                'urine_reaction' => 'Acidic',
                'urine_albumin' => 'Nil',
                'urine_sugar' => 'Nil',
                'urine_pus_cell' => '1-2',
                'urine_rbc' => 'Nil',
                'urine_epi_cell' => '2-4',
                'urine_crystal' => 'Nil',

                // Final Assessment
                'health_status' => fake()->randomElement(['Fit', 'Fit', 'Fit', 'Fit', 'Unfit']),
                'doctor_name' => $doc['name'],
                'doctor_qualification' => $doc['qual'],
                'doctor_signature' => 'Digitally Signed',
                'doctor_seal' => $doc['seal'],
                'job_restriction' => 'None',
                'reviewed_by' => 'Dr. Senior Consultant',
                'doctor_remarks' => 'Everything within normal limits.',
                
                // Administrative
                'hazardous_process' => 'N/A',
                'dangerous_operation' => 'No',
                'materials_exposed' => 'None',
            ]);
        }
    }
}
