<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
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
            ->paginate(15)
            ->withQueryString();

        return view('activity-logs.index', compact('activities'));
    }
}
