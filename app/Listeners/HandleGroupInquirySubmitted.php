<?php

namespace App\Listeners;

use App\Domains\Automation\Actions\EvaluateAutomationRulesAction;
use App\Domains\CRM\Events\GroupInquirySubmitted;
use App\Domains\Integrations\Actions\NotifyAdminsAction;

class HandleGroupInquirySubmitted
{
    public function __construct(
        private readonly EvaluateAutomationRulesAction $evaluateAutomationRules,
        private readonly NotifyAdminsAction $notifyAdmins,
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
        $this->notifyAdmins->execute('group_inquiry', 'group_inquiry_admin_notify', $context);
    }
}
