<?php

namespace App\Jobs;

use App\Domains\CRM\Models\Lead;
use App\Domains\Integrations\Contracts\MailchimpServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscribeToMailchimpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly int $leadId,
        public readonly array $tags = [],
    ) {}

    public function handle(MailchimpServiceInterface $mailchimp): void
    {
        $lead = Lead::query()->find($this->leadId);

        if (! $lead) {
            return;
        }

        $memberId = $mailchimp->subscribe(
            $lead->email,
            $lead->first_name,
            $lead->last_name,
            $this->tags ?: config('integrations.mailchimp.default_tags', []),
        );

        if ($memberId) {
            $lead->update(['mailchimp_member_id' => $memberId]);
        }
    }
}
