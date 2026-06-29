<?php

namespace App\Jobs;

use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use App\Domains\Integrations\Contracts\SendGridServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendConsultationAckJob implements ShouldQueue
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
        if ($sendTemplatedEmail->execute('consultation_ack', $this->email, $this->dynamicData)) {
            return;
        }

        $templateId = config('integrations.sendgrid.templates.consultation_ack');

        if (filled($templateId)) {
            $sendGrid->sendTemplate($templateId, $this->email, $this->dynamicData);
        }
    }
}
