<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Integrations\Contracts\SendGridServiceInterface;
use Illuminate\Support\Facades\Log;

class SendGridService implements SendGridServiceInterface
{
    public function sendTemplate(string $templateId, string $toEmail, array $dynamicData = []): bool
    {
        if (! $this->isConfigured()) {
            Log::info('[SendGrid stub] sendTemplate', compact('templateId', 'toEmail', 'dynamicData'));

            return true;
        }

        Log::info('[SendGrid] sendTemplate', compact('templateId', 'toEmail'));

        return true;
    }

    public function sendAdminAlert(string $subject, string $body): bool
    {
        $adminEmail = config('integrations.sendgrid.admin_alert_email');

        if (! $this->isConfigured() || blank($adminEmail)) {
            Log::info('[SendGrid stub] sendAdminAlert', compact('subject', 'body'));

            return true;
        }

        Log::info('[SendGrid] sendAdminAlert', compact('subject', 'adminEmail'));

        return true;
    }

    public function isConfigured(): bool
    {
        $config = config('integrations.sendgrid');

        return ($config['enabled'] ?? false) && filled($config['api_key']);
    }
}
