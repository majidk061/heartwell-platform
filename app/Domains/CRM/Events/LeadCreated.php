<?php

namespace App\Domains\CRM\Events;

use App\Domains\CRM\Models\Lead;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadCreated
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Lead $lead,
    ) {}
}
