<?php

namespace App\Domains\Booking\Actions;

use App\Domains\Booking\Models\Booking;
use App\Domains\Booking\Models\BookingEvent;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\Lead;
use App\Domains\Integrations\Contracts\AcuityServiceInterface;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class SyncBookingFromAcuityWebhookAction
{
    public function __construct(
        private readonly AcuityServiceInterface $acuity,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed> Normalized payload for BookingSynced listeners
     */
    public function execute(array $payload): array
    {
        $details = $this->resolveAppointmentDetails($payload);

        return DB::transaction(function () use ($details, $payload) {
            $email = (string) ($details['email'] ?? '');
            $firstName = (string) ($details['firstName'] ?? $details['first_name'] ?? '');
            $lastName = (string) ($details['lastName'] ?? $details['last_name'] ?? '');
            $externalId = (string) ($details['id'] ?? $payload['id'] ?? '');

            $lead = null;
            if ($email !== '') {
                $lead = Lead::query()->firstOrCreate(
                    ['email' => $email],
                    [
                        'first_name' => $firstName !== '' ? $firstName : 'Guest',
                        'last_name' => $lastName !== '' ? $lastName : null,
                        'source' => LeadSource::Acuity,
                        'status' => LeadStatus::Booked,
                    ],
                );

                if ($lead->status !== LeadStatus::Booked) {
                    $lead->update(['status' => LeadStatus::Booked]);
                }
            }

            $scheduledAt = $this->parseScheduledAt($details);

            $booking = $externalId !== ''
                ? Booking::query()->updateOrCreate(
                    ['external_acuity_id' => $externalId],
                    [
                        'lead_id' => $lead?->id,
                        'type' => 'individual',
                        'status' => ($payload['action'] ?? 'scheduled') === 'canceled' ? 'canceled' : 'confirmed',
                        'scheduled_at' => $scheduledAt,
                        'metadata' => [
                            'acuity_action' => $payload['action'] ?? null,
                            'appointment_type_id' => $details['appointmentTypeID'] ?? null,
                            'calendar_id' => $details['calendarID'] ?? null,
                        ],
                    ],
                )
                : Booking::query()->create([
                    'lead_id' => $lead?->id,
                    'type' => 'individual',
                    'external_acuity_id' => null,
                    'status' => ($payload['action'] ?? 'scheduled') === 'canceled' ? 'canceled' : 'confirmed',
                    'scheduled_at' => $scheduledAt,
                    'metadata' => ['acuity_action' => $payload['action'] ?? null],
                ]);

            BookingEvent::query()->create([
                'booking_id' => $booking->id,
                'event_type' => 'acuity.webhook.'.($payload['action'] ?? 'unknown'),
                'payload' => $payload,
                'status' => 'processed',
            ]);

            return [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'booking_date' => $scheduledAt?->toDayDateTimeString() ?? '',
                'datetime' => $scheduledAt?->toIso8601String() ?? '',
                'external_acuity_id' => $externalId,
                'booking_id' => $booking->id,
                'lead_id' => $lead?->id,
            ];
        });
    }

    /**
     * @param  array<string, mixed>  $payload
     * @return array<string, mixed>
     */
    private function resolveAppointmentDetails(array $payload): array
    {
        $appointmentId = (string) ($payload['id'] ?? $payload['appointmentID'] ?? '');

        if ($appointmentId !== '' && $this->acuity->isConfigured()) {
            $fetched = $this->acuity->getAppointment($appointmentId);
            if (is_array($fetched)) {
                return array_merge($payload, $fetched);
            }
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $details
     */
    private function parseScheduledAt(array $details): ?Carbon
    {
        $raw = $details['datetime'] ?? $details['date'] ?? $details['scheduled_at'] ?? null;

        if (! is_string($raw) || $raw === '') {
            return null;
        }

        try {
            return Carbon::parse($raw);
        } catch (\Throwable) {
            return null;
        }
    }
}
