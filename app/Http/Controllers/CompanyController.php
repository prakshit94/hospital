<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ActivityLog;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 10)));
        
        $query = Company::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->string('status')->toString();
                if ($status === 'deleted') {
                    $query->onlyTrashed();
                } else {
                    $query->where('is_active', $status === 'active');
                }
            });

        $companies = $query->latest()->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return view('companies.partials.results', compact('companies'));
        }

        return view('companies.index', compact('companies'));
    }

    public function create(Request $request): View
    {
        if ($request->ajax()) {
            return view('companies.modal-form', [
                'company' => new Company(['is_active' => true]),
                'pageTitle' => 'Create Company',
                'pageDescription' => 'Add a new corporate partner to the system.',
                'formAction' => route('companies.store'),
                'formMethod' => 'POST',
                'submitLabel' => 'Save Company',
            ]);
        }

        return view('companies.create');
    }

    public function store(Request $request): RedirectResponse|JsonResponse
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
            'company.created',
            "Created company: {$company->name}"
        );

        $msg = "Company '{$company->name}' created successfully.";

        if ($request->ajax()) {
            session()->flash('status', $msg);
            return response()->json([
                'status' => 'success',
                'message' => $msg,
            ]);
        }

        return redirect()->route('companies.index')->with('status', $msg);
    }

    public function show(Request $request, Company $company): View
    {
        $activities = ActivityLog::query()
            ->with('causer')
            ->where('subject_type', $company->getMorphClass())
            ->where('subject_id', $company->getKey())
            ->latest()
            ->take(10)
            ->get();

        if ($request->ajax()) {
            return view('companies.modal-show', compact('company', 'activities'));
        }

        return view('companies.show', compact('company', 'activities'));
    }

    public function edit(Request $request, Company $company): View
    {
        if ($request->ajax()) {
            return view('companies.modal-form', [
                'company' => $company,
                'pageTitle' => 'Edit Company',
                'pageDescription' => "Update details for {$company->name}.",
                'formAction' => route('companies.update', $company),
                'formMethod' => 'PUT',
                'submitLabel' => 'Update Company',
            ]);
        }

        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company): RedirectResponse|JsonResponse
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
            'company.updated',
            "Updated company: {$company->name}"
        );

        $company->save();

        $msg = "Company '{$company->name}' updated successfully.";

        if ($request->ajax()) {
            session()->flash('status', $msg);
            return response()->json([
                'status' => 'success',
                'message' => $msg,
            ]);
        }

        return redirect()->route('companies.index')->with('status', $msg);
    }

    public function destroy(Company $company): RedirectResponse|JsonResponse
    {
        $name = $company->name;

        ActivityLogService::log(
            auth()->user(),
            'company.deleted',
            $company,
            "Deleted company: {$name}"
        );

        $company->delete();

        $msg = "Company '{$name}' deleted successfully.";

        if (request()->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => $msg,
            ]);
        }

        return redirect()->route('companies.index')->with('status', $msg);
    }

    public function toggleStatus(Company $company): JsonResponse
    {
        $oldStatus = $company->is_active ? 'active' : 'inactive';
        $newStatusBool = !$company->is_active;
        $newStatus = $newStatusBool ? 'active' : 'inactive';

        $company->update(['is_active' => $newStatusBool]);

        ActivityLogService::log(
            auth()->user(),
            'company.status_toggled',
            $company,
            "Changed status for {$company->name} from {$oldStatus} to {$newStatus}.",
            ['old' => $oldStatus, 'new' => $newStatus]
        );

        $msg = "Company '{$company->name}' is now {$newStatus}.";
        session()->flash('status', $msg);

        return response()->json([
            'status' => 'success',
            'message' => $msg,
            'new_status' => $newStatus,
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
            return response()->json(['status' => 'error', 'message' => 'Unauthorized for this destructive action.'], 403);
        }
        
        if (in_array($action, ['active', 'inactive', 'restore']) && !$user->hasPermission('companies.update')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized to update company status.'], 403);
        }

        try {
            DB::transaction(function () use ($action, $ids) {
                $query = Company::withTrashed()->whereIn('id', $ids);
                $names = $query->pluck('name')->toArray();

                switch ($action) {
                    case 'delete':
                        $query->delete();
                        $this->logBulkAction('company.bulk_deleted', $ids, "Deleted " . count($ids) . " companies.", ['names' => $names]);
                        break;
                    case 'restore':
                        $query->restore();
                        $this->logBulkAction('company.bulk_restored', $ids, "Restored " . count($ids) . " companies.", ['names' => $names]);
                        break;
                    case 'active':
                    case 'inactive':
                        Company::whereIn('id', $ids)->update(['is_active' => $action === 'active']);
                        $this->logBulkAction('company.bulk_status_updated', $ids, "Updated status to {$action} for " . count($ids) . " companies.", ['names' => $names]);
                        break;
                }
            });

            $msg = count($ids) . ' companies processed successfully.';
            session()->flash('status', $msg);

            return response()->json([
                'status' => 'success',
                'message' => $msg,
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

    public function switch(Request $request): RedirectResponse
    {
        $companyId = $request->input('company_id');
        
        if ($companyId === 'all') {
            session()->forget('current_company_id');
            session()->forget('current_company_name');
        } else {
            $company = Company::findOrFail($companyId);
            session(['current_company_id' => $company->id]);
            session(['current_company_name' => $company->name]);
        }

        return back()->with('status', 'Company context switched.');
    }

    public function restore($id): RedirectResponse
    {
        $company = Company::withTrashed()->findOrFail($id);
        $company->restore();

        ActivityLogService::log(
            auth()->user(),
            'company.restored',
            $company,
            "Restored company: {$company->name}"
        );

        return redirect()->route('companies.index')->with('status', "Company '{$company->name}' restored successfully.");
    }
}
