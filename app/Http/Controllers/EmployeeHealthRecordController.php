<?php

namespace App\Http\Controllers;

use App\Models\EmployeeHealthRecord;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeHealthRecordController extends Controller
{
    public function index(Request $request): View
    {
        $query = EmployeeHealthRecord::query()->with('creator');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('company')) {
            $query->where('company_name', $request->company);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest()->paginate(10)->withQueryString();
        $companies = EmployeeHealthRecord::distinct()->pluck('company_name');

        return view('health-records.index', compact('records', 'companies'));
    }

    public function create(): View
    {
        return view('health-records.create', [
            'record' => new EmployeeHealthRecord()
        ]);
    }

    private function getValidationRules(): array
    {
        return [
            'company_name' => 'required|string|max:255',
            'employee_id' => 'required|string|max:255',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date|before:today',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'blood_group' => 'nullable|string|max:10',
            'height' => 'nullable|numeric|between:0,300',
            'weight' => 'nullable|numeric|between:0,600',
            'bp_systolic' => 'nullable|integer|between:0,400',
            'bp_diastolic' => 'nullable|integer|between:0,300',
            'heart_rate' => 'nullable|integer|between:0,300',
            'temperature' => 'nullable|numeric|between:0,120',
            'spo2' => 'nullable|integer|between:0,100',
            'status' => 'required|in:active,inactive',

            // Clinical / History
            'medical_history' => 'nullable|string|max:2000',
            'current_medication' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:1000',
            'physical_exam' => 'nullable|string|max:2000',
            'diagnosis' => 'nullable|string|max:2000',
            'advice' => 'nullable|string|max:2000',
            'past_history' => 'nullable|string|max:2000',
            'present_complain' => 'nullable|string|max:2000',
            'doctor_remarks' => 'nullable|string|max:2000',
            
            // Employee Info
            'identification_mark' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'husband_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'dependent' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'examination_date' => 'nullable|date',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'habits' => 'nullable|string|max:255',
            'prev_occ_history' => 'nullable|string|max:255',

            // Examination Vitals/Clinical
            'chest_before' => 'nullable|string|max:255',
            'chest_after' => 'nullable|string|max:255',
            'respiration_rate' => 'nullable|string|max:255',
            'right_eye_specs' => 'nullable|string|max:255',
            'left_eye_specs' => 'nullable|string|max:255',
            'near_vision_right' => 'nullable|string|max:255',
            'near_vision_left' => 'nullable|string|max:255',
            'distant_vision_right' => 'nullable|string|max:255',
            'distant_vision_left' => 'nullable|string|max:255',
            'colour_vision' => 'nullable|string|max:255',
            'eye' => 'nullable|string|max:255',
            'nose' => 'nullable|string|max:255',
            'conjunctiva' => 'nullable|string|max:255',
            'ear' => 'nullable|string|max:255',
            'tongue' => 'nullable|string|max:255',
            'nails' => 'nullable|string|max:255',
            'throat' => 'nullable|string|max:255',
            'skin' => 'nullable|string|max:255',
            'teeth' => 'nullable|string|max:255',
            'pefr' => 'nullable|string|max:255',
            'eczema' => 'nullable|string|max:255',
            'cyanosis' => 'nullable|string|max:255',
            'jaundice' => 'nullable|string|max:255',
            'anaemia' => 'nullable|string|max:255',
            'oedema' => 'nullable|string|max:255',
            'clubbing' => 'nullable|string|max:255',
            'allergy_status' => 'nullable|string|max:255',
            'lymphnode' => 'nullable|string|max:255',

            // Medical Conditions
            'hypertension' => 'nullable|string|max:255',
            'diabetes' => 'nullable|string|max:255',
            'dyslipidemia' => 'nullable|string|max:255',
            'radiation_effect' => 'nullable|string|max:255',
            'vertigo' => 'nullable|string|max:255',
            'tuberculosis' => 'nullable|string|max:255',
            'thyroid_disorder' => 'nullable|string|max:255',
            'epilepsy' => 'nullable|string|max:255',
            'asthma' => 'nullable|string|max:255',
            'heart_disease' => 'nullable|string|max:255',

            // Family History
            'family_father' => 'nullable|string|max:255',
            'family_mother' => 'nullable|string|max:255',
            'family_brother' => 'nullable|string|max:255',
            'family_sister' => 'nullable|string|max:255',

            // Systemic
            'resp_system' => 'nullable|string|max:255',
            'genito_urinary' => 'nullable|string|max:255',
            'cvs' => 'nullable|string|max:255',
            'cns' => 'nullable|string|max:255',
            'per_abdomen' => 'nullable|string|max:255',
            'ent' => 'nullable|string|max:255',

            // Investigations
            'pft' => 'nullable|string|max:255',
            'xray_chest' => 'nullable|string|max:255',
            'vertigo_test' => 'nullable|string|max:255',
            'audiometry' => 'nullable|string|max:255',
            'ecg' => 'nullable|string|max:255',

            // Lab Reports
            'hb' => 'nullable|string|max:255',
            'wbc_tc' => 'nullable|string|max:255',
            'parasite_dc' => 'nullable|string|max:255',
            'rbc' => 'nullable|string|max:255',
            'platelet' => 'nullable|string|max:255',
            'esr' => 'nullable|string|max:255',
            'fbs' => 'nullable|string|max:255',
            'pp2bs' => 'nullable|string|max:255',
            'sgpt' => 'nullable|string|max:255',
            's_creatinine' => 'nullable|string|max:255',
            'rbs' => 'nullable|string|max:255',
            's_chol' => 'nullable|string|max:255',
            's_trg' => 'nullable|string|max:255',
            's_hdl' => 'nullable|string|max:255',
            's_ldl' => 'nullable|string|max:255',
            'ch_ratio' => 'nullable|string|max:255',

            // Urine
            'urine_colour' => 'nullable|string|max:255',
            'urine_reaction' => 'nullable|string|max:255',
            'urine_albumin' => 'nullable|string|max:255',
            'urine_sugar' => 'nullable|string|max:255',
            'urine_pus_cell' => 'nullable|string|max:255',
            'urine_rbc' => 'nullable|string|max:255',
            'urine_epi_cell' => 'nullable|string|max:255',
            'urine_crystal' => 'nullable|string|max:255',

            // Assessment & Admin
            'health_status' => 'nullable|string|max:255',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_qualification' => 'nullable|string|max:255',
            'doctor_signature' => 'nullable|string|max:255',
            'doctor_seal' => 'nullable|string|max:255',
            'job_restriction' => 'nullable|string|max:255',
            'reviewed_by' => 'nullable|string|max:255',
            'hazardous_process' => 'nullable|string|max:255',
            'dangerous_operation' => 'nullable|string|max:255',
            'materials_exposed' => 'nullable|string|max:255',
        ];
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate($this->getValidationRules());

        return DB::transaction(function () use ($validated, $request) {
            $validated['created_by'] = auth()->id();
            
            // Calculate BMI if height and weight are provided
            if (($validated['height'] ?? 0) > 0 && ($validated['weight'] ?? 0) > 0) {
                $heightInMeters = $validated['height'] / 100;
                $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
            }

            $record = EmployeeHealthRecord::create($validated);

            ActivityLogService::logWithChanges(
                auth()->user(),
                $record,
                'health_record.created',
                "Created health record for: {$record->full_name} ({$record->company_name})"
            );

            $msg = "Health record for '{$record->full_name}' has been successfully stored.";
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $msg,
                    'redirect' => route('health-records.show', $record->uuid)
                ]);
            }

            return redirect()->route('health-records.show', $record->uuid)->with('success', $msg);
        });
    }

    public function show(EmployeeHealthRecord $healthRecord): View
    {
        $activities = ActivityLog::query()
            ->with('causer')
            ->where('subject_type', $healthRecord->getMorphClass())
            ->where('subject_id', $healthRecord->getKey())
            ->latest()
            ->take(10)
            ->get();

        return view('health-records.show', [
            'record' => $healthRecord,
            'activities' => $activities
        ]);
    }

    public function edit(EmployeeHealthRecord $healthRecord): View
    {
        return view('health-records.edit', ['record' => $healthRecord]);
    }

    public function update(Request $request, EmployeeHealthRecord $healthRecord): RedirectResponse|JsonResponse
    {
        $validated = $request->validate($this->getValidationRules());

        return DB::transaction(function () use ($validated, $request, $healthRecord) {
            $validated['updated_by'] = auth()->id();
            
            // Calculate BMI
            if (($validated['height'] ?? 0) > 0 && ($validated['weight'] ?? 0) > 0) {
                $heightInMeters = $validated['height'] / 100;
                $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
            }

            $healthRecord->update($validated);

            ActivityLogService::logWithChanges(
                auth()->user(),
                $healthRecord,
                'health_record.updated',
                "Updated health record for: {$healthRecord->full_name} ({$healthRecord->company_name})"
            );

            $msg = "Health record for '{$healthRecord->full_name}' has been updated.";
            
            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $msg,
                    'redirect' => route('health-records.show', $healthRecord->uuid)
                ]);
            }

            return redirect()->route('health-records.show', $healthRecord->uuid)->with('success', $msg);
        });
    }

    public function destroy(EmployeeHealthRecord $healthRecord): RedirectResponse|JsonResponse
    {
        $name = $healthRecord->full_name;
        
        ActivityLogService::log(
            auth()->user(),
            'health_record.deleted',
            $healthRecord,
            "Deleted health record for: {$name}"
        );

        $healthRecord->delete();

        $msg = "Health record for '{$name}' has been deleted.";

        return request()->ajax()
            ? response()->json(['status' => 'success', 'message' => $msg])
            : redirect()->route('health-records.index')->with('success', $msg);
    }

    public function print(EmployeeHealthRecord $healthRecord)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print', ['record' => $healthRecord]);
        
        return $pdf->setPaper('a4')
                   ->setOption(['isRemoteEnabled' => true])
                   ->download("Medical_Report_{$healthRecord->employee_id}.pdf");
    }

    public function printForm32(EmployeeHealthRecord $healthRecord)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_form32', ['record' => $healthRecord]);
        
        return $pdf->setPaper('a4', 'landscape')
                   ->setOption(['isRemoteEnabled' => true])
                   ->download("Form_32_{$healthRecord->employee_id}.pdf");
    }

    public function printForm33(EmployeeHealthRecord $healthRecord)
    {
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_form33', ['record' => $healthRecord]);
        
        return $pdf->setPaper('a4', 'portrait')
                   ->setOption(['isRemoteEnabled' => true])
                   ->download("Form_33_{$healthRecord->employee_id}.pdf");
    }
}
