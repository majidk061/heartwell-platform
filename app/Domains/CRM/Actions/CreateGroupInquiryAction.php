<?php

namespace App\Domains\CRM\Actions;

use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Events\GroupInquirySubmitted;
use App\Domains\CRM\Models\GroupInquiry;
use App\Domains\CRM\Models\Lead;
use Illuminate\Support\Facades\DB;

class CreateGroupInquiryAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): GroupInquiry
    {
        return DB::transaction(function () use ($data) {
            $lead = $this->findOrCreateLead($data);

            $inquiry = GroupInquiry::create([
                'lead_id' => $lead->id,
                'host_name' => $data['host_name'],
                'host_email' => $data['host_email'],
                'host_phone' => $data['host_phone'] ?? null,
                'event_name' => $data['event_name'] ?? null,
                'event_date' => $data['event_date'] ?? null,
                'guest_count' => $data['guest_count'] ?? null,
                'message' => $data['message'] ?? null,
                'status' => 'pending',
                'metadata' => $data['metadata'] ?? null,
            ]);

            GroupInquirySubmitted::dispatch($inquiry);

            return $inquiry;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function findOrCreateLead(array $data): Lead
    {
        $lead = Lead::query()->where('email', $data['host_email'])->first();

        if ($lead) {
            return $lead;
        }

        $nameParts = explode(' ', $data['host_name'], 2);

        return Lead::create([
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? null,
            'email' => $data['host_email'],
            'phone' => $data['host_phone'] ?? null,
            'source' => LeadSource::GroupInquiry,
            'status' => LeadStatus::NewLead,
        ]);
    }
}
