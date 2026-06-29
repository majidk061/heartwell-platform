<?php

namespace App\Providers;

use App\Domains\Booking\Events\BookingSynced;
use App\Domains\CRM\Events\ConsultationRequested;
use App\Domains\CRM\Events\GroupInquirySubmitted;
use App\Domains\CRM\Events\LeadCreated;
use App\Domains\CRM\Events\LeadStatusChanged;
use App\Domains\CRM\Events\WaitlistJoined;
use App\Listeners\HandleBookingSynced;
use App\Listeners\HandleConsultationRequested;
use App\Listeners\HandleGroupInquirySubmitted;
use App\Listeners\HandleLeadCreated;
use App\Listeners\HandleLeadStatusChanged;
use App\Listeners\HandleWaitlistJoined;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        WaitlistJoined::class => [
            HandleWaitlistJoined::class,
        ],
        ConsultationRequested::class => [
            HandleConsultationRequested::class,
        ],
        GroupInquirySubmitted::class => [
            HandleGroupInquirySubmitted::class,
        ],
        LeadStatusChanged::class => [
            HandleLeadStatusChanged::class,
        ],
        LeadCreated::class => [
            HandleLeadCreated::class,
        ],
        BookingSynced::class => [
            HandleBookingSynced::class,
        ],
    ];

    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
