<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Village;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 10)));

        $customers = Customer::query()
            ->with(['primaryAddress.village', 'assignedTo'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();
                $query->where(function ($inner) use ($search) {
                    $inner->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('mobile', 'like', "%{$search}%")
                        ->orWhere('customer_code', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $status = $request->string('status')->toString();
                $status === 'deleted'
                    ? $query->onlyTrashed()
                    : $query->where('status', $status);
            })
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->string('type')->toString());
            })
            ->when($request->filled('lead_status'), function ($query) use ($request) {
                $query->where('lead_status', $request->string('lead_status')->toString());
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        return $request->ajax()
            ? view('customers.partials.results', compact('customers'))
            : view('customers.index', compact('customers'));
    }

    public function create(Request $request): View
    {
        $villages = Village::active()->orderBy('village_name')->get();

        if ($request->ajax()) {
            return view('customers.modal-form', [
                'villages' => $villages,
                'pageTitle' => 'New Customer',
                'pageDescription' => 'Register a new customer profile in the ecosystem.',
                'formAction' => route('customers.store'),
                'submitLabel' => 'Create Profile'
            ]);
        }

        return view('customers.create', compact('villages'));
    }

    public function store(StoreCustomerRequest $request): RedirectResponse|JsonResponse
    {
        return DB::transaction(function () use ($request) {

            $validated = $request->validated();
            $validated['created_by'] = auth()->id();

            if ($request->filled('crops_input')) {
                $validated['crops'] = array_map('trim', explode(',', $request->crops_input));
            }

            if ($request->filled('irrigation_types_input')) {
                $validated['irrigation_type'] = array_map('trim', explode(',', $request->irrigation_types_input));
            }

            $customer = Customer::create($validated);

            ActivityLogService::logWithChanges(
                auth()->user(),
                $customer,
                'customer.created',
                "Created customer profile: {$customer->display_name}"
            );

            $msg = "New customer '{$customer->display_name}' has been successfully registered.";
            session()->flash('status', $msg);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $msg,
                    'redirect' => route('customers.show', $customer->uuid)
                ]);
            }

            return redirect()->route('customers.index')
                ->with('success', $msg);
        });
    }

    public function show(Request $request, Customer $customer): View
    {
        $customer->load(['addresses.village', 'primaryAddress.village', 'assignedTo']);

        $activities = ActivityLog::query()
            ->with('causer')
            ->where('subject_type', $customer->getMorphClass())
            ->where('subject_id', $customer->getKey())
            ->latest()
            ->take(10)
            ->get();

        return $request->ajax()
            ? view('customers.modal-show', compact('customer', 'activities'))
            : view('customers.show', compact('customer', 'activities'));
    }

    public function edit(Request $request, Customer $customer): View
    {
        $villages = Village::active()->orderBy('village_name')->get();

        if ($request->ajax()) {
            return view('customers.modal-form', [
                'customer' => $customer,
                'villages' => $villages,
                'pageTitle' => 'Edit Customer',
                'pageDescription' => "Updating profile for {$customer->display_name}",
                'formAction' => route('customers.update', $customer->uuid),
                'formMethod' => 'PUT',
                'submitLabel' => 'Save Changes'
            ]);
        }

        return view('customers.edit', compact('customer', 'villages'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse|JsonResponse
    {
        return DB::transaction(function () use ($request, $customer) {

            $validated = $request->validated();
            $validated['updated_by'] = auth()->id();

            if ($request->has('crops_input')) {
                $validated['crops'] = $request->filled('crops_input')
                    ? array_map('trim', explode(',', $request->crops_input))
                    : null;
            }

            $customer->fill($validated);

            // ✅ FIX: save first
            $customer->save();

            // ✅ FIX: log after save
            ActivityLogService::logWithChanges(
                auth()->user(),
                $customer,
                'customer.updated',
                "Updated customer profile: {$customer->display_name}"
            );

            $msg = "Profile details for '{$customer->display_name}' have been updated.";
            session()->flash('status', $msg);

            if ($request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => $msg
                ]);
            }

            return redirect()->route('customers.show', $customer->uuid)
                ->with('success', $msg);
        });
    }

    public function destroy(Customer $customer): RedirectResponse|JsonResponse
    {
        return DB::transaction(function () use ($customer) {

            $name = $customer->display_name;

            ActivityLogService::log(
                auth()->user(),
                'customer.deleted',
                $customer,
                "Deleted customer: {$name}"
            );

            $customer->delete();

            $msg = "Customer '{$name}' has been moved to the archive.";

            return request()->ajax()
                ? response()->json(['status' => 'success', 'message' => $msg])
                : redirect()->route('customers.index')->with('success', $msg);
        });
    }

    public function toggleStatus(Customer $customer): JsonResponse
    {
        $oldStatus = $customer->status;
        $newStatus = $oldStatus === 'active' ? 'inactive' : 'active';

        $customer->update(['status' => $newStatus]);

        ActivityLogService::log(
            auth()->user(),
            'customer.status_toggled',
            $customer,
            "Changed status for {$customer->display_name} from {$oldStatus} to {$newStatus}.",
            ['old' => $oldStatus, 'new' => $newStatus]
        );

        return response()->json([
            'status' => 'success',
            'message' => "Customer '{$customer->display_name}' is now {$newStatus}.",
            'new_status' => $newStatus
        ]);
    }

    public function restore($id): RedirectResponse|JsonResponse
    {
        return DB::transaction(function () use ($id) {

            $customer = Customer::withTrashed()->where('uuid', $id)->firstOrFail();
            $customer->restore();

            ActivityLogService::log(
                auth()->user(),
                'customer.restored',
                $customer,
                "Restored customer: {$customer->display_name}"
            );

            $msg = "Profile for '{$customer->display_name}' has been restored to active status.";

            return request()->ajax()
                ? response()->json(['status' => 'success', 'message' => $msg])
                : redirect()->route('customers.index')->with('success', $msg);
        });
    }

    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|string|in:active,inactive,delete,restore,force-delete',
            'ids' => 'required|array',
            'ids.*' => 'string'
        ]);

        $action = $request->input('action');
        $uuids = $request->input('ids');
        $user = auth()->user();

        if (in_array($action, ['delete', 'restore', 'force-delete']) && !$user->hasPermission('customers.delete')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        if (in_array($action, ['active', 'inactive']) && !$user->hasPermission('customers.update')) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
        }

        try {
            $affectedNames = Customer::withTrashed()
                ->whereIn('uuid', $uuids)
                ->pluck('display_name')
                ->all();

            DB::transaction(function () use ($action, $uuids, $affectedNames) {

                $query = Customer::withTrashed()->whereIn('uuid', $uuids);

                match ($action) {
                    'delete' => $query->delete(),
                    'restore' => $query->restore(),
                    'force-delete' => $query->forceDelete(),
                    'active', 'inactive' => $query->update(['status' => $action]), // ✅ FIXED
                };

                $this->logBulkAction(
                    "customer.bulk_{$action}",
                    $uuids,
                    ucfirst($action) . " " . count($uuids) . " customers.",
                    ['affected_customers' => $affectedNames]
                );
            });

            return response()->json([
                'status' => 'success',
                'message' => "Bulk {$action} completed successfully."
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function logBulkAction(string $event, array $uuids, string $description, array $properties = []): void
    {
        ActivityLogService::log(
            auth()->user(),
            $event,
            null,
            $description,
            array_merge(['uuids' => $uuids], $properties)
        );
    }
}