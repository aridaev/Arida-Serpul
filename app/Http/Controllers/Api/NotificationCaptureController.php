<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CapturedNotification;
use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationCaptureController extends Controller
{
    /**
     * Register device & get API token.
     * POST /api/device/register
     */
    public function registerDevice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_id' => 'required|string|max:255',
            'device_name' => 'nullable|string|max:255',
            'device_model' => 'nullable|string|max:255',
            'android_version' => 'nullable|string|max:50',
            'app_version' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $device = DeviceToken::where('device_id', $request->device_id)->first();

        if ($device) {
            $device->update([
                'device_name' => $request->device_name ?? $device->device_name,
                'device_model' => $request->device_model ?? $device->device_model,
                'android_version' => $request->android_version ?? $device->android_version,
                'app_version' => $request->app_version ?? $device->app_version,
                'last_seen_at' => now(),
                'is_active' => true,
            ]);
        } else {
            $device = DeviceToken::create([
                'device_id' => $request->device_id,
                'device_name' => $request->device_name,
                'device_model' => $request->device_model,
                'android_version' => $request->android_version,
                'app_version' => $request->app_version,
                'api_token' => DeviceToken::generateToken(),
                'last_seen_at' => now(),
                'is_active' => true,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Device registered',
            'data' => [
                'device_id' => $device->device_id,
                'api_token' => $device->api_token,
            ],
        ]);
    }

    /**
     * Receive captured notification from Android app.
     * POST /api/notifications/capture
     * Header: Authorization: Bearer {api_token}
     */
    public function capture(Request $request)
    {
        $device = $this->authenticateDevice($request);
        if (!$device) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'package_name' => 'nullable|string|max:255',
            'app_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:500',
            'text' => 'nullable|string',
            'big_text' => 'nullable|string',
            'ticker' => 'nullable|string|max:500',
            'tag' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'extras' => 'nullable|array',
            'posted_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $notification = CapturedNotification::create([
            'device_id' => $device->device_id,
            'device_name' => $device->device_name,
            'package_name' => $request->package_name,
            'app_name' => $request->app_name,
            'title' => $request->title,
            'text' => $request->text,
            'big_text' => $request->big_text,
            'ticker' => $request->ticker,
            'tag' => $request->tag,
            'category' => $request->category,
            'extras' => $request->extras,
            'posted_at' => $request->posted_at ?? now(),
        ]);

        $device->update(['last_seen_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'Notification captured',
            'data' => ['id' => $notification->id],
        ], 201);
    }

    /**
     * Batch capture multiple notifications at once.
     * POST /api/notifications/capture-batch
     * Header: Authorization: Bearer {api_token}
     */
    public function captureBatch(Request $request)
    {
        $device = $this->authenticateDevice($request);
        if (!$device) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'notifications' => 'required|array|min:1|max:100',
            'notifications.*.package_name' => 'nullable|string|max:255',
            'notifications.*.app_name' => 'nullable|string|max:255',
            'notifications.*.title' => 'nullable|string|max:500',
            'notifications.*.text' => 'nullable|string',
            'notifications.*.big_text' => 'nullable|string',
            'notifications.*.ticker' => 'nullable|string|max:500',
            'notifications.*.tag' => 'nullable|string|max:255',
            'notifications.*.category' => 'nullable|string|max:255',
            'notifications.*.extras' => 'nullable|array',
            'notifications.*.posted_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $ids = [];
        foreach ($request->notifications as $notif) {
            $record = CapturedNotification::create([
                'device_id' => $device->device_id,
                'device_name' => $device->device_name,
                'package_name' => $notif['package_name'] ?? null,
                'app_name' => $notif['app_name'] ?? null,
                'title' => $notif['title'] ?? null,
                'text' => $notif['text'] ?? null,
                'big_text' => $notif['big_text'] ?? null,
                'ticker' => $notif['ticker'] ?? null,
                'tag' => $notif['tag'] ?? null,
                'category' => $notif['category'] ?? null,
                'extras' => $notif['extras'] ?? null,
                'posted_at' => $notif['posted_at'] ?? now(),
            ]);
            $ids[] = $record->id;
        }

        $device->update(['last_seen_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => count($ids) . ' notifications captured',
            'data' => ['ids' => $ids],
        ], 201);
    }

    /**
     * Heartbeat / ping from device.
     * POST /api/device/ping
     */
    public function ping(Request $request)
    {
        $device = $this->authenticateDevice($request);
        if (!$device) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }

        $device->update(['last_seen_at' => now()]);

        return response()->json([
            'success' => true,
            'message' => 'pong',
            'server_time' => now()->toIso8601String(),
        ]);
    }

    /**
     * Authenticate device by Bearer token.
     */
    private function authenticateDevice(Request $request): ?DeviceToken
    {
        $token = $request->bearerToken();
        if (!$token) {
            return null;
        }

        return DeviceToken::where('api_token', $token)->where('is_active', true)->first();
    }
}
