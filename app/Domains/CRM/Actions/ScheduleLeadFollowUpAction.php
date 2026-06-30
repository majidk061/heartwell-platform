<?php

namespace App\Domains\CRM\Actions;

use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\Lead;

class ScheduleLeadFollowUpAction
{
    public function execute(Lead $lead, LeadStatus $toStatus): Lead
    {
        $followUpAt = match ($toStatus) {
            LeadStatus::Contacted => now()->addDays(3),
            LeadStatus::ConsultationScheduled => now()->addDay(),
            LeadStatus::Booked => now()->addDays(2),
            LeadStatus::FollowUp => now()->addDays(2),
            LeadStatus::Completed => now()->addWeeks(2),
            default => null,
        };

        if ($followUpAt !== null) {
            $lead->update(['next_follow_up_at' => $followUpAt]);
        }

        return $lead->fresh();
    }
}
