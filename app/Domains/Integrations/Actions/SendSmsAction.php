<?php

namespace App\Domains\Integrations\Actions;

use App\Domains\Integrations\Contracts\SmsServiceInterface;

class SendSmsAction
{
    public function __construct(
        private readonly SmsServiceInterface $sms,
    ) {}

    public function execute(string $toPhone, string $message): bool
    {
        if (blank($toPhone)) {
            return false;
        }

        return $this->sms->send($toPhone, $message);
    }

    /**
     * @param  array<string, mixed>  $mergeData
     */
    public function executeTemplate(string $templateKey, string $toPhone, array $mergeData = []): bool
    {
        $messages = [
            'appointment_reminder_sms' => 'Hi {{first_name}}, this is HeartWell. Reminder: your wellness visit is {{booking_date}}. Complete clinical intake if you have not yet: {{clinical_intake_url}}',
        ];

        $body = $messages[$templateKey] ?? null;

        if ($body === null) {
            return false;
        }

        foreach ($mergeData as $key => $value) {
            if (is_scalar($value) || $value === null) {
                $body = str_replace('{{'.$key.'}}', (string) ($value ?? ''), $body);
            }
        }

        return $this->execute($toPhone, $body);
    }
}
