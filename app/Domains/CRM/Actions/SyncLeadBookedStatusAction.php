<?php

namespace App\Domains\CRM\Actions;

use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Events\LeadStatusChanged;
use App\Domains\CRM\Models\Lead;

class SyncLeadBookedStatusAction
{
    public function __construct(
        private readonly TransitionLeadStatusAction $transitionLeadStatus,
    ) {}

    public function execute(Lead $lead): Lead
    {
        if ($lead->status === LeadStatus::Booked) {
            return $lead;
        }

        if ($lead->status->canTransitionTo(LeadStatus::Booked)) {
            return $this->transitionLeadStatus->execute($lead, LeadStatus::Booked);
        }

        $fromStatus = $lead->status;
        $lead->update(['status' => LeadStatus::Booked]);
        $lead = $lead->fresh();
        LeadStatusChanged::dispatch($lead, $fromStatus, LeadStatus::Booked);

        return $lead;
    }
}
