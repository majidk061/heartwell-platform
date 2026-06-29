<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Booking\Events\BookingSynced;
use App\Domains\Integrations\Contracts\AcuityServiceInterface;
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

    public function handleWebhook(array $payload): bool
    {
        if (! $this->isConfigured()) {
            Log::info('[Acuity stub] handleWebhook', ['payload' => $payload]);

            if (($payload['action'] ?? '') === 'scheduled') {
                BookingSynced::dispatch($payload);
            }

            return true;
        }

        Log::info('[Acuity] handleWebhook', ['action' => $payload['action'] ?? 'unknown']);

        if (($payload['action'] ?? '') === 'scheduled') {
            BookingSynced::dispatch($payload);
        }

        return true;
    }

    public function isConfigured(): bool
    {
        $config = config('integrations.acuity');

        return ($config['enabled'] ?? false)
            && filled($config['user_id'])
            && filled($config['api_key']);
    }
}
