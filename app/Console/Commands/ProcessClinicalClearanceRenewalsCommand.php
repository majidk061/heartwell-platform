<?php

namespace App\Console\Commands;

use App\Domains\CRM\Actions\UpdateClinicalClearanceAction;
use App\Domains\CRM\Enums\ClinicalClearanceStatus;
use App\Domains\CRM\Models\Lead;
use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use Illuminate\Console\Command;

class ProcessClinicalClearanceRenewalsCommand extends Command
{
    protected $signature = 'heartwell:process-clinical-clearance';

    protected $description = 'Expire overdue clinical clearances and send renewal reminders';

    public function handle(
        UpdateClinicalClearanceAction $updateClearance,
        SendTemplatedEmailAction $sendTemplatedEmail,
    ): int {
        $expired = Lead::query()
            ->where('clinical_clearance_status', ClinicalClearanceStatus::Cleared)
            ->whereNotNull('clinical_clearance_expires_at')
            ->where('clinical_clearance_expires_at', '<=', now())
            ->get();

        $count = 0;

        foreach ($expired as $lead) {
            $updateClearance->execute($lead, ClinicalClearanceStatus::Expired);

            if (filled($lead->email)) {
                $sendTemplatedEmail->execute('clinical_clearance_renewal', $lead->email, [
                    'lead_id' => $lead->id,
                    'first_name' => $lead->first_name,
                    'last_name' => $lead->last_name,
                    'email' => $lead->email,
                    'clinical_intake_url' => url('/clinical-intake'),
                ]);
            }

            $count++;
        }

        $this->info("Processed {$count} expired clinical clearance(s).");

        return self::SUCCESS;
    }
}
