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
        return $this->attributes['full_name'] ?? $this->employee?->full_name;
    }

    public function getCompanyIdAttribute()
    {
        return $this->attributes['company_id'] ?? $this->employee?->company_id;
    }

    public function getCompanyNameAttribute()
    {
        return $this->attributes['company_name'] ?? $this->employee?->company?->name;
    }

    public function getMobileAttribute()
    {
        return $this->attributes['mobile'] ?? $this->employee?->mobile;
    }

    public function getGenderAttribute()
    {
        return $this->attributes['gender'] ?? $this->employee?->gender;
    }

    public function getDobAttribute()
    {
        return $this->attributes['dob'] ?? $this->employee?->dob;
    }

    public function getFatherNameAttribute()
    {
        return $this->attributes['father_name'] ?? $this->employee?->father_name;
    }

    public function getJoiningDateAttribute()
    {
        return $this->attributes['joining_date'] ?? $this->employee?->joining_date;
    }

    public function getAddressAttribute()
    {
        return $this->attributes['address'] ?? $this->employee?->address;
    }

    public function getEmailAttribute()
    {
        return $this->attributes['email'] ?? $this->employee?->email;
    }

    public function getBloodGroupAttribute()
    {
        return $this->attributes['blood_group'] ?? $this->employee?->blood_group;
    }

    public function getDepartmentAttribute()
    {
        return $this->attributes['department'] ?? $this->employee?->department;
    }

    public function getDesignationAttribute()
    {
        return $this->attributes['designation'] ?? $this->employee?->designation;
    }

    public function getIdentificationMarkAttribute()
    {
        return $this->attributes['identification_mark'] ?? $this->employee?->identification_mark;
    }

    public function getHabitsAttribute()
    {
        return $this->attributes['habits'] ?? $this->employee?->habits;
    }

    public function getMaritalStatusAttribute()
    {
        return $this->attributes['marital_status'] ?? $this->employee?->marital_status;
    }

    public function getHusbandNameAttribute()
    {
        return $this->attributes['husband_name'] ?? $this->employee?->husband_name;
    }

    public function getDependentAttribute()
    {
        return $this->attributes['dependent'] ?? $this->employee?->dependent;
    }

    public function getPrevOccHistoryAttribute()
    {
        return $this->attributes['prev_occ_history'] ?? $this->employee?->prev_occ_history;
    }

    public function getStatusAttribute()
    {
        return $this->employee->status ?? 'active';
    }
}
