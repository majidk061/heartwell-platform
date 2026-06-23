<?php

namespace App\Domains\CRM\Enums;

enum LeadSource: string
{
    case Website = 'website';
    case Waitlist = 'waitlist';
    case Consultation = 'consultation';
    case GroupInquiry = 'group_inquiry';
    case Acuity = 'acuity';
    case Admin = 'admin';
    case Referral = 'referral';

    public function label(): string
    {
        return match ($this) {
            self::Website => 'Website',
            self::Waitlist => 'Waitlist',
            self::Consultation => 'Consultation Request',
            self::GroupInquiry => 'Group Inquiry',
            self::Acuity => 'Acuity Booking',
            self::Admin => 'Admin',
            self::Referral => 'Referral',
        };
    }
}
