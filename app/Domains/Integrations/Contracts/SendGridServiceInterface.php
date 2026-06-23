<?php

namespace App\Domains\Integrations\Contracts;

interface SendGridServiceInterface
{
    /**
     * @param  array<string, mixed>  $dynamicData
     */
    public function sendTemplate(string $templateId, string $toEmail, array $dynamicData = []): bool;

    public function sendAdminAlert(string $subject, string $body): bool;

    public function isConfigured(): bool;
}
