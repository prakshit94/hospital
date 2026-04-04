<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $activities = ActivityLog::query()
            ->with(['causer', 'subject'])
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('action', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return response()->json($activities);
    }
}
