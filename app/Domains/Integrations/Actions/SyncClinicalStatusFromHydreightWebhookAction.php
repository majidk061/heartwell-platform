<?php

namespace App\Domains\Integrations\Actions;

use App\Domains\CRM\Actions\UpdateClinicalClearanceAction;
use App\Domains\CRM\Enums\ClinicalClearanceStatus;
use App\Domains\CRM\Models\Lead;
use Illuminate\Support\Facades\Log;

class SyncClinicalStatusFromHydreightWebhookAction
{
    public function __construct(
        private readonly UpdateClinicalClearanceAction $updateClinicalClearance,
    ) {}

    /**
     * @param  array<string, mixed>  $payload
     */
    public function execute(array $payload): bool
    {
        $email = (string) ($payload['email'] ?? $payload['patient_email'] ?? '');
        $status = strtolower((string) ($payload['status'] ?? $payload['clearance_status'] ?? ''));

        if ($email === '') {
            Log::warning('[Hydreight webhook] missing email', $payload);

            return false;
        }

        $lead = Lead::query()->where('email', $email)->first();

        if (! $lead) {
            Log::info('[Hydreight webhook] lead not found', ['email' => $email]);

            return false;
        }

        $clearanceStatus = match ($status) {
            'cleared', 'approved', 'complete', 'completed' => ClinicalClearanceStatus::Cleared,
            'expired', 'renewal_required' => ClinicalClearanceStatus::Expired,
            'pending', 'incomplete', '' => ClinicalClearanceStatus::Pending,
            default => null,
        };

        if ($clearanceStatus === null) {
            Log::warning('[Hydreight webhook] unknown status', ['status' => $status, 'email' => $email]);

            return false;
        }

        $this->updateClinicalClearance->execute($lead, $clearanceStatus);

        return true;
    }
}
