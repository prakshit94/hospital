<?php

namespace App\Http\Controllers;

use App\Models\UserDevice;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $devices = $request->user()->devices()->latest('last_active_at')->get();
        $currentDeviceId = md5($request->userAgent() . $request->user()->id);

        return view('devices.index', compact('devices', 'currentDeviceId'));
    }

    public function destroy(Request $request, UserDevice $device)
    {
        if ($device->user_id !== $request->user()->id) {
            abort(403);
        }

        $device->delete();

        return back()->with('status', 'Device removed successfully.');
    }
}
