<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function markAsRead(Request $request): JsonResponse
    {
        $request->user()->update(['notifications_read_at' => now()]);

        return response()->json([
            'status' => 'success',
            'message' => 'Notifications marked as read successfully.',
        ]);
    }
}
