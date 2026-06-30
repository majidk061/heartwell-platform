<?php

namespace App\Listeners;

use App\Domains\Automation\Actions\EvaluateAutomationRulesAction;
use App\Domains\CRM\Events\ConsultationRequested;
use App\Domains\Integrations\Actions\NotifyAdminsAction;

class HandleConsultationRequested
{
    public function __construct(
        private readonly EvaluateAutomationRulesAction $evaluateAutomationRules,
        private readonly NotifyAdminsAction $notifyAdmins,
    ) {}

    public function handle(ConsultationRequested $event): void
    {
        $request = $event->consultationRequest->loadMissing('lead');
        $lead = $request->lead;

        $context = [
            'lead_id' => $lead?->id,
            'email' => $request->email,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'message' => $request->message,
            'preferred_contact_method' => $request->preferred_contact_method,
            'avatar_type' => $request->avatar_type?->value,
            'source_page' => $request->source_page,
        ];

        $this->evaluateAutomationRules->execute('consultation_requested', $context);
        $this->notifyAdmins->execute('consultation', 'consultation_admin_notify', $context);
    }
}
