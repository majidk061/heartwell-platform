<?php

namespace App\Listeners;

use App\Domains\Automation\Actions\EvaluateAutomationRulesAction;
use App\Domains\Booking\Actions\AlertBookingPendingClearanceAction;
use App\Domains\Booking\Actions\ScheduleBookingCommunicationsAction;
use App\Domains\Booking\Events\BookingSynced;
use App\Domains\Booking\Models\Booking;
use App\Domains\CRM\Models\Lead;
use App\Domains\Integrations\Actions\NotifyAdminsAction;

class HandleBookingSynced
{
    public function __construct(
        private readonly EvaluateAutomationRulesAction $evaluateAutomationRules,
        private readonly NotifyAdminsAction $notifyAdmins,
        private readonly ScheduleBookingCommunicationsAction $scheduleBookingCommunications,
        private readonly AlertBookingPendingClearanceAction $alertBookingPendingClearance,
    ) {}

    public function handle(BookingSynced $event): void
    {
        $payload = $event->payload;
        $email = $payload['email'] ?? null;

        $mergeData = [
            'first_name' => $payload['firstName'] ?? $payload['first_name'] ?? '',
            'last_name' => $payload['lastName'] ?? $payload['last_name'] ?? '',
            'email' => $email ?? '',
            'booking_date' => $payload['booking_date'] ?? $payload['datetime'] ?? $payload['date'] ?? '',
            'clinical_intake_url' => url('/clinical-intake'),
            'my_visit_url' => url('/my-visit'),
            'lead_id' => $payload['lead_id'] ?? null,
            'booking_id' => $payload['booking_id'] ?? null,
        ];

        $this->evaluateAutomationRules->execute('booking_synced', $mergeData);
        $this->notifyAdmins->execute('booking', 'booking_admin_notify', $mergeData);

        $booking = isset($payload['booking_id'])
            ? Booking::query()->find($payload['booking_id'])
            : null;
        $lead = $booking?->lead ?? (isset($payload['lead_id']) ? Lead::query()->find($payload['lead_id']) : null);

        if ($booking && $lead) {
            $this->alertBookingPendingClearance->execute($booking, $lead, $mergeData);
            $this->scheduleBookingCommunications->execute($booking, $lead, $mergeData);
        }
    }
}
