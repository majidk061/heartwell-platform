<?php

namespace App\Domains\CRM\Rules;

use App\Domains\CRM\Enums\LeadStatus;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidLeadStatusTransition implements ValidationRule
{
    public function __construct(
        private readonly LeadStatus $currentStatus,
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $targetStatus = LeadStatus::tryFrom((string) $value);

        if ($targetStatus === null) {
            $fail('The selected lead status is invalid.');

            return;
        }

        if (! $this->currentStatus->canTransitionTo($targetStatus)) {
            $fail(sprintf(
                'Cannot transition from %s to %s.',
                $this->currentStatus->label(),
                $targetStatus->label(),
            ));
        }
    }
}
