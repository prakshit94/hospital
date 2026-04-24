<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\HealthCheckup;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\HealthRecordDocument;
use Illuminate\View\View;

class EmployeeHealthRecordController extends Controller
{
    /**
     * Display a listing of health checkups.
     */
    public function index(Request $request): View
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 10)));
        
        // We list checkups, but we can search by employee details
        $query = HealthCheckup::query()
            ->with(['employee.company', 'creator'])
            ->withCount('documents');

        if (session()->has('current_company_id')) {
            $query->whereHas('employee', function($q) {
                $q->where('company_id', session('current_company_id'));
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('employee', function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('company')) {
            $query->whereHas('employee', function($q) use ($request) {
                if (is_numeric($request->company)) {
                    $q->where('company_id', $request->company);
                } else {
                    $q->whereHas('company', function($sq) use ($request) {
                        $sq->where('name', 'like', "%{$request->company}%");
                    });
                }
            });
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->whereHas('employee', function($q) use ($status) {
                    $q->where('status', $status);
                });
            }
        }

        $records = $query->latest('examination_date')->paginate($perPage)->withQueryString();
        
        $companies = session()->has('current_company_id') 
            ? Company::where('id', session('current_company_id'))->get()
            : Company::where('is_active', true)->orderBy('name')->get();

        if ($request->ajax()) {
            return view('health-records.partials.results', compact('records', 'companies'));
        }

        return view('health-records.index', compact('records', 'companies'));
    }

    public function create(Request $request): View
    {
        $record = new HealthCheckup();
        $prefillEmployee = null;

        // Support pre-filling for existing employees (Quick Action from show page)
        // Uses ?prefill=<employee_uuid> — single param avoids HTML &amp; encoding bugs
        if ($request->filled('prefill')) {
            $prefillEmployee = Employee::where('uuid', $request->prefill)->first();

            if ($prefillEmployee) {
                $record->setRelation('employee', $prefillEmployee);
            }
        }

        return view('health-records.create', [
            'record'          => $record,
            'prefillEmployee' => $prefillEmployee,
        ]);
    }

    private function getValidationRules(): array
    {
        return [
            // Employee Info
            'company_id' => 'required|exists:companies,id',
            'employee_id' => 'nullable|string|max:255',
            'full_name' => 'required|string|max:255',
            'gender' => 'required|in:male,female,other',
            'dob' => 'required|date|before:today',
            'mobile' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'blood_group' => 'nullable|string|max:10',
            'father_name' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:255',
            'husband_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:1000',
            'identification_mark' => 'nullable|string|max:255',
            'joining_date' => 'nullable|date',
            'department' => 'nullable|string|max:255',
            'designation' => 'nullable|string|max:255',
            'habits' => 'nullable|string|max:1000',
            'dependent' => 'nullable|string|max:255',
            'prev_occ_history' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',

            // Checkup Vitals
            'examination_date' => 'required|date',
            'height' => 'nullable|numeric|between:0,300',
            'weight' => 'nullable|numeric|between:0,600',
            'bp_systolic' => 'nullable|integer|between:0,400',
            'bp_diastolic' => 'nullable|integer|between:0,300',
            'heart_rate' => 'nullable|integer|between:0,300',
            'temperature' => 'nullable|numeric|between:0,120',
            'spo2' => 'nullable|integer|between:0,100',

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
            
            // Detailed Exam
            'chest_before' => 'nullable|string|max:5000',
            'chest_after' => 'nullable|string|max:5000',
            'respiration_rate' => 'nullable|string|max:5000',
            'right_eye_specs' => 'nullable|string|max:5000',
            'left_eye_specs' => 'nullable|string|max:5000',
            'near_vision_right' => 'nullable|string|max:5000',
            'near_vision_left' => 'nullable|string|max:5000',
            'distant_vision_right' => 'nullable|string|max:5000',
            'distant_vision_left' => 'nullable|string|max:5000',
            'colour_vision' => 'nullable|string|max:5000',
            'eye' => 'nullable|string|max:5000',
            'nose' => 'nullable|string|max:5000',
            'conjunctiva' => 'nullable|string|max:5000',
            'ear' => 'nullable|string|max:5000',
            'tongue' => 'nullable|string|max:5000',
            'nails' => 'nullable|string|max:5000',
            'throat' => 'nullable|string|max:5000',
            'skin' => 'nullable|string|max:5000',
            'teeth' => 'nullable|string|max:5000',
            'pefr' => 'nullable|string|max:5000',
            'eczema' => 'nullable|string|max:5000',
            'cyanosis' => 'nullable|string|max:5000',
            'jaundice' => 'nullable|string|max:5000',
            'anaemia' => 'nullable|string|max:5000',
            'oedema' => 'nullable|string|max:5000',
            'clubbing' => 'nullable|string|max:5000',
            'allergy_status' => 'nullable|string|max:5000',
            'lymphnode' => 'nullable|string|max:5000',

            // Medical Conditions
            'hypertension' => 'nullable|string|max:5000',
            'diabetes' => 'nullable|string|max:5000',
            'dyslipidemia' => 'nullable|string|max:5000',
            'radiation_effect' => 'nullable|string|max:5000',
            'vertigo' => 'nullable|string|max:5000',
            'tuberculosis' => 'nullable|string|max:5000',
            'thyroid_disorder' => 'nullable|string|max:5000',
            'epilepsy' => 'nullable|string|max:5000',
            'asthma' => 'nullable|string|max:5000',
            'heart_disease' => 'nullable|string|max:5000',

            // Family History
            'family_father' => 'nullable|string|max:5000',
            'family_mother' => 'nullable|string|max:5000',
            'family_brother' => 'nullable|string|max:5000',
            'family_sister' => 'nullable|string|max:5000',

            // Systemic
            'resp_system' => 'nullable|string|max:5000',
            'genito_urinary' => 'nullable|string|max:5000',
            'cvs' => 'nullable|string|max:5000',
            'cns' => 'nullable|string|max:5000',
            'per_abdomen' => 'nullable|string|max:5000',
            'ent' => 'nullable|string|max:5000',

            // Investigations
            'pft' => 'nullable|string|max:5000',
            'xray_chest' => 'nullable|string|max:5000',
            'vertigo_test' => 'nullable|string|max:5000',
            'audiometry' => 'nullable|string|max:5000',
            'ecg' => 'nullable|string|max:5000',

            // Lab Reports
            'hb' => 'nullable|string|max:5000',
            'wbc_tc' => 'nullable|string|max:5000',
            'parasite_dc' => 'nullable|string|max:5000',
            'rbc' => 'nullable|string|max:5000',
            'platelet' => 'nullable|string|max:5000',
            'esr' => 'nullable|string|max:5000',
            'fbs' => 'nullable|string|max:5000',
            'pp2bs' => 'nullable|string|max:5000',
            'sgpt' => 'nullable|string|max:5000',
            's_creatinine' => 'nullable|string|max:5000',
            'rbs' => 'nullable|string|max:5000',
            's_chol' => 'nullable|string|max:5000',
            's_trg' => 'nullable|string|max:5000',
            's_hdl' => 'nullable|string|max:5000',
            's_ldl' => 'nullable|string|max:5000',
            'ch_ratio' => 'nullable|string|max:5000',

            // Urine
            'urine_colour' => 'nullable|string|max:5000',
            'urine_reaction' => 'nullable|string|max:5000',
            'urine_albumin' => 'nullable|string|max:5000',
            'urine_sugar' => 'nullable|string|max:5000',
            'urine_pus_cell' => 'nullable|string|max:5000',
            'urine_rbc' => 'nullable|string|max:5000',
            'urine_epi_cell' => 'nullable|string|max:5000',
            'urine_crystal' => 'nullable|string|max:5000',

            // Assessment & Admin
            'health_status' => 'nullable|string|max:5000',
            'doctor_name' => 'nullable|string|max:5000',
            'doctor_qualification' => 'nullable|string|max:5000',
            'doctor_signature' => 'nullable|string|max:5000',
            'doctor_seal' => 'nullable|string|max:5000',
            'job_restriction' => 'nullable|string|max:5000',
            'reviewed_by' => 'nullable|string|max:5000',
            'hazardous_process' => 'nullable|string|max:5000',
            'dangerous_operation' => 'nullable|string|max:5000',
            'materials_exposed' => 'nullable|string|max:5000',
        ];
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate($this->getValidationRules());

        return DB::transaction(function () use ($validated, $request) {
            // 1. Check for potential duplicate ID with a different name
            $existingEmployee = Employee::where('company_id', $validated['company_id'])
                ->where('employee_id', $validated['employee_id'])
                ->first();

            if ($existingEmployee && strtolower(trim($existingEmployee->full_name)) !== strtolower(trim($validated['full_name']))) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'employee_id' => ["The Employee ID '{$validated['employee_id']}' is already assigned to '{$existingEmployee->full_name}'. Please use a different ID or ensure the name matches."]
                ]);
            }

            // 2. Find or Create Employee
            $employee = Employee::updateOrCreate(
                [
                    'company_id' => $validated['company_id'],
                    'employee_id' => $validated['employee_id']
                ],
                [
                    'full_name' => $validated['full_name'],
                    'gender' => $validated['gender'],
                    'dob' => $validated['dob'],
                    'mobile' => $validated['mobile'],
                    'email' => $validated['email'],
                    'blood_group' => $validated['blood_group'],
                    'father_name' => $validated['father_name'],
                    'marital_status' => $validated['marital_status'],
                    'husband_name' => $validated['husband_name'],
                    'address' => $validated['address'],
                    'identification_mark' => $validated['identification_mark'],
                    'joining_date' => $validated['joining_date'],
                    'department' => $validated['department'],
                    'designation' => $validated['designation'],
                    'habits' => $validated['habits'] ?? null,
                    'dependent' => $validated['dependent'] ?? null,
                    'prev_occ_history' => $validated['prev_occ_history'] ?? null,
                    'status' => $validated['status'],
                ]
            );

            // 2. Create Health Checkup
            $checkupData = collect($validated)->except([
                'company_id', 'employee_id', 'full_name', 'gender', 'dob', 'mobile', 'email', 
                'blood_group', 'father_name', 'marital_status', 'husband_name', 'address', 
                'identification_mark', 'joining_date', 'department', 'designation', 
                'habits', 'dependent', 'prev_occ_history', 'status'
            ])->toArray();

            $checkupData['employee_id'] = $employee->id;
            $checkupData['created_by'] = auth()->id();

            // Calculate BMI
            if (($checkupData['height'] ?? 0) > 0 && ($checkupData['weight'] ?? 0) > 0) {
                $heightInMeters = $checkupData['height'] / 100;
                $checkupData['bmi'] = round($checkupData['weight'] / ($heightInMeters * $heightInMeters), 2);
            }

            $checkup = HealthCheckup::create($checkupData);

            // 3. Handle multiple document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store("health-record-documents/{$checkup->uuid}", 'public');
                        $checkup->documents()->create([
                            'original_name' => $file->getClientOriginalName(),
                            'path'          => $path,
                            'mime_type'     => $file->getMimeType(),
                            'size'          => $file->getSize(),
                            'uploaded_by'   => auth()->id(),
                        ]);
                    }
                }
            }

            ActivityLogService::logWithChanges(
                auth()->user(),
                $checkup,
                'health_checkup.created',
                "New health checkup recorded for: {$employee->full_name} ({$employee->company->name})"
            );

            $msg = "Health checkup for '{$employee->full_name}' has been successfully recorded.";

            if ($request->ajax()) {
                session()->flash('status', $msg);
                return response()->json([
                    'status'   => 'success',
                    'message'  => $msg,
                    'redirect' => route('health-records.show', $checkup->uuid)
                ]);
            }

            return redirect()->route('health-records.show', $checkup->uuid)->with('status', $msg);
        });
    }

    public function show(HealthCheckup $record): View
    {
        $record->loadMissing(['employee.company', 'documents', 'creator']);

        $history = HealthCheckup::where('employee_id', $record->employee_id)
            ->where('id', '!=', $record->id)
            ->latest('examination_date')
            ->get();

        $activities = ActivityLog::query()
            ->with('causer')
            ->where('subject_type', HealthCheckup::class)
            ->where('subject_id', $record->id)
            ->latest()
            ->take(10)
            ->get();

        $previousRecord = HealthCheckup::where('employee_id', $record->employee_id)
            ->where('examination_date', '<', $record->examination_date)
            ->orderBy('examination_date', 'desc')
            ->first();

        return view('health-records.show', [
            'record'         => $record,
            'employee'       => $record->employee,
            'history'        => $history,
            'previousRecord' => $previousRecord,
            'activities'     => $activities
        ]);
    }

    public function edit(HealthCheckup $record): View
    {
        $record->loadMissing(['employee', 'documents']);
        return view('health-records.edit', [
            'record' => $record,
            'employee' => $record->employee
        ]);
    }

    public function update(Request $request, HealthCheckup $record): RedirectResponse|JsonResponse
    {
        $validated = $request->validate($this->getValidationRules());

        return DB::transaction(function () use ($validated, $request, $record) {
            // 1. Check for potential duplicate ID with a different name (if ID/Company changed)
            $existingEmployee = Employee::where('company_id', $validated['company_id'])
                ->where('employee_id', $validated['employee_id'])
                ->first();

            if ($existingEmployee && $existingEmployee->id !== $record->employee_id && strtolower(trim($existingEmployee->full_name)) !== strtolower(trim($validated['full_name']))) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'employee_id' => ["The Employee ID '{$validated['employee_id']}' is already assigned to '{$existingEmployee->full_name}'."]
                ]);
            }

            // 2. Find or Create Employee (handles cases where ID or Company might have changed)
            $employee = Employee::updateOrCreate(
                [
                    'company_id' => $validated['company_id'],
                    'employee_id' => $validated['employee_id']
                ],
                [
                    'full_name' => $validated['full_name'],
                    'gender' => $validated['gender'],
                    'dob' => $validated['dob'],
                    'mobile' => $validated['mobile'],
                    'email' => $validated['email'],
                    'blood_group' => $validated['blood_group'],
                    'father_name' => $validated['father_name'],
                    'marital_status' => $validated['marital_status'],
                    'husband_name' => $validated['husband_name'],
                    'address' => $validated['address'],
                    'identification_mark' => $validated['identification_mark'],
                    'joining_date' => $validated['joining_date'],
                    'department' => $validated['department'],
                    'designation' => $validated['designation'],
                    'habits' => $validated['habits'] ?? null,
                    'dependent' => $validated['dependent'] ?? null,
                    'prev_occ_history' => $validated['prev_occ_history'] ?? null,
                    'status' => $validated['status'],
                ]
            );

            // 2. Update Checkup Details
            $checkupData = collect($validated)->except([
                'company_id', 'employee_id', 'full_name', 'gender', 'dob', 'mobile', 'email', 
                'blood_group', 'father_name', 'marital_status', 'husband_name', 'address', 
                'identification_mark', 'joining_date', 'department', 'designation', 
                'habits', 'dependent', 'prev_occ_history', 'status'
            ])->toArray();

            $checkupData['employee_id'] = $employee->id; // Link to the resolved employee
            $checkupData['updated_by'] = auth()->id();

            // Calculate BMI
            if (($checkupData['height'] ?? 0) > 0 && ($checkupData['weight'] ?? 0) > 0) {
                $heightInMeters = $checkupData['height'] / 100;
                $checkupData['bmi'] = round($checkupData['weight'] / ($heightInMeters * $heightInMeters), 2);
            }

            $record->update($checkupData);

            ActivityLogService::logWithChanges(
                auth()->user(),
                $record,
                'health_checkup.updated',
                "Updated health checkup for: {$record->employee->full_name}"
            );

            // Handle additional document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store("health-record-documents/{$record->uuid}", 'public');
                        $record->documents()->create([
                            'original_name' => $file->getClientOriginalName(),
                            'path'          => $path,
                            'mime_type'     => $file->getMimeType(),
                            'size'          => $file->getSize(),
                            'uploaded_by'   => auth()->id(),
                        ]);
                    }
                }
            }

            $msg = "Health checkup record has been updated.";
            
            if ($request->ajax()) {
                session()->flash('status', $msg);
                return response()->json([
                    'status'   => 'success',
                    'message'  => $msg,
                    'redirect' => route('health-records.show', $record->uuid)
                ]);
            }

            return redirect()->route('health-records.show', $record->uuid)->with('status', $msg);
        });
    }

    public function destroy(HealthCheckup $record): RedirectResponse|JsonResponse
    {
        $name = $record->employee->full_name;
        
        ActivityLogService::log(
            auth()->user(),
            'health_checkup.deleted',
            $record,
            "Deleted health checkup for: {$name} (Date: {$record->examination_date->format('d/m/Y')})"
        );

        $record->delete();

        $msg = "Health checkup record has been deleted.";

        if (request()->ajax()) {
            session()->flash('status', $msg);
            return response()->json(['status' => 'success', 'message' => $msg]);
        }
        
        return redirect()->route('health-records.index')->with('status', $msg);
    }

    // PDF printing methods will now receive HealthCheckup instead of the old record
    public function print(HealthCheckup $record)
    {
        $record->loadMissing(['employee.company']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print', [
            'record' => $record,
            'employee' => $record->employee
        ]);
        
        return $pdf->setPaper('a4')
                   ->setOption(['isRemoteEnabled' => true])
                   ->download("Medical_Report_{$record->employee->employee_id}.pdf");
    }

    public function printForm32(HealthCheckup $record)
    {
        $record->loadMissing(['employee.company']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_form32', [
            'record' => $record,
            'employee' => $record->employee
        ]);
        
        return $pdf->setPaper('a4', 'landscape')
                   ->setOption(['isRemoteEnabled' => true])
                   ->download("Form_32_{$record->employee->employee_id}.pdf");
    }

    public function printForm33(HealthCheckup $record)
    {
        $record->loadMissing(['employee.company']);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_form33', [
            'record' => $record,
            'employee' => $record->employee
        ]);
        
        return $pdf->setPaper('a4', 'portrait')
                   ->setOption(['isRemoteEnabled' => true])
                   ->download("Form_33_{$record->employee->employee_id}.pdf");
    }

    public function printAll(HealthCheckup $record): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse|RedirectResponse
    {
        $record->loadMissing(['employee.company', 'documents']);

        $tmpDir = storage_path('app/temp_pdfs');
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }
        
        // Clean up
        foreach (glob($tmpDir . "/*") as $file) {
            if (is_file($file) && (time() - filemtime($file) > 3600)) {
                @unlink($file);
            }
        }

        $pdfFilesToMerge = [];
        $uniqueId = uniqid();

        // Data for templates
        $data = ['record' => $record, 'employee' => $record->employee];

        // 1. Generate PDFs
        $paths = [
            'main' => "{$tmpDir}/{$uniqueId}_main.pdf",
            'form32' => "{$tmpDir}/{$uniqueId}_form32.pdf",
            'form33' => "{$tmpDir}/{$uniqueId}_form33.pdf"
        ];

        \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print', $data)->setPaper('a4', 'portrait')->setOption(['isRemoteEnabled' => true])->save($paths['main']);
        \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_form32', $data)->setPaper('a4', 'landscape')->setOption(['isRemoteEnabled' => true])->save($paths['form32']);
        \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_form33', $data)->setPaper('a4', 'portrait')->setOption(['isRemoteEnabled' => true])->save($paths['form33']);

        $pdfFilesToMerge = array_values($paths);

        // 4. Handle Attachments (Images)
        $imageAttachments = [];
        foreach ($record->documents as $doc) {
            $ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
            $fullPath = storage_path('app/public/' . $doc->path);
            if (!file_exists($fullPath)) continue;

            if ($ext === 'pdf') {
                // Ghostscript conversion logic ...
                $gsCommand = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'gswin64c' : 'gs';
                $convertedPdfPath = $tmpDir . DIRECTORY_SEPARATOR . uniqid() . '_converted.pdf';
                $cmd = sprintf('%s -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=%s %s', $gsCommand, escapeshellarg($convertedPdfPath), escapeshellarg($fullPath));
                @exec($cmd, $output, $returnVar);
                
                $pdfFilesToMerge[] = ($returnVar === 0 && file_exists($convertedPdfPath)) ? $convertedPdfPath : $fullPath;
            } elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
                $imageData = @file_get_contents($fullPath);
                if ($imageData) {
                    $imageAttachments[] = [
                        'name' => $doc->original_name,
                        'src'  => 'data:' . $mime . ';base64,' . base64_encode($imageData),
                        'size' => $doc->formatted_size,
                        'date' => $doc->created_at->format('d/m/Y'),
                    ];
                }
            }
        }

        if (!empty($imageAttachments)) {
            $imagesPdfPath = $tmpDir . "/{$uniqueId}_images.pdf";
            \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_attachments', ['record' => $record, 'employee' => $record->employee, 'imageAttachments' => $imageAttachments])
                ->setPaper('a4', 'portrait')->setOption(['isRemoteEnabled' => true])->save($imagesPdfPath);
            $pdfFilesToMerge[] = $imagesPdfPath;
        }

        // Merge
        $mergedPdfPath = $tmpDir . "/{$uniqueId}_merged.pdf";
        try {
            $merger = \Webklex\PDFMerger\Facades\PDFMergerFacade::init();
            foreach ($pdfFilesToMerge as $pdfFile) {
                if (file_exists($pdfFile)) $merger->addPDF($pdfFile, 'all');
            }
            $merger->merge();
            $merger->save($mergedPdfPath);
        } catch (\Exception $e) {
            Log::error("PDF merge failed: " . $e->getMessage());
        }

        // Clean up
        foreach ($pdfFilesToMerge as $file) {
            if (str_starts_with($file, $tmpDir) && file_exists($file)) @unlink($file);
        }

        if (file_exists($mergedPdfPath)) {
            return response()->download($mergedPdfPath, "Complete_Report_{$record->employee->employee_id}.pdf")->deleteFileAfterSend(true);
        }

        return back()->withErrors(['error' => 'Failed to generate combined PDF report.']);
    }

    /**
     * Generate the next sequential Employee No for a given company.
     */
    private function generateNextEmployeeId(?int $companyId): string
    {
        if (!$companyId) return '';
        $company = Company::find($companyId);
        if (!$company || !$company->code) return '';

        $prefix = strtoupper($company->code) . '-';
        $latestEmployee = Employee::where('company_id', $companyId)
            ->where('employee_id', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(employee_id) DESC')
            ->orderBy('employee_id', 'desc')
            ->first();

        if (!$latestEmployee) return $prefix . '001';
        $numberPart = substr($latestEmployee->employee_id, strlen($prefix));
        if (is_numeric($numberPart)) {
            return $prefix . str_pad((int) $numberPart + 1, 3, '0', STR_PAD_LEFT);
        }
        return $prefix . '001';
    }

    /**
     * Get the next available employee ID for a company.
     */
    public function getNextEmployeeId(Request $request): JsonResponse
    {
        $companyId = $request->query('company_id');
        
        if (!$companyId) {
            return response()->json(['next_id' => '']);
        }

        return response()->json([
            'next_id' => $this->generateNextEmployeeId((int) $companyId)
        ]);
    }
    /**
     * Delete a specific document.
     */
    public function deleteDocument(HealthRecordDocument $document): RedirectResponse
    {
        try {
            $recordUuid = $document->checkup->uuid;

            // Delete the file from storage
            if ($document->path && Storage::exists($document->path)) {
                Storage::delete($document->path);
            }

            // Delete from database
            $document->delete();

            return redirect()->route('health-records.show', $recordUuid)
                ->with('status', 'Document deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Error deleting document: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete document.');
        }
    }
}
