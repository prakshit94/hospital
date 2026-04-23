<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create Employees table
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->string('employee_id')->nullable(); // Company-specific ID
            $table->string('full_name');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('blood_group')->nullable();
            $table->string('father_name')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('husband_name')->nullable();
            $table->text('address')->nullable();
            $table->string('identification_mark')->nullable();
            $table->date('joining_date')->nullable();
            $table->string('department')->nullable();
            $table->string('designation')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'employee_id']);
        });

        // 2. Create Health Checkups table
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

            // Detailed Examination Fields (Extracted from EmployeeHealthRecord)
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

        // 3. Migrate Data from employee_health_records to new tables
        if (Schema::hasTable('employee_health_records')) {
            $records = DB::table('employee_health_records')->get();

            foreach ($records as $record) {
                // Find or create employee
                $employeeId = DB::table('employees')->where([
                    'company_id' => $record->company_id,
                    'employee_id' => $record->employee_id
                ])->value('id');

                if (!$employeeId) {
                    $employeeId = DB::table('employees')->insertGetId([
                        'uuid' => (string) Str::uuid(),
                        'company_id' => $record->company_id,
                        'employee_id' => $record->employee_id,
                        'full_name' => $record->full_name,
                        'gender' => $record->gender,
                        'dob' => $record->dob,
                        'mobile' => $record->mobile,
                        'email' => $record->email,
                        'blood_group' => $record->blood_group,
                        'father_name' => $record->father_name,
                        'marital_status' => $record->marital_status,
                        'husband_name' => $record->husband_name,
                        'address' => $record->address,
                        'identification_mark' => $record->identification_mark,
                        'joining_date' => $record->joining_date,
                        'department' => $record->department,
                        'designation' => $record->designation,
                        'status' => $record->status ?? 'active',
                        'created_at' => $record->created_at,
                        'updated_at' => $record->updated_at,
                    ]);
                }

                // Create health checkup
                $checkupId = DB::table('health_checkups')->insertGetId([
                    'uuid' => $record->uuid, // Keep original UUID for checkup to maintain links
                    'employee_id' => $employeeId,
                    'examination_date' => $record->examination_date,
                    'height' => $record->height,
                    'weight' => $record->weight,
                    'bmi' => $record->bmi,
                    'bp_systolic' => $record->bp_systolic,
                    'bp_diastolic' => $record->bp_diastolic,
                    'heart_rate' => $record->heart_rate,
                    'temperature' => $record->temperature,
                    'spo2' => $record->spo2,
                    'medical_history' => $record->medical_history,
                    'current_medication' => $record->current_medication,
                    'allergies' => $record->allergies,
                    'physical_exam' => $record->physical_exam,
                    'diagnosis' => $record->diagnosis,
                    'advice' => $record->advice,
                    'past_history' => $record->past_history,
                    'present_complain' => $record->present_complain,
                    'doctor_remarks' => $record->doctor_remarks,
                    'chest_before' => $record->chest_before,
                    'chest_after' => $record->chest_after,
                    'respiration_rate' => $record->respiration_rate,
                    'right_eye_specs' => $record->right_eye_specs,
                    'left_eye_specs' => $record->left_eye_specs,
                    'near_vision_right' => $record->near_vision_right,
                    'near_vision_left' => $record->near_vision_left,
                    'distant_vision_right' => $record->distant_vision_right,
                    'distant_vision_left' => $record->distant_vision_left,
                    'colour_vision' => $record->colour_vision,
                    'eye' => $record->eye,
                    'nose' => $record->nose,
                    'conjunctiva' => $record->conjunctiva,
                    'ear' => $record->ear,
                    'tongue' => $record->tongue,
                    'nails' => $record->nails,
                    'throat' => $record->throat,
                    'skin' => $record->skin,
                    'teeth' => $record->teeth,
                    'pefr' => $record->pefr,
                    'eczema' => $record->eczema,
                    'cyanosis' => $record->cyanosis,
                    'jaundice' => $record->jaundice,
                    'anaemia' => $record->anaemia,
                    'oedema' => $record->oedema,
                    'clubbing' => $record->clubbing,
                    'allergy_status' => $record->allergy_status,
                    'lymphnode' => $record->lymphnode,
                    'hypertension' => $record->hypertension,
                    'diabetes' => $record->diabetes,
                    'dyslipidemia' => $record->dyslipidemia,
                    'radiation_effect' => $record->radiation_effect,
                    'vertigo' => $record->vertigo,
                    'tuberculosis' => $record->tuberculosis,
                    'thyroid_disorder' => $record->thyroid_disorder,
                    'epilepsy' => $record->epilepsy,
                    'asthma' => $record->asthma,
                    'heart_disease' => $record->heart_disease,
                    'family_father' => $record->family_father,
                    'family_mother' => $record->family_mother,
                    'family_brother' => $record->family_brother,
                    'family_sister' => $record->family_sister,
                    'resp_system' => $record->resp_system,
                    'genito_urinary' => $record->genito_urinary,
                    'cvs' => $record->cvs,
                    'cns' => $record->cns,
                    'per_abdomen' => $record->per_abdomen,
                    'ent' => $record->ent,
                    'pft' => $record->pft,
                    'xray_chest' => $record->xray_chest,
                    'vertigo_test' => $record->vertigo_test,
                    'audiometry' => $record->audiometry,
                    'ecg' => $record->ecg,
                    'hb' => $record->hb,
                    'wbc_tc' => $record->wbc_tc,
                    'parasite_dc' => $record->parasite_dc,
                    'rbc' => $record->rbc,
                    'platelet' => $record->platelet,
                    'esr' => $record->esr,
                    'fbs' => $record->fbs,
                    'pp2bs' => $record->pp2bs,
                    'sgpt' => $record->sgpt,
                    's_creatinine' => $record->s_creatinine,
                    'rbs' => $record->rbs,
                    's_chol' => $record->s_chol,
                    's_trg' => $record->s_trg,
                    's_hdl' => $record->s_hdl,
                    's_ldl' => $record->s_ldl,
                    'ch_ratio' => $record->ch_ratio,
                    'urine_colour' => $record->urine_colour,
                    'urine_reaction' => $record->urine_reaction,
                    'urine_albumin' => $record->urine_albumin,
                    'urine_sugar' => $record->urine_sugar,
                    'urine_pus_cell' => $record->urine_pus_cell,
                    'urine_rbc' => $record->urine_rbc,
                    'urine_epi_cell' => $record->urine_epi_cell,
                    'urine_crystal' => $record->urine_crystal,
                    'health_status' => $record->health_status,
                    'doctor_name' => $record->doctor_name,
                    'doctor_qualification' => $record->doctor_qualification,
                    'doctor_signature' => $record->doctor_signature,
                    'doctor_seal' => $record->doctor_seal,
                    'job_restriction' => $record->job_restriction,
                    'reviewed_by' => $record->reviewed_by,
                    'hazardous_process' => $record->hazardous_process,
                    'dangerous_operation' => $record->dangerous_operation,
                    'materials_exposed' => $record->materials_exposed,
                    'created_by' => $record->created_by,
                    'updated_by' => $record->updated_by,
                    'created_at' => $record->created_at,
                    'updated_at' => $record->updated_at,
                ]);

                // Update documents to point to new checkup
                if (Schema::hasTable('health_record_documents')) {
                    DB::table('health_record_documents')
                        ->where('health_record_id', $record->id)
                        ->update(['health_record_id' => $checkupId]);
                }
            }

            // 4. Update health_record_documents table structure
            Schema::table('health_record_documents', function (Blueprint $table) {
                $table->renameColumn('health_record_id', 'health_checkup_id');
            });
            
            Schema::table('health_record_documents', function (Blueprint $table) {
                $table->foreign('health_checkup_id')->references('id')->on('health_checkups')->onDelete('cascade');
            });

            // 5. Optionally drop the old table
            // Schema::dropIfExists('employee_health_records');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_record_documents', function (Blueprint $table) {
            $table->dropForeign(['health_checkup_id']);
            $table->renameColumn('health_checkup_id', 'health_record_id');
        });

        Schema::dropIfExists('health_checkups');
        Schema::dropIfExists('employees');
    }
};
