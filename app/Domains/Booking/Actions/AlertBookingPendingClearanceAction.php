<?php

namespace App\Domains\Booking\Actions;

use App\Domains\Booking\Models\Booking;
use App\Domains\CRM\Models\Lead;
use App\Domains\Integrations\Actions\NotifyAdminsAction;

class AlertBookingPendingClearanceAction
{
    public function __construct(
        private readonly NotifyAdminsAction $notifyAdmins,
    ) {}

    /**
     * @param  array<string, mixed>  $context
     */
    public function execute(Booking $booking, Lead $lead, array $context): void
    {
        if ($lead->hasValidClinicalClearance()) {
            return;
        }

        $this->notifyAdmins->execute('booking', 'booking_pending_clearance_admin', array_merge($context, [
            'lead_id' => $lead->id,
            'booking_id' => $booking->id,
            'clearance_status' => $lead->clinical_clearance_status?->label() ?? 'Pending intake',
            'clinical_intake_url' => url('/clinical-intake'),
        ]));
    }
}
