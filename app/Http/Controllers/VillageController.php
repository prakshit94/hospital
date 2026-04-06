<?php

namespace App\Http\Controllers;

use App\Models\Village;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class VillageController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 10)));
        $query = Village::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('village_name', 'like', "%{$search}%")
                  ->orWhere('pincode', 'like', "%{$search}%")
                  ->orWhere('district_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $villages = $query->latest()->paginate($perPage)->withQueryString();

        if ($request->ajax()) {
            return view('villages.partials.results', compact('villages'));
        }

        return view('villages.index', compact('villages'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'village_name' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'taluka_name' => 'nullable|string|max:255',
            'district_name' => 'nullable|string|max:255',
            'state_name' => 'nullable|string|max:255',
            'is_serviceable' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['is_active'] = true;
        $village = Village::create($validated);

        ActivityLogService::logWithChanges(
            auth()->user(),
            $village,
            'village.created',
            "Created new village: {$village->village_name}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Village created successfully',
            'village' => $village
        ]);
    }

    public function update(Request $request, Village $village): JsonResponse
    {
        $validated = $request->validate([
            'village_name' => 'required|string|max:255',
            'pincode' => 'required|string|max:10',
            'taluka_name' => 'nullable|string|max:255',
            'district_name' => 'nullable|string|max:255',
            'state_name' => 'nullable|string|max:255',
            'is_serviceable' => 'boolean',
            'status' => 'string|in:active,inactive',
        ]);

        if ($request->has('status')) {
            $validated['is_active'] = $request->status === 'active';
            unset($validated['status']);
        }

        $village->fill($validated);
        $village->updated_by = auth()->id();

        ActivityLogService::logWithChanges(
            auth()->user(),
            $village,
            'village.updated',
            "Updated village details: {$village->village_name}"
        );

        $village->save();

        return response()->json([
            'success' => true,
            'message' => 'Village updated successfully'
        ]);
    }

    public function destroy(Village $village): JsonResponse
    {
        $name = $village->village_name;
        $village->delete();

        ActivityLogService::log(
            auth()->user(),
            'village.deleted',
            null,
            "Deleted village: {$name}"
        );

        return response()->json([
            'success' => true,
            'message' => 'Village deleted successfully'
        ]);
    }

    public function toggleStatus(Village $village): JsonResponse
    {
        $oldStatus = $village->is_active ? 'active' : 'inactive';
        $newStatusBool = !$village->is_active;
        $newStatus = $newStatusBool ? 'active' : 'inactive';

        $village->update(['is_active' => $newStatusBool, 'updated_by' => auth()->id()]);

        ActivityLogService::log(
            auth()->user(),
            'village.status_toggled',
            $village,
            "Changed status for {$village->village_name} from {$oldStatus} to {$newStatus}.",
            ['old' => $oldStatus, 'new' => $newStatus]
        );

        return response()->json([
            'status' => 'success',
            'message' => "Village status updated to {$newStatus}.",
            'new_status' => $newStatus
        ]);
    }

    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|string|in:active,inactive,delete',
            'ids' => 'required|array',
            'ids.*' => 'string'
        ]);

        $action = $request->action;
        $uuids = $request->ids;

        try {
            DB::transaction(function () use ($action, $uuids) {
                $query = Village::whereIn('uuid', $uuids);
                $names = $query->pluck('village_name')->toArray();

                if ($action === 'delete') {
                    $query->delete();
                    $this->logBulkAction('village.bulk_deleted', $uuids, "Deleted " . count($uuids) . " villages.", ['names' => $names]);
                } else {
                    $query->update(['is_active' => $action === 'active', 'updated_by' => auth()->id()]);
                    $this->logBulkAction('village.bulk_status_updated', $uuids, "Updated status to {$action} for " . count($uuids) . " villages.", ['names' => $names]);
                }
            });

            return response()->json(['status' => 'success', 'message' => count($uuids) . ' villages processed successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function search(Request $request): JsonResponse
    {
        $search = $request->query('q');
        
        if (strlen($search) < 2) {
            return response()->json([]);
        }

        $villages = Village::query()
            ->active()
            ->where(function ($q) use ($search) {
                $q->where('village_name', 'like', "%{$search}%")
                  ->orWhere('pincode', 'like', "{$search}%")
                  ->orWhere('taluka_name', 'like', "%{$search}%")
                  ->orWhere('district_name', 'like', "%{$search}%")
                  ->orWhere('post_so_name', 'like', "%{$search}%");
            })
            ->select(['id', 'village_name', 'pincode', 'taluka_name', 'district_name', 'state_name', 'post_so_name'])
            ->limit(10)
            ->get();

        return response()->json($villages);
    }

    private function logBulkAction(string $event, array $ids, string $description, array $extra = []): void
    {
        ActivityLogService::log(
            auth()->user(),
            $event,
            null,
            $description,
            array_merge(['affected_uuids' => $ids], $extra)
        );
    }
}
