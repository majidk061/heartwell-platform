<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Integrations\Contracts\AcuityServiceInterface;
use Illuminate\Support\Facades\Log;

class AcuityService implements AcuityServiceInterface
{
    public function getAppointment(string $appointmentId): ?array
    {
        if (! $this->isConfigured()) {
            Log::info('[Acuity stub] getAppointment', compact('appointmentId'));

            return null;
        }

        Log::info('[Acuity] getAppointment', compact('appointmentId'));

        return null;
    }

    public function handleWebhook(array $payload): bool
    {
        if (! $this->isConfigured()) {
            Log::info('[Acuity stub] handleWebhook', ['payload' => $payload]);

            return true;
        }

        Log::info('[Acuity] handleWebhook', ['action' => $payload['action'] ?? 'unknown']);

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
