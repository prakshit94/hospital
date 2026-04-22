<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->integer('per_page', 50);
        $query = Company::query()->where('is_active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $companies = $query->orderBy('name')->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $companies,
        ]);
    }

    public function show(Company $company): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $company,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies',
            'code' => 'nullable|string|max:50|unique:companies',
            'address' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $company = Company::create($validated);

        ActivityLogService::logWithChanges(
            auth()->user(),
            $company,
            'company.created.api',
            "API: Created company: {$company->name}"
        );

        return response()->json([
            'status' => 'success',
            'message' => "Company '{$company->name}' created successfully.",
            'data' => $company,
        ], 201);
    }

    public function update(Request $request, Company $company): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:companies,name,' . $company->id,
            'code' => 'nullable|string|max:50|unique:companies,code,' . $company->id,
            'address' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'is_active' => 'boolean',
        ]);

        $company->fill($validated);
        
        ActivityLogService::logWithChanges(
            auth()->user(),
            $company,
            'company.updated.api',
            "API: Updated company: {$company->name}"
        );

        $company->save();

        return response()->json([
            'status' => 'success',
            'message' => "Company '{$company->name}' updated successfully.",
            'data' => $company,
        ]);
    }

    public function destroy(Company $company): JsonResponse
    {
        $name = $company->name;

        ActivityLogService::log(
            auth()->user(),
            'company.deleted.api',
            $company,
            "API: Deleted company: {$name}"
        );

        $company->delete();

        return response()->json([
            'status' => 'success',
            'message' => "Company '{$name}' deleted successfully.",
        ]);
    }

    public function toggleStatus(Company $company): JsonResponse
    {
        $oldStatus = $company->is_active ? 'active' : 'inactive';
        $newStatusBool = !$company->is_active;
        $newStatus = $newStatusBool ? 'active' : 'inactive';

        $company->update(['is_active' => $newStatusBool]);

        ActivityLogService::log(
            auth()->user(),
            'company.status_toggled.api',
            $company,
            "API: Changed status for {$company->name} from {$oldStatus} to {$newStatus}.",
            ['old' => $oldStatus, 'new' => $newStatus]
        );

        return response()->json([
            'status' => 'success',
            'message' => "Company '{$company->name}' is now {$newStatus}.",
            'data' => [
                'new_status' => $newStatus,
            ],
        ]);
    }

    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|string|in:active,inactive,delete,restore',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        $action = $request->input('action');
        $ids = $request->input('ids');

        // Action-specific permission checks
        $user = auth()->user();
        if ($action === 'delete' && !$user->hasPermission('companies.delete')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized for this destructive action.',
            ], 403);
        }
        
        if (in_array($action, ['active', 'inactive', 'restore']) && !$user->hasPermission('companies.update')) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized to update company status.',
            ], 403);
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($action, $ids) {
                $query = Company::withTrashed()->whereIn('id', $ids);
                $names = $query->pluck('name')->toArray();

                switch ($action) {
                    case 'delete':
                        $query->delete();
                        $this->logBulkAction('company.bulk_deleted.api', $ids, "API: Deleted " . count($ids) . " companies.", ['names' => $names]);
                        break;
                    case 'restore':
                        $query->restore();
                        $this->logBulkAction('company.bulk_restored.api', $ids, "API: Restored " . count($ids) . " companies.", ['names' => $names]);
                        break;
                    case 'active':
                    case 'inactive':
                        Company::whereIn('id', $ids)->update(['is_active' => $action === 'active']);
                        $this->logBulkAction('company.bulk_status_updated.api', $ids, "API: Updated status to {$action} for " . count($ids) . " companies.", ['names' => $names]);
                        break;
                }
            });

            return response()->json([
                'status' => 'success',
                'message' => count($ids) . ' companies processed successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    private function logBulkAction(string $event, array $ids, string $description, array $extra = []): void
    {
        ActivityLogService::log(
            auth()->user(),
            $event,
            null,
            $description,
            array_merge(['affected_ids' => $ids], $extra)
        );
    }

    public function restore($id): JsonResponse
    {
        $company = Company::withTrashed()->findOrFail($id);
        $company->restore();

        ActivityLogService::log(
            auth()->user(),
            'company.restored.api',
            $company,
            "API: Restored company: {$company->name}"
        );

        return response()->json([
            'status' => 'success',
            'message' => "Company '{$company->name}' restored successfully.",
            'data' => $company,
        ]);
    }
}
