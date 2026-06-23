<?php

namespace App\Domains\CRM\Events;

use App\Domains\CRM\Models\WaitlistEntry;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WaitlistJoined
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly WaitlistEntry $waitlistEntry,
    ) {}
}
