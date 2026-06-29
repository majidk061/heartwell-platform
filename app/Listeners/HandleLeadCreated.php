<?php

namespace App\Listeners;

use App\Domains\CRM\Events\LeadCreated;
use App\Domains\Integrations\Actions\NotifyAdminsAction;

class HandleLeadCreated
{
    public function __construct(
        private readonly NotifyAdminsAction $notifyAdmins,
    ) {}

    public function handle(LeadCreated $event): void
    {
        $lead = $event->lead;

        $this->notifyAdmins->execute('new_lead', 'new_lead_admin_notify', [
            'first_name' => $lead->first_name,
            'last_name' => $lead->last_name,
            'email' => $lead->email,
            'phone' => $lead->phone,
            'source' => $lead->source?->label() ?? $lead->source,
        ]);
    }
}
