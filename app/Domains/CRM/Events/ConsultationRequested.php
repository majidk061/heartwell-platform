<?php

namespace App\Domains\CRM\Events;

use App\Domains\CRM\Models\ConsultationRequest;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConsultationRequested
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly ConsultationRequest $consultationRequest,
    ) {}
}
