<?php

namespace App\Domains\Integrations\Contracts;

interface AcuityServiceInterface
{
    /**
     * @return array<string, mixed>|null
     */
    public function getAppointment(string $appointmentId): ?array;

    /**
     * @param  array<string, mixed>  $payload
     */
    public function handleWebhook(array $payload, ?\Illuminate\Http\Request $request = null): bool;

    public function isConfigured(): bool;
}
