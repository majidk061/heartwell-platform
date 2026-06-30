<?php

namespace App\Domains\Integrations\Actions;

use App\Domains\Integrations\Models\EmailTemplate;
use App\Domains\Integrations\Services\SettingsResolver;
use Illuminate\Support\Facades\Mail;

class SendTestEmailsAction
{
    public function __construct(
        private readonly SettingsResolver $settingsResolver,
        private readonly SendTemplatedEmailAction $sendTemplatedEmail,
        private readonly GetTestRecipientEmailAction $getTestRecipientEmail,
    ) {}

    public function sendConnectivityTest(?string $recipient = null): string
    {
        $email = $this->resolveRecipient($recipient);

        $this->settingsResolver->mergeIntoConfig();

        Mail::raw('HeartWell test email — your SMTP settings are working.', function ($message) use ($email): void {
            $message->to($email)->subject('HeartWell SMTP Test');
        });

        return $email;
    }

    /**
     * @return array{email: string, sent: list<string>, skipped: list<string>}
     */
    public function sendAllTemplateTests(?string $recipient = null): array
    {
        $email = $this->resolveRecipient($recipient);

        $this->settingsResolver->mergeIntoConfig();

        $this->sendConnectivityTest($email);

        $sampleData = $this->sampleMergeData($email);
        $sent = [];
        $skipped = [];

        $templates = EmailTemplate::query()->where('is_enabled', true)->orderBy('key')->get();

        foreach ($templates as $template) {
            if ($this->sendTemplatedEmail->execute($template->key, $email, $sampleData)) {
                $sent[] = $template->key;
            } else {
                $skipped[] = $template->key;
            }
        }

        return [
            'email' => $email,
            'sent' => $sent,
            'skipped' => $skipped,
        ];
    }

    private function resolveRecipient(?string $recipient): string
    {
        $email = $this->getTestRecipientEmail->execute($recipient);

        if (! filled($email)) {
            throw new \InvalidArgumentException('No test email recipient configured. Set one on Email / SMTP settings.');
        }

        return $email;
    }

    /**
     * @return array<string, mixed>
     */
    private function sampleMergeData(string $email): array
    {
        return [
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => $email,
            'phone' => '555-0100',
            'message' => 'This is a test message from HeartWell.',
            'event_name' => 'Wellness Gathering',
            'event_date' => now()->addWeek()->toDateString(),
            'guest_count' => '8',
            'booking_date' => now()->addWeek()->toDateString(),
            'host_name' => 'Test Host',
            'name' => 'Test Admin',
            'source' => 'website',
            'reset_url' => url('/admin/password-reset/reset?token=test-token&email='.urlencode($email)),
        ];
    }
}
