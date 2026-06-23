<?php

namespace App\Domains\CRM\Events;

use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\Lead;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeadStatusChanged
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly Lead $lead,
        public readonly ?LeadStatus $fromStatus,
        public readonly LeadStatus $toStatus,
    ) {}
}
