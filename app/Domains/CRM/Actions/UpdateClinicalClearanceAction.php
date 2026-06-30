<?php

namespace App\Domains\CRM\Actions;

use App\Domains\CRM\Enums\ClinicalClearanceStatus;
use App\Domains\CRM\Models\Lead;
use Illuminate\Support\Carbon;

class UpdateClinicalClearanceAction
{
    public function execute(Lead $lead, ClinicalClearanceStatus $status, ?Carbon $clearedAt = null): Lead
    {
        $clearedAt = $clearedAt ?? now();

        $expiresAt = match ($status) {
            ClinicalClearanceStatus::Cleared => $clearedAt->copy()->addMonths(6),
            default => null,
        };

        $lead->update([
            'clinical_clearance_status' => $status,
            'clinical_cleared_at' => $status === ClinicalClearanceStatus::Cleared ? $clearedAt : null,
            'clinical_clearance_expires_at' => $expiresAt,
        ]);

        return $lead->fresh();
    }
}
