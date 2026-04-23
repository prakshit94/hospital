<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class HealthCheckup extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'uuid',
        'employee_id',
        'examination_date',
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
        'past_history',
        'present_complain',
        'doctor_remarks',
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
        'doctor_qualification',
        'doctor_signature',
        'doctor_seal',
        'job_restriction',
        'reviewed_by',
        'hazardous_process',
        'dangerous_operation',
        'materials_exposed',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
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

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function documents()
    {
        return $this->hasMany(HealthRecordDocument::class, 'health_checkup_id');
    }

    // Accessors for backward compatibility with views
    public function getFullNameAttribute()
    {
        return $this->employee->full_name ?? 'N/A';
    }

    public function getCompanyIdAttribute()
    {
        return $this->employee->company_id ?? null;
    }

    public function getCompanyNameAttribute()
    {
        return $this->employee->company->name ?? 'N/A';
    }

    public function getMobileAttribute()
    {
        return $this->employee->mobile ?? 'N/A';
    }

    public function getGenderAttribute()
    {
        return $this->employee->gender ?? 'N/A';
    }

    public function getDobAttribute()
    {
        return $this->employee->dob ?? null;
    }

    public function getFatherNameAttribute()
    {
        return $this->employee->father_name ?? 'N/A';
    }

    public function getJoiningDateAttribute()
    {
        return $this->employee->joining_date ?? null;
    }

    public function getAddressAttribute()
    {
        return $this->employee->address ?? 'N/A';
    }

    public function getEmailAttribute()
    {
        return $this->employee->email ?? 'N/A';
    }

    public function getBloodGroupAttribute()
    {
        return $this->employee->blood_group ?? 'N/A';
    }

    public function getDepartmentAttribute()
    {
        return $this->employee->department ?? 'N/A';
    }

    public function getDesignationAttribute()
    {
        return $this->employee->designation ?? 'N/A';
    }

    public function getIdentificationMarkAttribute()
    {
        return $this->employee->identification_mark ?? 'N/A';
    }

    public function getHabitsAttribute()
    {
        return $this->employee->habits ?? 'N/A';
    }

    public function getMaritalStatusAttribute()
    {
        return $this->employee->marital_status ?? 'N/A';
    }

    public function getHusbandNameAttribute()
    {
        return $this->employee->husband_name ?? 'N/A';
    }

    public function getDependentAttribute()
    {
        return $this->employee->dependent ?? 'N/A';
    }

    public function getPrevOccHistoryAttribute()
    {
        return $this->employee->prev_occ_history ?? 'N/A';
    }

    public function getStatusAttribute()
    {
        return $this->employee->status ?? 'active';
    }
}
