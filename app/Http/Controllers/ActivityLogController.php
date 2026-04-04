<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $perPage = max(5, min(100, (int) $request->integer('per_page', 5)));

        $activities = ActivityLog::query()
            ->with(['causer', 'subject'])
            ->when($request->string('search')->toString(), function ($query, string $search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('action', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('ip_address', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        if ($request->ajax()) {
            return view('activity-logs.partials.results', compact('activities'));
        }

        return view('activity-logs.index', compact('activities'));
    }
}
