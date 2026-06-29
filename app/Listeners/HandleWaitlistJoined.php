<?php

namespace App\Listeners;

use App\Domains\Automation\Actions\EvaluateAutomationRulesAction;
use App\Domains\CRM\Events\WaitlistJoined;
use App\Domains\Integrations\Actions\NotifyAdminsAction;
use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use App\Jobs\SubscribeToMailchimpJob;
use App\Domains\Integrations\Contracts\SendGridServiceInterface;

class HandleWaitlistJoined
{
    public function __construct(
        private readonly EvaluateAutomationRulesAction $evaluateAutomationRules,
        private readonly SendTemplatedEmailAction $sendTemplatedEmail,
        private readonly NotifyAdminsAction $notifyAdmins,
        private readonly SendGridServiceInterface $sendGrid,
    ) {}

    public function handle(WaitlistJoined $event): void
    {
        $entry = $event->waitlistEntry->loadMissing('lead');
        $lead = $entry->lead;

        $context = [
            'lead_id' => $lead?->id,
            'email' => $entry->email,
            'first_name' => $entry->first_name,
            'last_name' => $entry->last_name,
            'phone' => $entry->phone,
            'avatar_type' => $entry->avatar_type?->value,
            'source_page' => $entry->source_page,
            'tags' => config('integrations.mailchimp.default_tags', []),
        ];

        $this->evaluateAutomationRules->execute('waitlist_joined', $context);

        if ($lead) {
            SubscribeToMailchimpJob::dispatch($lead->id, $context['tags']);
        }

        $sent = $this->sendTemplatedEmail->execute('waitlist_welcome', $entry->email, $context);

        if (! $sent) {
            $templateId = config('integrations.sendgrid.templates.waitlist_welcome');
            if (filled($templateId)) {
                $this->sendGrid->sendTemplate($templateId, $entry->email, $context);
            }
        }

        $this->notifyAdmins->execute('waitlist', 'waitlist_admin_notify', $context);
    }
}
