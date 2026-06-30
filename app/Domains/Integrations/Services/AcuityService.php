<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Booking\Actions\SyncBookingFromAcuityWebhookAction;
use App\Domains\Booking\Events\BookingSynced;
use App\Domains\Integrations\Contracts\AcuityServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AcuityService implements AcuityServiceInterface
{
    public function getAppointment(string $appointmentId): ?array
    {
        if (! $this->isConfigured()) {
            Log::info('[Acuity stub] getAppointment', compact('appointmentId'));

            return null;
        }

        try {
            $response = Http::withBasicAuth(
                config('integrations.acuity.user_id'),
                config('integrations.acuity.api_key'),
            )->get(config('integrations.acuity.api_base_url').'/appointments/'.$appointmentId);

            return $response->successful() ? $response->json() : null;
        } catch (\Throwable $e) {
            Log::error('[Acuity] getAppointment failed', ['id' => $appointmentId, 'error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleWebhook(array $payload, ?Request $request = null): bool
    {
        if ($request !== null && ! $this->validateWebhookSecret($request)) {
            return false;
        }

        $action = (string) ($payload['action'] ?? '');

        if (! in_array($action, ['scheduled', 'rescheduled', 'canceled'], true)) {
            Log::info('[Acuity] webhook ignored', ['action' => $action]);

            return true;
        }

        $normalized = app(SyncBookingFromAcuityWebhookAction::class)->execute($payload);

        if ($action !== 'canceled') {
            BookingSynced::dispatch($normalized);
        }

        return true;
    }

    public function validateWebhookSecret(Request $request): bool
    {
        $secret = config('integrations.acuity.webhook_secret');

        if (blank($secret)) {
            return true;
        }

        $provided = (string) ($request->query('secret') ?? $request->header('X-Acuity-Webhook-Secret', ''));

        return hash_equals((string) $secret, $provided);
    }

    public function isConfigured(): bool
    {
        $config = config('integrations.acuity');

        return ($config['enabled'] ?? false)
            && filled($config['user_id'])
            && filled($config['api_key']);
    }

    public function hasEmbed(): bool
    {
        return filled(config('integrations.acuity.embed_url'));
    }
}
