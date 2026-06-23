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
        $acuity->handleWebhook($request->all());

        return response()->json(['ok' => true]);
    }
}
