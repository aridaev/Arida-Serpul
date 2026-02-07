<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Transaksi;
use App\Services\DigiflazzService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DigiflazzWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $json = $request->getContent();
        $payload = json_decode($json, true);
        $data = $payload['data'] ?? $payload;

        // Verify webhook secret if configured
        $secret = Setting::get('digiflazz_webhook_secret', '');
        if (!empty($secret)) {
            $headerSecret = $request->header('X-Hub-Signature', '');
            if (!empty($headerSecret)) {
                $expected = 'sha1=' . hash_hmac('sha1', $json, $secret);
                if (!hash_equals($expected, $headerSecret)) {
                    Log::warning('DigiFlazz webhook: invalid signature');
                    return response()->json(['status' => 'invalid signature'], 403);
                }
            }
        }

        $refId = $data['ref_id'] ?? null;
        if (!$refId) {
            return response()->json(['status' => 'missing ref_id'], 400);
        }

        $transaksi = Transaksi::where('ref_id', $refId)->first();
        if (!$transaksi) {
            Log::warning("DigiFlazz webhook: transaksi not found for ref_id={$refId}");
            return response()->json(['status' => 'not found'], 404);
        }

        $digiflazz = app(DigiflazzService::class);
        $status = $digiflazz->parseStatus($data);

        if ($status === 'success') {
            $transaksi->update([
                'status' => 'success',
                'sn' => $data['sn'] ?? '',
                'response_provider' => $json,
            ]);
            Log::info("DigiFlazz webhook SUCCESS: ref_id={$refId}, sn={$data['sn'] ?? ''}");
        } elseif ($status === 'failed') {
            $transaksi->update([
                'status' => 'failed',
                'response_provider' => $json,
            ]);
            Log::info("DigiFlazz webhook FAILED: ref_id={$refId}, rc={$data['rc'] ?? ''}");
        } else {
            $transaksi->update([
                'response_provider' => $json,
            ]);
            Log::info("DigiFlazz webhook PENDING: ref_id={$refId}");
        }

        return response()->json(['status' => 'ok']);
    }
}
