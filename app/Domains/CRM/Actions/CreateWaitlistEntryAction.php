<?php

namespace App\Domains\CRM\Actions;

use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Events\WaitlistJoined;
use App\Domains\CRM\Models\Lead;
use App\Domains\CRM\Models\WaitlistEntry;
use Illuminate\Support\Facades\DB;

class CreateWaitlistEntryAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): WaitlistEntry
    {
        return DB::transaction(function () use ($data) {
            $lead = $this->findOrCreateLead($data);

            $entry = WaitlistEntry::create([
                'lead_id' => $lead->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'interests' => $data['interests'] ?? null,
                'source_page' => $data['source_page'] ?? null,
                'avatar_type' => isset($data['avatar_type'])
                    ? AvatarType::tryFrom($data['avatar_type'])
                    : null,
                'status' => 'active',
                'metadata' => $data['metadata'] ?? null,
            ]);

            WaitlistJoined::dispatch($entry);

            return $entry;
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function findOrCreateLead(array $data): Lead
    {
        $avatarType = isset($data['avatar_type'])
            ? AvatarType::tryFrom($data['avatar_type'])
            : null;
        $avatarTags = $this->avatarInterestTags($data['interests'] ?? []);

        $lead = Lead::query()->where('email', $data['email'])->first();

        if ($lead) {
            $this->syncLeadAvatar($lead, $avatarType, $avatarTags);

            return $lead;
        }

        return Lead::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'] ?? null,
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'source' => LeadSource::Waitlist,
            'avatar_type' => $avatarType,
            'tags' => $avatarTags !== [] ? $avatarTags : null,
            'status' => LeadStatus::NewLead,
        ]);
    }

    /**
     * @param  array<int, string>  $interests
     * @return array<int, string>
     */
    private function avatarInterestTags(array $interests): array
    {
        return array_values(array_unique(array_map(
            fn (string $interest) => 'avatar:'.$interest,
            $interests,
        )));
    }

    /**
     * @param  array<int, string>  $avatarTags
     */
    private function syncLeadAvatar(Lead $lead, ?AvatarType $avatarType, array $avatarTags): void
    {
        $updates = [];

        if ($avatarType !== null) {
            $updates['avatar_type'] = $avatarType;
        }

        if ($avatarTags !== []) {
            $updates['tags'] = array_values(array_unique(array_merge($lead->tags ?? [], $avatarTags)));
        }

        if ($updates !== []) {
            $lead->update($updates);
        }
    }
}
