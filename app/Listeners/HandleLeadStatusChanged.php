<?php

namespace App\Listeners;

use App\Domains\Automation\Actions\EvaluateAutomationRulesAction;
use App\Domains\CRM\Events\LeadStatusChanged;

class HandleLeadStatusChanged
{
    public function __construct(
        private readonly EvaluateAutomationRulesAction $evaluateAutomationRules,
    ) {}

    public function handle(LeadStatusChanged $event): void
    {
        $lead = $event->lead;

        $this->evaluateAutomationRules->execute('lead_status_changed', [
            'lead_id' => $lead->id,
            'email' => $lead->email,
            'first_name' => $lead->first_name,
            'last_name' => $lead->last_name,
            'from_status' => $event->fromStatus?->value,
            'to_status' => $event->toStatus->value,
            'avatar_type' => $lead->avatar_type?->value,
            'source' => $lead->source->value,
        ]);
    }
}
