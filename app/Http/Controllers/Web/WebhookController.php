<?php

namespace App\Http\Controllers\Web;

use App\Domains\Integrations\Contracts\AcuityServiceInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function acuity(Request $request, AcuityServiceInterface $acuity): JsonResponse
    {
        if (! $acuity->handleWebhook($request->all(), $request)) {
            return response()->json(['ok' => false, 'message' => 'Invalid webhook secret.'], 403);
        }

        return response()->json(['ok' => true]);
    }
}
