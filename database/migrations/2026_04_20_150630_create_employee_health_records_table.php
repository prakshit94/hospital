<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employee_health_records', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            
            // 2. Employee Information
            $table->string('company_name');
            $table->foreignId('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('employee_id')->nullable(); // Employee No
            $table->date('examination_date')->nullable();
            $table->string('full_name'); // Employee Name
            $table->string('father_name')->nullable();
            $table->date('dob')->nullable(); // Date of Birth (Age calculated from this)
            $table->string('department')->nullable();
            $table->enum('gender', ['male', 'female', 'other']); // Sex
            $table->date('joining_date')->nullable();
            $table->text('identification_mark')->nullable();
            $table->text('habits')->nullable(); // Habit (H/O Habit)
            $table->string('marital_status')->nullable();
            $table->string('designation')->nullable();
            $table->string('husband_name')->nullable();
            $table->string('dependent')->nullable(); // Dependents
            $table->text('prev_occ_history')->nullable(); // Previous Occupational History
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->string('email')->nullable(); // Optional, kept for utility
            $table->string('blood_group')->nullable(); // Optional, kept for utility
            $table->text('medical_history')->nullable();
            $table->text('current_medication')->nullable();
            $table->text('allergies')->nullable();
            $table->text('physical_exam')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('advice')->nullable();

            // 3. Physical Examination
            $table->decimal('temperature', 5, 1)->nullable();
            $table->decimal('height', 8, 2)->nullable();
            $table->string('chest_before')->nullable(); // Chest Before Breathing
            $table->integer('heart_rate')->nullable(); // Pulse Rate
            $table->decimal('weight', 8, 2)->nullable();
            $table->string('chest_after')->nullable(); // Chest After Breathing
            $table->integer('bp_systolic')->nullable();
            $table->integer('bp_diastolic')->nullable(); // Blood Pressure (BP)
            $table->decimal('bmi', 8, 2)->nullable();
            $table->integer('spo2')->nullable(); // SpO2
            $table->string('respiration_rate')->nullable();

            // 4. Vision Examination
            $table->text('right_eye_specs')->nullable();
            $table->string('near_vision_right')->default('N/6');
            $table->string('distant_vision_right')->default('6/6');
            $table->text('left_eye_specs')->nullable();
            $table->string('near_vision_left')->default('N/6');
            $table->string('distant_vision_left')->default('6/6');
            $table->text('colour_vision')->nullable();

            // 5. Local Examination
            $table->text('eye')->nullable();
            $table->text('nose')->nullable();
            $table->text('ear')->nullable();
            $table->text('conjunctiva')->nullable();
            $table->text('tongue')->nullable();
            $table->text('nails')->nullable();
            $table->text('throat')->nullable();
            $table->text('skin')->nullable();
            $table->text('teeth')->nullable();
            $table->text('pefr')->nullable();
            $table->text('eczema')->nullable();
            $table->text('cyanosis')->nullable();
            $table->text('jaundice')->nullable();
            $table->text('anaemia')->nullable();
            $table->text('oedema')->nullable();
            $table->text('clubbing')->nullable();
            $table->text('allergy_status')->nullable(); // Allergy
            $table->text('lymphnode')->nullable(); // Optional, kept

            // 6. Medical History Examination
            $table->text('hypertension')->nullable();
            $table->text('diabetes')->nullable();
            $table->text('dyslipidemia')->nullable();
            $table->text('radiation_effect')->nullable();
            $table->text('vertigo')->nullable();
            $table->text('tuberculosis')->nullable();
            $table->text('thyroid_disorder')->nullable();
            $table->text('epilepsy')->nullable();
            $table->text('asthma')->nullable(); // Bronchial Asthma (Br_Asthma)
            $table->text('heart_disease')->nullable();

            // 7. History Details
            $table->text('past_history')->nullable(); // NEW
            $table->text('present_complain')->nullable(); // Present Complaint

            // 8. Family History
            $table->text('family_father')->nullable();
            $table->text('family_mother')->nullable();
            $table->text('family_brother')->nullable();
            $table->text('family_sister')->nullable();

            // 9. Systemic Examination
            $table->text('resp_system')->nullable(); // Respiratory System
            $table->text('genito_urinary')->nullable(); // Genito Urinary System
            $table->text('cvs')->nullable(); // CVS
            $table->text('cns')->nullable(); // CNS
            $table->text('per_abdomen')->nullable();
            $table->text('ent')->nullable();

            // 10. Investigations
            $table->text('pft')->nullable(); // PFT
            $table->text('xray_chest')->nullable(); // X-Ray Chest
            $table->text('vertigo_test')->nullable();
            $table->text('audiometry')->nullable();
            $table->text('ecg')->nullable();

            // 11. Laboratory Tests
            $table->text('hb')->nullable(); // Hemoglobin (HB)
            $table->text('wbc_tc')->nullable(); // WBC Count
            $table->text('parasite_dc')->nullable(); // Parasite (MP)
            $table->text('rbc')->nullable(); // RBC Count
            $table->text('platelet')->nullable(); // Platelet Count
            $table->text('esr')->nullable();
            $table->text('fbs')->nullable(); // FBS (Fasting Blood Sugar)
            $table->text('pp2bs')->nullable();
            $table->text('sgpt')->nullable();
            $table->text('s_creatinine')->nullable(); // Serum Creatinine
            $table->text('rbs')->nullable(); // RBS (Random Blood Sugar)
            $table->text('s_chol')->nullable(); // Serum Cholesterol
            $table->text('s_trg')->nullable(); // Serum Triglycerides (TRG)
            $table->text('s_hdl')->nullable(); // Serum HDL
            $table->text('s_ldl')->nullable(); // Serum LDL
            $table->text('ch_ratio')->nullable(); // Cholesterol/HDL Ratio (C/H Ratio)

            // 12. Urine Report
            $table->text('urine_colour')->nullable(); // Colour
            $table->text('urine_reaction')->nullable(); // Reaction (pH)
            $table->text('urine_albumin')->nullable(); // Albumin
            $table->text('urine_sugar')->nullable(); // Sugar
            $table->text('urine_pus_cell')->nullable(); // Pus Cells
            $table->text('urine_rbc')->nullable(); // Urine RBC
            $table->text('urine_epi_cell')->nullable(); // Epithelial Cells (EpiCell)
            $table->text('urine_crystal')->nullable(); // Crystals

            // 13. Final Assessment
            $table->text('health_status')->nullable(); // Health Status

            // 14. Doctor Details
            $table->string('doctor_name')->nullable();
            $table->string('doctor_qualification')->nullable(); // NEW
            $table->string('doctor_signature')->nullable(); // Doctor Signature
            $table->string('doctor_seal')->nullable(); // NEW (Seal of Doctor)

            // 15. Job & Advice
            $table->text('job_restriction')->nullable(); // Job Restriction
            $table->text('doctor_remarks')->nullable(); // Doctor Remarks
            $table->text('hazardous_process')->nullable(); // Optional, kept
            $table->text('dangerous_operation')->nullable(); // Optional, kept
            $table->text('materials_exposed')->nullable(); // Optional, kept

            // 16. Review Section
            $table->string('reviewed_by')->nullable(); // Reviewed By

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_health_records');
    }
};
