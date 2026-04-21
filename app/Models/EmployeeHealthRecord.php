<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class EmployeeHealthRecord extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'uuid',
        'company_name',
        'employee_id',
        'full_name',
        'gender',
        'dob',
        'mobile',
        'email',
        'blood_group',
        'height',
        'weight',
        'bmi',
        'bp_systolic',
        'bp_diastolic',
        'heart_rate',
        'temperature',
        'spo2',
        'medical_history',
        'current_medication',
        'allergies',
        'physical_exam',
        'diagnosis',
        'advice',
        'created_by',
        'updated_by',
        'status',

        // Expanded Fields
        'identification_mark',
        'father_name',
        'marital_status',
        'husband_name',
        'address',
        'dependent',
        'joining_date',
        'examination_date',
        'department',
        'designation',
        'habits',
        'prev_occ_history',
        'chest_before',
        'chest_after',
        'respiration_rate',
        'right_eye_specs',
        'left_eye_specs',
        'near_vision_right',
        'near_vision_left',
        'distant_vision_right',
        'distant_vision_left',
        'colour_vision',
        'eye',
        'nose',
        'conjunctiva',
        'ear',
        'tongue',
        'nails',
        'throat',
        'skin',
        'teeth',
        'pefr',
        'eczema',
        'cyanosis',
        'jaundice',
        'anaemia',
        'oedema',
        'clubbing',
        'allergy_status',
        'lymphnode',
        'hypertension',
        'diabetes',
        'dyslipidemia',
        'radiation_effect',
        'vertigo',
        'tuberculosis',
        'thyroid_disorder',
        'epilepsy',
        'asthma',
        'heart_disease',
        'present_complain',
        'family_father',
        'family_mother',
        'family_brother',
        'family_sister',
        'resp_system',
        'genito_urinary',
        'cvs',
        'cns',
        'per_abdomen',
        'ent',
        'pft',
        'xray_chest',
        'vertigo_test',
        'audiometry',
        'ecg',
        'hb',
        'wbc_tc',
        'parasite_dc',
        'rbc',
        'platelet',
        'esr',
        'fbs',
        'pp2bs',
        'sgpt',
        's_creatinine',
        'rbs',
        's_chol',
        's_trg',
        's_hdl',
        's_ldl',
        'ch_ratio',
        'urine_colour',
        'urine_reaction',
        'urine_albumin',
        'urine_sugar',
        'urine_pus_cell',
        'urine_rbc',
        'urine_epi_cell',
        'urine_crystal',
        'health_status',
        'doctor_name',
        'doctor_signature',
        'job_restriction',
        'reviewed_by',
        'doctor_remarks',
        'hazardous_process',
        'dangerous_operation',
        'materials_exposed',
        'past_history',
        'doctor_qualification',
        'doctor_seal',
    ];

    protected $casts = [
        'dob' => 'date',
        'joining_date' => 'date',
        'examination_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
