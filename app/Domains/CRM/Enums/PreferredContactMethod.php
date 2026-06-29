<?php

namespace App\Domains\CRM\Enums;

enum PreferredContactMethod: string
{
    case Email = 'email';
    case Phone = 'phone';
    case Either = 'either';

    public function label(): string
    {
        return match ($this) {
            self::Email => 'Email',
            self::Phone => 'Phone',
            self::Either => 'Either',
        };
    }
}
