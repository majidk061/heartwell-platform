<?php

namespace App\Domains\Integrations\Contracts;

interface SmsServiceInterface
{
    public function send(string $toPhone, string $message): bool;

    public function isConfigured(): bool;
}
