<?php

namespace App\Domains\Integrations\Services;

use App\Domains\Integrations\Contracts\SendGridServiceInterface;
use Illuminate\Support\Facades\Log;
use SendGrid;
use SendGrid\Mail\Mail;

class SendGridService implements SendGridServiceInterface
{
    public function sendTemplate(string $templateId, string $toEmail, array $dynamicData = []): bool
    {
        if (! $this->isConfigured()) {
            Log::info('[SendGrid stub] sendTemplate', compact('templateId', 'toEmail', 'dynamicData'));

            return true;
        }

        try {
            $mail = new Mail;
            $mail->setFrom(
                config('integrations.sendgrid.from_email'),
                config('integrations.sendgrid.from_name'),
            );
            $mail->addTo($toEmail);
            $mail->setTemplateId($templateId);
            foreach ($dynamicData as $key => $value) {
                $mail->addDynamicTemplateData($key, $value);
            }

            $response = $this->client()->send($mail);

            return $response->statusCode() >= 200 && $response->statusCode() < 300;
        } catch (\Throwable $e) {
            Log::error('[SendGrid] sendTemplate failed', ['to' => $toEmail, 'error' => $e->getMessage()]);

            return false;
        }
    }

    public function sendAdminAlert(string $subject, string $body): bool
    {
        $adminEmail = config('integrations.sendgrid.admin_alert_email');

        if (! $this->isConfigured() || blank($adminEmail)) {
            Log::info('[SendGrid stub] sendAdminAlert', compact('subject', 'body'));

            return true;
        }

        try {
            $mail = new Mail;
            $mail->setFrom(
                config('integrations.sendgrid.from_email'),
                config('integrations.sendgrid.from_name'),
            );
            $mail->addTo($adminEmail);
            $mail->setSubject($subject);
            $mail->addContent('text/plain', $body);

            $response = $this->client()->send($mail);

            return $response->statusCode() >= 200 && $response->statusCode() < 300;
        } catch (\Throwable $e) {
            Log::error('[SendGrid] sendAdminAlert failed', ['error' => $e->getMessage()]);

            return false;
        }
    }

    public function isConfigured(): bool
    {
        $config = config('integrations.sendgrid');

        return ($config['enabled'] ?? false) && filled($config['api_key']);
    }

    private function client(): SendGrid
    {
        return new SendGrid(config('integrations.sendgrid.api_key'));
    }
}
