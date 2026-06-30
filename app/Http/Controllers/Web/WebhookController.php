<?php

namespace App\Http\Controllers\Web;

use App\Domains\Integrations\Actions\SyncClinicalStatusFromHydreightWebhookAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function acuity(Request $request, \App\Domains\Integrations\Contracts\AcuityServiceInterface $acuity): JsonResponse
    {
        if (! $acuity->handleWebhook($request->all(), $request)) {
            return response()->json(['ok' => false, 'message' => 'Invalid webhook secret.'], 403);
        }

        return response()->json(['ok' => true]);
    }

    public function hydreight(Request $request, SyncClinicalStatusFromHydreightWebhookAction $sync): JsonResponse
    {
        $secret = config('integrations.hydreight.webhook_secret');

        if (filled($secret)) {
            $provided = (string) ($request->query('secret') ?? $request->header('X-Hydreight-Webhook-Secret', ''));

            if (! hash_equals((string) $secret, $provided)) {
                return response()->json(['ok' => false, 'message' => 'Invalid webhook secret.'], 403);
            }
        }

        $processed = $sync->execute($request->all());

        return response()->json(['ok' => true, 'processed' => $processed]);
    }
}
