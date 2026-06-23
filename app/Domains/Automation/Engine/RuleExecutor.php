<?php

namespace App\Domains\Automation\Engine;

use App\Domains\Automation\Models\AutomationLog;
use App\Domains\Automation\Models\AutomationRule;
use App\Domains\Integrations\Contracts\MailchimpServiceInterface;
use App\Domains\Integrations\Contracts\SendGridServiceInterface;
use Illuminate\Support\Facades\Log;
use Throwable;

class RuleExecutor
{
    public function __construct(
        private readonly MailchimpServiceInterface $mailchimp,
        private readonly SendGridServiceInterface $sendGrid,
    ) {}

    /**
     * @param  array<string, mixed>  $context
     */
    public function execute(AutomationRule $rule, array $context = []): AutomationLog
    {
        try {
            $result = match ($rule->channel) {
                'email' => $this->executeEmail($rule, $context),
                'mailchimp' => $this->executeMailchimp($rule, $context),
                default => ['status' => 'skipped', 'message' => "Unsupported channel: {$rule->channel}"],
            };

            return AutomationLog::create([
                'automation_rule_id' => $rule->id,
                'lead_id' => $context['lead_id'] ?? null,
                'status' => $result['status'],
                'channel' => $rule->channel,
                'payload' => array_merge($context, ['result' => $result]),
                'executed_at' => now(),
            ]);
        } catch (Throwable $e) {
            Log::error('Automation rule execution failed', [
                'rule_id' => $rule->id,
                'error' => $e->getMessage(),
            ]);

            return AutomationLog::create([
                'automation_rule_id' => $rule->id,
                'lead_id' => $context['lead_id'] ?? null,
                'status' => 'failed',
                'channel' => $rule->channel,
                'payload' => $context,
                'error_message' => $e->getMessage(),
                'executed_at' => now(),
            ]);
        }
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    private function executeEmail(AutomationRule $rule, array $context): array
    {
        $this->sendGrid->sendTemplate(
            $rule->template_ref ?? '',
            $context['email'] ?? '',
            $context,
        );

        return ['status' => 'sent'];
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    private function executeMailchimp(AutomationRule $rule, array $context): array
    {
        $memberId = $this->mailchimp->subscribe(
            $context['email'] ?? '',
            $context['first_name'] ?? '',
            $context['last_name'] ?? '',
            $context['tags'] ?? [],
        );

        return ['status' => 'sent', 'member_id' => $memberId];
    }
}
