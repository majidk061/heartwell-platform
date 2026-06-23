<?php

namespace App\Domains\CRM\Enums;

enum LeadStatus: string
{
    case NewLead = 'new_lead';
    case Contacted = 'contacted';
    case ConsultationScheduled = 'consultation_scheduled';
    case Booked = 'booked';
    case Completed = 'completed';
    case FollowUp = 'follow_up';

    public function label(): string
    {
        return match ($this) {
            self::NewLead => 'New Lead',
            self::Contacted => 'Contacted',
            self::ConsultationScheduled => 'Consultation Scheduled',
            self::Booked => 'Booked',
            self::Completed => 'Completed',
            self::FollowUp => 'Follow Up',
        };
    }

    /**
     * @return array<self>
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::NewLead => [self::Contacted],
            self::Contacted => [self::ConsultationScheduled, self::FollowUp],
            self::ConsultationScheduled => [self::Booked, self::FollowUp],
            self::Booked => [self::Completed, self::FollowUp],
            self::Completed => [self::FollowUp],
            self::FollowUp => [self::Contacted, self::ConsultationScheduled, self::Booked],
        };
    }

    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->allowedTransitions(), true);
    }
}
