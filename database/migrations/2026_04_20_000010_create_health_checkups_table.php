<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('health_checkups', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->date('examination_date')->nullable();
            
            // Vitals
            $table->decimal('height', 8, 2)->nullable();
            $table->decimal('weight', 8, 2)->nullable();
            $table->decimal('bmi', 8, 2)->nullable();
            $table->integer('bp_systolic')->nullable();
            $table->integer('bp_diastolic')->nullable();
            $table->integer('heart_rate')->nullable();
            $table->decimal('temperature', 5, 2)->nullable();
            $table->integer('spo2')->nullable();

            // Clinical / History
            $table->text('medical_history')->nullable();
            $table->text('current_medication')->nullable();
            $table->text('allergies')->nullable();
            $table->text('physical_exam')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('advice')->nullable();
            $table->text('past_history')->nullable();
            $table->text('present_complain')->nullable();
            $table->text('doctor_remarks')->nullable();

            // Detailed Examination
            $table->text('chest_before')->nullable();
            $table->text('chest_after')->nullable();
            $table->text('respiration_rate')->nullable();
            $table->text('right_eye_specs')->nullable();
            $table->text('left_eye_specs')->nullable();
            $table->text('near_vision_right')->nullable();
            $table->text('near_vision_left')->nullable();
            $table->text('distant_vision_right')->nullable();
            $table->text('distant_vision_left')->nullable();
            $table->text('colour_vision')->nullable();
            $table->text('eye')->nullable();
            $table->text('nose')->nullable();
            $table->text('conjunctiva')->nullable();
            $table->text('ear')->nullable();
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
            $table->text('allergy_status')->nullable();
            $table->text('lymphnode')->nullable();

            // Medical Conditions
            $table->text('hypertension')->nullable();
            $table->text('diabetes')->nullable();
            $table->text('dyslipidemia')->nullable();
            $table->text('radiation_effect')->nullable();
            $table->text('vertigo')->nullable();
            $table->text('tuberculosis')->nullable();
            $table->text('thyroid_disorder')->nullable();
            $table->text('epilepsy')->nullable();
            $table->text('asthma')->nullable();
            $table->text('heart_disease')->nullable();

            // Family History
            $table->text('family_father')->nullable();
            $table->text('family_mother')->nullable();
            $table->text('family_brother')->nullable();
            $table->text('family_sister')->nullable();

            // Systemic
            $table->text('resp_system')->nullable();
            $table->text('genito_urinary')->nullable();
            $table->text('cvs')->nullable();
            $table->text('cns')->nullable();
            $table->text('per_abdomen')->nullable();
            $table->text('ent')->nullable();

            // Investigations
            $table->text('pft')->nullable();
            $table->text('xray_chest')->nullable();
            $table->text('vertigo_test')->nullable();
            $table->text('audiometry')->nullable();
            $table->text('ecg')->nullable();

            // Lab Reports
            $table->text('hb')->nullable();
            $table->text('wbc_tc')->nullable();
            $table->text('parasite_dc')->nullable();
            $table->text('rbc')->nullable();
            $table->text('platelet')->nullable();
            $table->text('esr')->nullable();
            $table->text('fbs')->nullable();
            $table->text('pp2bs')->nullable();
            $table->text('sgpt')->nullable();
            $table->text('s_creatinine')->nullable();
            $table->text('rbs')->nullable();
            $table->text('s_chol')->nullable();
            $table->text('s_trg')->nullable();
            $table->text('s_hdl')->nullable();
            $table->text('s_ldl')->nullable();
            $table->text('ch_ratio')->nullable();

            // Urine
            $table->text('urine_colour')->nullable();
            $table->text('urine_reaction')->nullable();
            $table->text('urine_albumin')->nullable();
            $table->text('urine_sugar')->nullable();
            $table->text('urine_pus_cell')->nullable();
            $table->text('urine_rbc')->nullable();
            $table->text('urine_epi_cell')->nullable();
            $table->text('urine_crystal')->nullable();

            // Assessment & Admin
            $table->text('health_status')->nullable();
            $table->text('doctor_name')->nullable();
            $table->text('doctor_qualification')->nullable();
            $table->text('doctor_signature')->nullable();
            $table->text('doctor_seal')->nullable();
            $table->text('job_restriction')->nullable();
            $table->text('reviewed_by')->nullable();
            $table->text('hazardous_process')->nullable();
            $table->text('dangerous_operation')->nullable();
            $table->text('materials_exposed')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('health_checkups');
    }
};
