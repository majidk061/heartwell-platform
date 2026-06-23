<?php

namespace App\Domains\CRM\Actions;

use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Events\LeadStatusChanged;
use App\Domains\CRM\Models\Lead;
use App\Domains\CRM\Models\LeadStatusHistory;
use App\Domains\CRM\Rules\ValidLeadStatusTransition;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class TransitionLeadStatusAction
{
    public function execute(Lead $lead, LeadStatus $toStatus, ?int $changedBy = null, ?string $notes = null): Lead
    {
        $validator = Validator::make(
            ['status' => $toStatus->value],
            ['status' => [new ValidLeadStatusTransition($lead->status)]],
        );

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return DB::transaction(function () use ($lead, $toStatus, $changedBy, $notes) {
            $fromStatus = $lead->status;

            $lead->update(['status' => $toStatus]);

            LeadStatusHistory::create([
                'lead_id' => $lead->id,
                'from_status' => $fromStatus,
                'to_status' => $toStatus,
                'changed_by' => $changedBy,
                'notes' => $notes,
            ]);

            LeadStatusChanged::dispatch($lead->fresh(), $fromStatus, $toStatus);

            return $lead->fresh();
        });
    }
}
