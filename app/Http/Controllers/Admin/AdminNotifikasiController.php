<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CapturedNotification;
use App\Models\DeviceToken;
use Illuminate\Http\Request;

class AdminNotifikasiController extends Controller
{
    public function index(Request $request)
    {
        $query = CapturedNotification::query()->latest();

        if ($request->filled('device')) {
            $query->where('device_id', $request->device);
        }
        if ($request->filled('app')) {
            $query->where('package_name', $request->app);
        }
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('text', 'like', "%{$s}%")
                  ->orWhere('app_name', 'like', "%{$s}%");
            });
        }
        if ($request->filled('read')) {
            $query->where('is_read', $request->read === 'yes');
        }

        $notifications = $query->paginate(30);

        $devices = DeviceToken::orderBy('last_seen_at', 'desc')->get();
        $apps = CapturedNotification::select('package_name', 'app_name')
            ->whereNotNull('package_name')
            ->groupBy('package_name', 'app_name')
            ->orderBy('app_name')
            ->get();

        $stats = [
            'total' => CapturedNotification::count(),
            'today' => CapturedNotification::whereDate('created_at', today())->count(),
            'unread' => CapturedNotification::where('is_read', false)->count(),
            'devices' => DeviceToken::where('is_active', true)->count(),
        ];

        return view('admin.notifikasi', compact('notifications', 'devices', 'apps', 'stats'));
    }

    public function show($id)
    {
        $notification = CapturedNotification::findOrFail($id);
        $notification->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'data' => $notification,
        ]);
    }

    public function markRead(Request $request)
    {
        if ($request->filled('ids')) {
            CapturedNotification::whereIn('id', $request->ids)->update(['is_read' => true]);
        } else {
            CapturedNotification::where('is_read', false)->update(['is_read' => true]);
        }

        return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
    }

    public function destroy($id)
    {
        CapturedNotification::findOrFail($id)->delete();
        return back()->with('success', 'Notifikasi dihapus.');
    }

    public function destroyBatch(Request $request)
    {
        if ($request->filled('ids')) {
            CapturedNotification::whereIn('id', $request->ids)->delete();
        }
        return back()->with('success', 'Notifikasi terpilih dihapus.');
    }

    public function devices()
    {
        $devices = DeviceToken::withCount('notifications')
            ->orderBy('last_seen_at', 'desc')
            ->paginate(20);

        return view('admin.devices', compact('devices'));
    }

    public function toggleDevice($id)
    {
        $device = DeviceToken::findOrFail($id);
        $device->update(['is_active' => !$device->is_active]);

        return back()->with('success', 'Status device diperbarui.');
    }

    public function destroyDevice($id)
    {
        $device = DeviceToken::findOrFail($id);
        CapturedNotification::where('device_id', $device->device_id)->delete();
        $device->delete();

        return back()->with('success', 'Device dan semua notifikasinya dihapus.');
    }
}
