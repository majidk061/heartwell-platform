<?php

namespace App\Domains\CRM\Enums;

enum ClinicalClearanceStatus: string
{
    case Pending = 'pending';
    case Cleared = 'cleared';
    case Expired = 'expired';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending intake',
            self::Cleared => 'Cleared',
            self::Expired => 'Renewal required',
        };
    }
}
