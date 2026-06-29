<?php

namespace App\Listeners;

use App\Domains\Booking\Events\BookingSynced;
use App\Domains\Integrations\Actions\NotifyAdminsAction;
use App\Domains\Integrations\Actions\SendTemplatedEmailAction;

class HandleBookingSynced
{
    public function __construct(
        private readonly NotifyAdminsAction $notifyAdmins,
        private readonly SendTemplatedEmailAction $sendTemplatedEmail,
    ) {}

    public function handle(BookingSynced $event): void
    {
        $payload = $event->payload;
        $email = $payload['email'] ?? null;

        $mergeData = [
            'first_name' => $payload['firstName'] ?? $payload['first_name'] ?? '',
            'last_name' => $payload['lastName'] ?? $payload['last_name'] ?? '',
            'email' => $email ?? '',
            'booking_date' => $payload['datetime'] ?? $payload['date'] ?? '',
        ];

        $this->notifyAdmins->execute('booking', 'booking_admin_notify', $mergeData);

        if ($email) {
            $this->sendTemplatedEmail->execute('booking_confirmation', $email, $mergeData);
        }
    }
}
