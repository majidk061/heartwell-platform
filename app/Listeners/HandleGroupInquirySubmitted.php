<?php

namespace App\Listeners;

use App\Domains\Automation\Actions\EvaluateAutomationRulesAction;
use App\Domains\CRM\Events\GroupInquirySubmitted;
use App\Domains\Integrations\Contracts\SendGridServiceInterface;

class HandleGroupInquirySubmitted
{
    public function __construct(
        private readonly EvaluateAutomationRulesAction $evaluateAutomationRules,
        private readonly SendGridServiceInterface $sendGrid,
    ) {}

    public function handle(GroupInquirySubmitted $event): void
    {
        $inquiry = $event->groupInquiry->loadMissing('lead');
        $lead = $inquiry->lead;

        $context = [
            'lead_id' => $lead?->id,
            'email' => $inquiry->host_email,
            'host_name' => $inquiry->host_name,
            'event_name' => $inquiry->event_name,
            'event_date' => $inquiry->event_date?->toDateString(),
            'guest_count' => $inquiry->guest_count,
            'message' => $inquiry->message,
        ];

        $this->evaluateAutomationRules->execute('group_inquiry_submitted', $context);

        $this->sendGrid->sendAdminAlert(
            'New group inquiry: '.($inquiry->event_name ?? 'Unnamed event'),
            sprintf(
                "Host: %s (%s)\nGuests: %s\nDate: %s\n\n%s",
                $inquiry->host_name,
                $inquiry->host_email,
                $inquiry->guest_count ?? 'N/A',
                $inquiry->event_date?->toDateString() ?? 'TBD',
                $inquiry->message ?? '',
            ),
        );
    }
}
