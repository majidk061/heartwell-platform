<?php

namespace App\Jobs;

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

    public function handle(SendGridServiceInterface $sendGrid): void
    {
        $templateId = config('integrations.sendgrid.templates.waitlist_welcome');

        if (blank($templateId)) {
            return;
        }

        $sendGrid->sendTemplate($templateId, $this->email, $this->dynamicData);
    }
}
