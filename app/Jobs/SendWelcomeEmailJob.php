<?php

namespace App\Jobs;

use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use App\Domains\Integrations\Contracts\SendGridServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWelcomeEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $dynamicData
     */
    public function __construct(
        public readonly string $email,
        public readonly array $dynamicData = [],
    ) {}

    public function handle(SendTemplatedEmailAction $sendTemplatedEmail, SendGridServiceInterface $sendGrid): void
    {
        if ($sendTemplatedEmail->execute('waitlist_welcome', $this->email, $this->dynamicData)) {
            return;
        }

        $templateId = config('integrations.sendgrid.templates.waitlist_welcome');

        if (filled($templateId)) {
            $sendGrid->sendTemplate($templateId, $this->email, $this->dynamicData);
        }
    }
}
