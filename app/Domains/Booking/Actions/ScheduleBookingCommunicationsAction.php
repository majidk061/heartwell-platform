<?php

namespace App\Domains\Booking\Actions;

use App\Domains\Automation\Models\AutomationLog;
use App\Domains\Booking\Models\Booking;
use App\Domains\CRM\Models\Lead;
use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use Illuminate\Support\Carbon;

class ScheduleBookingCommunicationsAction
{
    public function __construct(
        private readonly SendTemplatedEmailAction $sendTemplatedEmail,
    ) {}

    /**
     * @param  array<string, mixed>  $context
     */
    public function execute(Booking $booking, Lead $lead, array $context): void
    {
        $mergeData = array_merge($context, [
            'lead_id' => $lead->id,
            'booking_id' => $booking->id,
            'first_name' => $context['first_name'] ?? $lead->first_name,
            'last_name' => $context['last_name'] ?? $lead->last_name,
            'email' => $context['email'] ?? $lead->email,
            'booking_date' => $context['booking_date'] ?? $booking->scheduled_at?->toDayDateTimeString() ?? '',
            'clinical_intake_url' => url('/clinical-intake'),
        ]);

        if (! $lead->hasValidClinicalClearance() && filled($mergeData['email'])) {
            $this->sendTemplatedEmail->execute('clinical_intake_reminder', (string) $mergeData['email'], $mergeData);
        }

        if ($booking->scheduled_at) {
            $this->scheduleTemplateEmail(
                'appointment_reminder',
                $booking->scheduled_at->copy()->subDay(),
                $mergeData,
                $lead->id,
            );

            $this->scheduleTemplateEmail(
                'post_visit_followup',
                $booking->scheduled_at->copy()->addHours(4),
                $mergeData,
                $lead->id,
            );

            $this->scheduleSmsReminder($booking, $mergeData, $lead);
        }
    }

    /**
     * @param  array<string, mixed>  $mergeData
     */
    private function scheduleTemplateEmail(string $templateKey, Carbon $runAt, array $mergeData, int $leadId): void
    {
        if ($runAt->isPast()) {
            return;
        }

        if (filled($mergeData['email'])) {
            AutomationLog::query()->create([
                'automation_rule_id' => null,
                'lead_id' => $leadId,
                'status' => 'scheduled',
                'channel' => 'email',
                'payload' => array_merge($mergeData, [
                    'template_key' => $templateKey,
                    'email' => $mergeData['email'],
                ]),
                'executed_at' => $runAt,
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $mergeData
     */
    private function scheduleSmsReminder(Booking $booking, array $mergeData, Lead $lead): void
    {
        $phone = $lead->phone;

        if (blank($phone) || ! $booking->scheduled_at) {
            return;
        }

        $runAt = $booking->scheduled_at->copy()->subDay();

        if ($runAt->isPast()) {
            return;
        }

        AutomationLog::query()->create([
            'automation_rule_id' => null,
            'lead_id' => $lead->id,
            'status' => 'scheduled',
            'channel' => 'sms',
            'payload' => [
                'phone' => $phone,
                'first_name' => $mergeData['first_name'] ?? $lead->first_name,
                'booking_date' => $mergeData['booking_date'] ?? '',
                'clinical_intake_url' => $mergeData['clinical_intake_url'] ?? url('/clinical-intake'),
                'template_key' => 'appointment_reminder_sms',
            ],
            'executed_at' => $runAt,
        ]);
    }
}
