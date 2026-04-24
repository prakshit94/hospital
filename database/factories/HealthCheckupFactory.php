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
        $height = $this->faker->numberBetween(150, 190);
        $weight = $this->faker->numberBetween(50, 100);
        $heightInMeters = $height / 100;
        $bmi = round($weight / ($heightInMeters * $heightInMeters), 2);

        return [
            'uuid' => (string) Str::uuid(),
            'employee_id' => Employee::factory(),
            'examination_date' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'height' => $height,
            'weight' => $weight,
            'bmi' => $bmi,
            'bp_systolic' => $this->faker->numberBetween(110, 140),
            'bp_diastolic' => $this->faker->numberBetween(70, 90),
            'heart_rate' => $this->faker->numberBetween(60, 100),
            'temperature' => $this->faker->randomFloat(1, 97.0, 99.5),
            'spo2' => $this->faker->numberBetween(95, 100),
            'respiration_rate' => $this->faker->numberBetween(12, 20),
            
            // Clinical History
            'past_history' => $this->faker->randomElement(['NAD', 'No major illness', 'Previous appendectomy']),
            'present_complain' => $this->faker->randomElement(['NAD', 'Mild headache', 'No complaints']),
            'medical_history' => $this->faker->randomElement(['None', 'Occasional seasonal allergies']),
            
            // Chest
            'chest_before' => $this->faker->numberBetween(80, 100),
            'chest_after' => $this->faker->numberBetween(85, 105),
            
            // Vision
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
            'conjunctiva' => 'Normal',
            'ear' => 'Normal',
            'tongue' => 'Normal',
            'nails' => 'Normal',
            'throat' => 'Normal',
            'skin' => 'Normal',
            'teeth' => 'Normal',
            'pefr' => 'Normal',
            'eczema' => 'No',
            'cyanosis' => 'No',
            'jaundice' => 'No',
            'anaemia' => 'No',
            'oedema' => 'No',
            'clubbing' => 'No',
            'allergy_status' => 'None',
            'lymphnode' => 'NAD',
            
            // Medical Conditions
            'hypertension' => 'No',
            'diabetes' => 'No',
            'dyslipidemia' => 'No',
            'radiation_effect' => 'None',
            'vertigo' => 'No',
            'tuberculosis' => 'No',
            'thyroid_disorder' => 'No',
            'epilepsy' => 'No',
            'asthma' => 'No',
            'heart_disease' => 'No',
            
            // Family History
            'family_father' => 'NAD',
            'family_mother' => 'NAD',
            'family_brother' => 'NAD',
            'family_sister' => 'NAD',
            
            // Systemic
            'resp_system' => 'Normal',
            'genito_urinary' => 'Normal',
            'cvs' => 'Normal',
            'cns' => 'Normal',
            'per_abdomen' => 'Normal',
            'ent' => 'Normal',
            
            // Investigations
            'pft' => 'Normal',
            'xray_chest' => 'Normal',
            'vertigo_test' => 'Normal',
            'audiometry' => 'Normal',
            'ecg' => 'Normal',
            
            // Lab Reports
            'hb' => $this->faker->randomFloat(1, 12, 16),
            'wbc_tc' => $this->faker->numberBetween(4000, 11000),
            'parasite_dc' => 'NAD',
            'rbc' => $this->faker->randomFloat(1, 4, 6),
            'platelet' => $this->faker->numberBetween(150000, 450000),
            'esr' => $this->faker->numberBetween(0, 20),
            'fbs' => $this->faker->numberBetween(70, 110),
            'sgpt' => $this->faker->numberBetween(10, 40),
            's_creatinine' => $this->faker->randomFloat(1, 0.6, 1.2),
            
            // Urine
            'urine_colour' => 'Pale Yellow',
            'urine_reaction' => 'Acidic',
            'urine_albumin' => 'Nil',
            'urine_sugar' => 'Nil',
            'urine_pus_cell' => 'Occasional',
            
            // Assessment
            'health_status' => 'Fit',
            'doctor_name' => $this->faker->name('male'),
            'doctor_qualification' => 'MBBS, MD',
            'doctor_signature' => 'Digitally Signed',
            'doctor_seal' => 'Hospital Seal',
            'doctor_remarks' => 'Fit for duty.',
            'job_restriction' => 'None',
            'reviewed_by' => 'Admin',
            'hazardous_process' => 'No',
            'dangerous_operation' => 'No',
            'materials_exposed' => 'None',
            'created_by' => User::factory(),
        ];
    }
}
