<?php

namespace App\Listeners;

use App\Domains\Automation\Actions\EvaluateAutomationRulesAction;
use App\Domains\CRM\Events\WaitlistJoined;
use App\Domains\Integrations\Actions\NotifyAdminsAction;

class HandleWaitlistJoined
{
    public function __construct(
        private readonly EvaluateAutomationRulesAction $evaluateAutomationRules,
        private readonly NotifyAdminsAction $notifyAdmins,
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
        $this->notifyAdmins->execute('waitlist', 'waitlist_admin_notify', $context);
    }
}
