<?php

namespace App\Http\Controllers;

use App\Models\EmployeeHealthRecord;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class EmployeeHealthRecordController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 10)));
        $query = EmployeeHealthRecord::query()->with('creator')->withCount('documents');

        if (session()->has('current_company_id')) {
            $query->where('company_id', session('current_company_id'));
        }

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
            if (is_numeric($request->company)) {
                $query->where('company_id', $request->company);
            } else {
                $query->where('company_name', $request->company);
            }
        }

        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'deleted') {
                $query->onlyTrashed();
            } else {
                $query->where('status', $status);
            }
        }

        $records = $query->latest()->paginate($perPage)->withQueryString();
        
        $companies = session()->has('current_company_id') 
            ? \App\Models\Company::where('id', session('current_company_id'))->get()
            : \App\Models\Company::where('is_active', true)->orderBy('name')->get();

        if ($request->ajax()) {
            return view('health-records.partials.results', compact('records', 'companies'));
        }

        return view('health-records.index', compact('records', 'companies'));
    }

    public function create(): View
    {
        $record = new EmployeeHealthRecord();

        // Pre-fill next Employee No from the active session company
        $companyId = session('current_company_id');
        if ($companyId) {
            $record->employee_id = $this->generateNextEmployeeId((int) $companyId);
        }

        return view('health-records.create', ['record' => $record]);
    }

    private function getValidationRules(): array
    {
        return [
            'company_id' => 'nullable|exists:companies,id',
            'company_name' => 'required|string|max:255',
            'employee_id' => 'nullable|string|max:255',
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

            $companyId = $validated['company_id'] ?? null;
            $resolvedCompany = null;

            if (session()->has('current_company_id') && !isset($validated['company_id'])) {
                $validated['company_id'] = session('current_company_id');
                $validated['company_name'] = session('current_company_name');
                $companyId = $validated['company_id'];
                $resolvedCompany = \App\Models\Company::find($companyId);
            } elseif (isset($validated['company_id'])) {
                $resolvedCompany = \App\Models\Company::find($validated['company_id']);
                if ($resolvedCompany) $validated['company_name'] = $resolvedCompany->name;
            }

            // Auto-generate Employee No if blank OR if prefix doesn't match the selected company
            if (empty($validated['employee_id'])) {
                // Blank — generate from the selected company
                $validated['employee_id'] = $this->generateNextEmployeeId($companyId);
            } elseif ($resolvedCompany && $resolvedCompany->code) {
                // Has a value — verify prefix matches the selected company's code
                $expectedPrefix = strtoupper($resolvedCompany->code) . '-';
                if (!str_starts_with(strtoupper($validated['employee_id']), $expectedPrefix)) {
                    // Prefix mismatch (stale auto-fill from a different company) — regenerate
                    $validated['employee_id'] = $this->generateNextEmployeeId($companyId);
                }
            }

            // Calculate BMI if height and weight are provided
            if (($validated['height'] ?? 0) > 0 && ($validated['weight'] ?? 0) > 0) {
                $heightInMeters = $validated['height'] / 100;
                $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
            } else {
                $validated['bmi'] = null;
            }

            $record = EmployeeHealthRecord::create($validated);

            // Handle multiple document uploads
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

            ActivityLogService::logWithChanges(
                auth()->user(),
                $record,
                'health_record.created',
                "Created health record for: {$record->full_name} ({$record->company_name})"
            );

            $msg = "Health record for '{$record->full_name}' has been successfully stored.";

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

    public function show(EmployeeHealthRecord $healthRecord): View
    {
        $healthRecord->loadMissing('documents');

        $activities = ActivityLog::query()
            ->with('causer')
            ->where('subject_type', $healthRecord->getMorphClass())
            ->where('subject_id', $healthRecord->getKey())
            ->latest()
            ->take(10)
            ->get();

        return view('health-records.show', [
            'record'     => $healthRecord,
            'activities' => $activities
        ]);
    }

    public function edit(EmployeeHealthRecord $healthRecord): View
    {
        $healthRecord->loadMissing('documents');
        return view('health-records.edit', ['record' => $healthRecord]);
    }

    public function update(Request $request, EmployeeHealthRecord $healthRecord): RedirectResponse|JsonResponse
    {
        $validated = $request->validate($this->getValidationRules());

        return DB::transaction(function () use ($validated, $request, $healthRecord) {
            $validated['updated_by'] = auth()->id();

            if (isset($validated['company_id'])) {
                $company = \App\Models\Company::find($validated['company_id']);
                if ($company) $validated['company_name'] = $company->name;
            }
            // Calculate BMI
            if (($validated['height'] ?? 0) > 0 && ($validated['weight'] ?? 0) > 0) {
                $heightInMeters = $validated['height'] / 100;
                $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
            } else {
                $validated['bmi'] = null;
            }

            $healthRecord->fill($validated);

            ActivityLogService::logWithChanges(
                auth()->user(),
                $healthRecord,
                'health_record.updated',
                "Updated health record for: {$healthRecord->full_name} ({$healthRecord->company_name})"
            );

            $healthRecord->save();

            // Handle additional document uploads
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store("health-record-documents/{$healthRecord->uuid}", 'public');
                        $healthRecord->documents()->create([
                            'original_name' => $file->getClientOriginalName(),
                            'path'          => $path,
                            'mime_type'     => $file->getMimeType(),
                            'size'          => $file->getSize(),
                            'uploaded_by'   => auth()->id(),
                        ]);
                    }
                }
            }

            $msg = "Health record for '{$healthRecord->full_name}' has been updated.";
            
            if ($request->ajax()) {
                session()->flash('status', $msg);
                return response()->json([
                    'status'   => 'success',
                    'message'  => $msg,
                    'redirect' => route('health-records.show', $healthRecord->uuid)
                ]);
            }

            return redirect()->route('health-records.show', $healthRecord->uuid)->with('status', $msg);
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

        if (request()->ajax()) {
            session()->flash('status', $msg);
            return response()->json(['status' => 'success', 'message' => $msg]);
        }
        
        return redirect()->route('health-records.index')->with('status', $msg);
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
        $healthRecord->loadMissing('company');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_form33', ['record' => $healthRecord]);
        
        return $pdf->setPaper('a4', 'portrait')
                   ->setOption(['isRemoteEnabled' => true])
                   ->download("Form_33_{$healthRecord->employee_id}.pdf");
    }

    public function printAll(EmployeeHealthRecord $healthRecord): \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
{
    $healthRecord->loadMissing(['company', 'documents']);

    $tmpDir = storage_path('app/temp_pdfs');
    if (!file_exists($tmpDir)) {
        mkdir($tmpDir, 0755, true);
    }

    // Cleanup old temp files
    foreach (glob($tmpDir . "/*") as $file) {
        if (is_file($file) && (time() - filemtime($file) > 3600)) {
            @unlink($file);
        }
    }

    $pdfFilesToMerge = [];
    $uniqueId = uniqid();

    // 1. Main PDF
    $mainPdfPath = $tmpDir . "/{$uniqueId}_main.pdf";
    \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print', ['record' => $healthRecord])
        ->setPaper('a4', 'portrait')
        ->setOption(['isRemoteEnabled' => true])
        ->save($mainPdfPath);
    $pdfFilesToMerge[] = $mainPdfPath;

    // 2. Form 32
    $form32PdfPath = $tmpDir . "/{$uniqueId}_form32.pdf";
    \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_form32', ['record' => $healthRecord])
        ->setPaper('a4', 'landscape')
        ->setOption(['isRemoteEnabled' => true])
        ->save($form32PdfPath);
    $pdfFilesToMerge[] = $form32PdfPath;

    // 3. Form 33
    $form33PdfPath = $tmpDir . "/{$uniqueId}_form33.pdf";
    \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_form33', ['record' => $healthRecord])
        ->setPaper('a4', 'portrait')
        ->setOption(['isRemoteEnabled' => true])
        ->save($form33PdfPath);
    $pdfFilesToMerge[] = $form33PdfPath;

    // 4. Attachments
    $imageAttachments = [];

    foreach ($healthRecord->documents as $doc) {
        $ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
        $fullPath = storage_path('app/public/' . $doc->path);

        if (!file_exists($fullPath)) continue;

        if ($ext === 'pdf') {

            $convertedPdfPath = $tmpDir . DIRECTORY_SEPARATOR . uniqid() . '_converted.pdf';

            // Detect OS
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $gsBinary = 'C:\\Program Files\\gs\\gs10.07.0\\bin\\gswin64c.exe';

                if (!file_exists($gsBinary)) {
                    \Log::error('Ghostscript not found', ['path' => $gsBinary]);
                    $pdfFilesToMerge[] = $fullPath;
                    continue;
                }

                $gsCommand = '"' . $gsBinary . '"';
            } else {
                $gsCommand = 'gs';
            }

            // Build command
            $cmd = sprintf(
                '%s -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dNOPAUSE -dQUIET -dBATCH -sOutputFile=%s %s',
                $gsCommand,
                escapeshellarg($convertedPdfPath),
                escapeshellarg($fullPath)
            );

            exec($cmd, $output, $returnVar);

            // Debug logs (remove later if not needed)
            \Log::info("Ghostscript CMD: " . $cmd);
            \Log::info("Ghostscript RETURN: " . $returnVar);

            if ($returnVar === 0 && file_exists($convertedPdfPath)) {
                $pdfFilesToMerge[] = $convertedPdfPath;
            } else {
                \Log::warning("Ghostscript conversion failed, using original PDF", [
                    'file' => $doc->original_name,
                    'return_var' => $returnVar,
                    'cmd' => $cmd
                ]);

                $pdfFilesToMerge[] = $fullPath;
            }

        } elseif (in_array($ext, ['jpg', 'jpeg', 'png'])) {

            $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';

            try {
                $imageData = file_get_contents($fullPath);

                if ($imageData !== false) {
                    $imageAttachments[] = [
                        'name' => $doc->original_name,
                        'src'  => 'data:' . $mime . ';base64,' . base64_encode($imageData),
                        'size' => $doc->formatted_size,
                        'date' => $doc->created_at->format('d/m/Y'),
                    ];
                }
            } catch (\Exception $e) {
                \Log::error("Image processing failed: " . $e->getMessage());
            }
        }
    }

    // 5. Image PDF
    if (!empty($imageAttachments)) {
        $imagesPdfPath = $tmpDir . "/{$uniqueId}_images.pdf";

        \Barryvdh\DomPDF\Facade\Pdf::loadView('health-records.print_attachments', [
            'record' => $healthRecord,
            'imageAttachments' => $imageAttachments
        ])
        ->setPaper('a4', 'portrait')
        ->setOption(['isRemoteEnabled' => true])
        ->save($imagesPdfPath);

        $pdfFilesToMerge[] = $imagesPdfPath;
    }

    // 6. Merge PDFs
    $mergedPdfPath = $tmpDir . "/{$uniqueId}_merged.pdf";

    try {
        $merger = \Webklex\PDFMerger\Facades\PDFMergerFacade::init();

        foreach ($pdfFilesToMerge as $pdfFile) {
            if (file_exists($pdfFile)) {
                $merger->addPDF($pdfFile, 'all');
            } else {
                \Log::error("Missing file for merge: " . $pdfFile);
            }
        }

        $merger->merge();
        $merger->save($mergedPdfPath);

    } catch (\Exception $e) {
        \Log::error("PDF merge failed: " . $e->getMessage(), [
            'files' => $pdfFilesToMerge
        ]);
    }

    // 7. Cleanup temp files
    foreach ($pdfFilesToMerge as $file) {
        if (str_starts_with($file, $tmpDir) && file_exists($file)) {
            @unlink($file);
        }
    }

    if (file_exists($mergedPdfPath)) {
        return response()
            ->download($mergedPdfPath, "Complete_Report_{$healthRecord->employee_id}.pdf")
            ->deleteFileAfterSend(true);
    }

    return back()->withErrors(['error' => 'Failed to generate combined PDF report.']);
}
    public function bulkAction(Request $request): JsonResponse|RedirectResponse|\Illuminate\Http\Response
    {
        $request->validate([
            'action' => 'required|string|in:delete,restore,force-delete,print',
            'form_type' => 'nullable|string|in:medical_report,form32,form33',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $action = $request->input('action');
        $ids = $request->input('ids');
        $formType = $request->input('form_type', 'medical_report');

        // Action-specific permission checks
        $user = auth()->user();
        if (in_array($action, ['delete', 'force-delete']) && !$user->hasPermission('health_records.delete')) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized for this destructive action.'], 403);
            }
            return redirect()->back()->withErrors(['error' => 'Unauthorized for this destructive action.']);
        }
        
        if ($action === 'restore' && !$user->hasPermission('health_records.update')) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized for this action.'], 403);
            }
            return redirect()->back()->withErrors(['error' => 'Unauthorized for this action.']);
        }

        if ($action === 'print' && !$user->hasPermission('health_records.view')) {
            if ($request->ajax()) {
                return response()->json(['status' => 'error', 'message' => 'Unauthorized to view/print records.'], 403);
            }
            return redirect()->back()->withErrors(['error' => 'Unauthorized to view/print records.']);
        }

        if (in_array($action, ['delete', 'restore', 'force-delete'])) {
            try {
                DB::transaction(function () use ($action, $ids) {
                    $query = EmployeeHealthRecord::withTrashed()->whereIn('id', $ids);
                    
                    switch ($action) {
                        case 'delete':
                            $query->delete();
                            ActivityLogService::log(auth()->user(), 'health_record.bulk_deleted', null, "Bulk deleted " . count($ids) . " health records.");
                            break;
                        case 'restore':
                            $query->restore();
                            ActivityLogService::log(auth()->user(), 'health_record.bulk_restored', null, "Bulk restored " . count($ids) . " health records.");
                            break;
                        case 'force-delete':
                            $query->forceDelete();
                            ActivityLogService::log(auth()->user(), 'health_record.bulk_permanently_deleted', null, "Bulk permanently deleted " . count($ids) . " health records.");
                            break;
                    }
                });

                $actionLabel = $action === 'force-delete' ? 'permanently deleted' : ($action === 'delete' ? 'deleted' : 'restored');
                $msg = "Successfully {$actionLabel} " . count($ids) . " records.";
                if ($request->ajax()) {
                    session()->flash('status', $msg);
                    return response()->json(['status' => 'success', 'message' => $msg]);
                }
                
                return redirect()->back()->with('status', $msg);
            } catch (\Exception $e) {
                if ($request->ajax()) {
                    return response()->json(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()], 500);
                }
                return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
            }
        }

        if ($action === 'print') {
            $records = EmployeeHealthRecord::whereIn('id', $ids)->with('company')->get();
            
            $view = 'health-records.bulk_print';
            $filename = "Bulk_Medical_Reports.pdf";
            $orientation = 'portrait';

            if ($formType === 'form32') {
                $view = 'health-records.bulk_print_form32';
                $filename = "Bulk_Form_32.pdf";
                $orientation = 'landscape';
            } elseif ($formType === 'form33') {
                $view = 'health-records.bulk_print_form33';
                $filename = "Bulk_Form_33.pdf";
            }

            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, ['records' => $records]);
            return $pdf->setPaper('a4', $orientation)
                       ->setOption(['isRemoteEnabled' => true])
                       ->download($filename);
        }

        return response()->json(['status' => 'error', 'message' => 'Invalid action.'], 422);
    }

    public function restore($uuid): RedirectResponse
    {
        $record = EmployeeHealthRecord::withTrashed()->where('uuid', $uuid)->firstOrFail();
        $record->restore();

        ActivityLogService::log(
            auth()->user(),
            'health_record.restored',
            $record,
            "Restored health record for: {$record->full_name}."
        );

        return redirect()
            ->route('health-records.index', ['status' => 'deleted'])
            ->with('status', 'Health record restored successfully.');
    }

    public function forceDelete($uuid): RedirectResponse
    {
        $record = EmployeeHealthRecord::withTrashed()->where('uuid', $uuid)->firstOrFail();
        $name = $record->full_name;

        ActivityLogService::log(
            auth()->user(),
            'health_record.permanently_deleted',
            $record,
            "Permanently deleted health record for: {$name}."
        );

        $record->forceDelete();

        return redirect()
            ->route('health-records.index', ['status' => 'deleted'])
            ->with('status', 'Health record permanently deleted successfully.');
    }

    public function getNextEmployeeId(Request $request): \Illuminate\Http\JsonResponse
    {
        $companyId = $request->get('company_id');
        if (!$companyId) {
            return response()->json(['error' => 'Company ID is required'], 400);
        }

        $company = Company::find($companyId);
        if (!$company || !$company->code) {
            return response()->json(['next_id' => '']);
        }

        return response()->json(['next_id' => $this->generateNextEmployeeId($companyId)]);
    }

    /**
     * Generate the next sequential Employee No for a given company.
     * Used by both the AJAX endpoint and the store() fallback.
     */
    private function generateNextEmployeeId(?int $companyId): string
    {
        if (!$companyId) {
            return '';
        }

        $company = Company::find($companyId);
        if (!$company || !$company->code) {
            return '';
        }

        $prefix = strtoupper($company->code) . '-';

        $latestRecord = EmployeeHealthRecord::where('company_id', $companyId)
            ->where('employee_id', 'like', $prefix . '%')
            ->orderByRaw('LENGTH(employee_id) DESC')
            ->orderBy('employee_id', 'desc')
            ->first();

        if (!$latestRecord) {
            return $prefix . '001';
        }

        $numberPart = substr($latestRecord->employee_id, strlen($prefix));

        if (is_numeric($numberPart)) {
            return $prefix . str_pad((int) $numberPart + 1, 3, '0', STR_PAD_LEFT);
        }

        return $prefix . '001';
    }
}

