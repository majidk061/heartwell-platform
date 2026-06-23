<?php

namespace App\Domains\CRM\Events;

use App\Domains\CRM\Models\GroupInquiry;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GroupInquirySubmitted
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public readonly GroupInquiry $groupInquiry,
    ) {}
}
