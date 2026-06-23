<?php

namespace App\Domains\CRM\Actions;

use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Events\ConsultationRequested;
use App\Domains\CRM\Models\ConsultationRequest;
use App\Domains\CRM\Models\Lead;
use Illuminate\Support\Facades\DB;

class CreateConsultationRequestAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): ConsultationRequest
    {
        return DB::transaction(function () use ($data) {
            $lead = $this->findOrCreateLead($data);

            $request = ConsultationRequest::create([
                'lead_id' => $lead->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'message' => $data['message'] ?? null,
                'preferred_contact_method' => $data['preferred_contact_method'] ?? null,
                'source_page' => $data['source_page'] ?? null,
                'avatar_type' => isset($data['avatar_type'])
                    ? AvatarType::tryFrom($data['avatar_type'])
                    : null,
                'status' => 'pending',
                'metadata' => $data['metadata'] ?? null,
            ]);

            ConsultationRequested::dispatch($request);

            return $request;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function findOrCreateLead(array $data): Lead
    {
        $lead = Lead::query()->where('email', $data['email'])->first();

        if ($lead) {
            return $lead;
        }

        return Lead::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'source' => LeadSource::Consultation,
            'avatar_type' => isset($data['avatar_type'])
                ? AvatarType::tryFrom($data['avatar_type'])
                : null,
            'status' => LeadStatus::NewLead,
        ]);
    }
}
