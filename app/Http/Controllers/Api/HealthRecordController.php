<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmployeeHealthRecord;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HealthRecordController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $this->authorizePermission('health_records.view');

        $perPage = $request->integer('per_page', 15);
        $query = EmployeeHealthRecord::query()->with(['creator', 'company']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%")
                  ->orWhere('company_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $records = $query->latest()->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $records,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $this->authorizePermission('health_records.create');

        $validator = Validator::make($request->all(), $this->getValidationRules());

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        return DB::transaction(function () use ($validated) {
            $validated['created_by'] = auth()->id();
            
            if (isset($validated['company_id'])) {
                $company = \App\Models\Company::find($validated['company_id']);
                if ($company) {
                    $validated['company_name'] = $company->name;
                }
            }

            // Calculate BMI
            if (($validated['height'] ?? 0) > 0 && ($validated['weight'] ?? 0) > 0) {
                $heightInMeters = $validated['height'] / 100;
                $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
            }

            $record = EmployeeHealthRecord::create($validated);

            ActivityLogService::logWithChanges(
                auth()->user(),
                $record,
                'health_record.created',
                "API: Created health record for: {$record->full_name} ({$record->company_name})"
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Health record created successfully.',
                'data' => $record
            ], 201);
        });
    }

    public function show(string $id): JsonResponse
    {
        $this->authorizePermission('health_records.view');

        $record = EmployeeHealthRecord::where('id', $id)
            ->orWhere('uuid', $id)
            ->with(['creator', 'updater', 'company'])
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $record,
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $this->authorizePermission('health_records.update');

        $record = EmployeeHealthRecord::where('id', $id)
            ->orWhere('uuid', $id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), array_merge($this->getValidationRules(), [
            'status' => 'sometimes|in:active,inactive',
        ]));

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validated = $validator->validated();

        return DB::transaction(function () use ($validated, $record) {
            $validated['updated_by'] = auth()->id();

            if (isset($validated['company_id'])) {
                $company = \App\Models\Company::find($validated['company_id']);
                if ($company) {
                    $validated['company_name'] = $company->name;
                }
            }

            // Calculate BMI
            if (($validated['height'] ?? 0) > 0 && ($validated['weight'] ?? 0) > 0) {
                $heightInMeters = $validated['height'] / 100;
                $validated['bmi'] = round($validated['weight'] / ($heightInMeters * $heightInMeters), 2);
            }

            $record->fill($validated);

            ActivityLogService::logWithChanges(
                auth()->user(),
                $record,
                'health_record.updated',
                "API: Updated health record for: {$record->full_name} ({$record->company_name})"
            );

            $record->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Health record updated successfully.',
                'data' => $record
            ]);
        });
    }

    public function destroy(string $id): JsonResponse
    {
        $this->authorizePermission('health_records.delete');

        $record = EmployeeHealthRecord::where('id', $id)
            ->orWhere('uuid', $id)
            ->firstOrFail();

        ActivityLogService::log(
            auth()->user(),
            'health_record.deleted.api',
            $record,
            "API: Deleted health record for: {$record->full_name}"
        );

        $record->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Health record deleted successfully.',
        ]);
    }

    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|string|in:delete,restore,force-delete',
            'ids' => 'required|array',
            'ids.*' => 'required', // Could be UUID or ID
        ]);

        $action = $request->input('action');
        $ids = $request->input('ids');

        $user = auth()->user();
        if (in_array($action, ['delete', 'force-delete']) && !$user->hasPermission('health_records.delete')) {
            return response()->json(['message' => 'Unauthorized for deletion.'], 403);
        }
        if ($action === 'restore' && !$user->hasPermission('health_records.update')) {
            return response()->json(['message' => 'Unauthorized for restoration.'], 403);
        }

        try {
            DB::transaction(function () use ($action, $ids) {
                $query = EmployeeHealthRecord::withTrashed()
                    ->where(function($q) use ($ids) {
                        $q->whereIn('id', $ids)->orWhereIn('uuid', $ids);
                    });

                switch ($action) {
                    case 'delete':
                        $query->delete();
                        ActivityLogService::log(auth()->user(), 'health_record.bulk_deleted.api', null, "API: Bulk deleted " . count($ids) . " health records.");
                        break;
                    case 'restore':
                        $query->restore();
                        ActivityLogService::log(auth()->user(), 'health_record.bulk_restored.api', null, "API: Bulk restored " . count($ids) . " health records.");
                        break;
                    case 'force-delete':
                        $query->forceDelete();
                        ActivityLogService::log(auth()->user(), 'health_record.bulk_permanently_deleted.api', null, "API: Bulk permanently deleted " . count($ids) . " health records.");
                        break;
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => count($ids) . ' records processed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function restore(string $id): JsonResponse
    {
        $this->authorizePermission('health_records.update');

        $record = EmployeeHealthRecord::withTrashed()
            ->where('id', $id)
            ->orWhere('uuid', $id)
            ->firstOrFail();

        $record->restore();

        ActivityLogService::log(
            auth()->user(),
            'health_record.restored.api',
            $record,
            "API: Restored health record for: {$record->full_name}"
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Health record restored successfully.',
            'data' => $record
        ]);
    }

    public function forceDelete(string $id): JsonResponse
    {
        $this->authorizePermission('health_records.delete');

        $record = EmployeeHealthRecord::withTrashed()
            ->where('id', $id)
            ->orWhere('uuid', $id)
            ->firstOrFail();

        ActivityLogService::log(
            auth()->user(),
            'health_record.permanently_deleted.api',
            $record,
            "API: Permanently deleted health record for: {$record->full_name}"
        );

        $record->forceDelete();

        return response()->json([
            'status' => 'success',
            'message' => 'Health record permanently deleted successfully.',
        ]);
    }

    protected function authorizePermission(string $permission)
    {
        if (!auth()->user()->hasPermission($permission)) {
            abort(403, 'Unauthorized.');
        }
    }

    protected function getValidationRules(): array
    {
        return [
            'company_id' => 'required|exists:companies,id',
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
            'medical_history' => 'nullable|string|max:2000',
            'current_medication' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:1000',
            'physical_exam' => 'nullable|string|max:2000',
            'diagnosis' => 'nullable|string|max:2000',
            'advice' => 'nullable|string|max:2000',
            'status' => 'required|in:active,inactive',
        ];
    }
}
