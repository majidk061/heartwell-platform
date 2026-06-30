<?php

namespace App\Listeners;

use App\Domains\Automation\Actions\EvaluateAutomationRulesAction;
use App\Domains\CRM\Actions\ScheduleLeadFollowUpAction;
use App\Domains\CRM\Events\LeadStatusChanged;
use Illuminate\Support\Facades\Log;

class HandleLeadStatusChanged
{
    public function __construct(
        private readonly EvaluateAutomationRulesAction $evaluateAutomationRules,
        private readonly ScheduleLeadFollowUpAction $scheduleLeadFollowUp,
    ) {}

    public function handle(LeadStatusChanged $event): void
    {
        $lead = $event->lead;

        try {
            $this->scheduleLeadFollowUp->execute($lead, $event->toStatus);

            $this->evaluateAutomationRules->execute('lead_status_changed', [
                'lead_id' => $lead->id,
                'email' => $lead->email,
                'first_name' => $lead->first_name,
                'last_name' => $lead->last_name,
                'from_status' => $event->fromStatus?->value,
                'to_status' => $event->toStatus->value,
                'status' => $event->toStatus->value,
                'avatar_type' => $lead->avatar_type?->value,
                'source' => $lead->source->value,
                'clinical_intake_url' => url('/clinical-intake'),
                'my_visit_url' => url('/my-visit'),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Lead status automation skipped', [
                'lead_id' => $lead->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
