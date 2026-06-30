<?php

namespace App\Domains\Automation\Engine;

use App\Domains\Automation\Models\AutomationLog;
use App\Domains\Automation\Models\AutomationRule;
use App\Domains\Integrations\Actions\SendSmsAction;
use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use App\Domains\Integrations\Contracts\MailchimpServiceInterface;
use App\Domains\Integrations\Contracts\SendGridServiceInterface;
use App\Domains\Integrations\Services\MailChannelResolver;
use Illuminate\Support\Facades\Log;
use Throwable;

class RuleExecutor
{
    public function __construct(
        private readonly MailchimpServiceInterface $mailchimp,
        private readonly SendGridServiceInterface $sendGrid,
        private readonly MailChannelResolver $mailChannel,
        private readonly SendTemplatedEmailAction $sendTemplatedEmail,
        private readonly SendSmsAction $sendSms,
    ) {}

    /**
     * @param  array<string, mixed>  $context
     */
    public function execute(AutomationRule $rule, array $context = []): AutomationLog
    {
        try {
            $result = $this->process($rule, $context);

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
    public function process(AutomationRule $rule, array $context): array
    {
        return match ($rule->channel) {
            'email' => $this->executeEmail($rule, $context),
            'mailchimp' => $this->executeMailchimp($rule, $context),
            'sms' => $this->executeSms($rule, $context),
            default => ['status' => 'skipped', 'message' => "Unsupported channel: {$rule->channel}"],
        };
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    private function executeEmail(AutomationRule $rule, array $context): array
    {
        $channel = $this->mailChannel->resolve();
        $templateKey = $rule->template_ref ?? '';

        if ($channel === 'smtp') {
            $sent = $this->sendTemplatedEmail->execute(
                $templateKey,
                $context['email'] ?? '',
                $context,
            );

            return ['status' => $sent ? 'sent' : 'skipped', 'channel' => 'smtp'];
        }

        $sendGridTemplateId = config('integrations.sendgrid.templates.'.$templateKey) ?? $templateKey;

        $this->sendGrid->sendTemplate(
            $sendGridTemplateId,
            $context['email'] ?? '',
            $context,
        );

        return ['status' => 'sent', 'channel' => 'sendgrid'];
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

    /**
     * @param  array<string, mixed>  $context
     * @return array<string, mixed>
     */
    private function executeSms(AutomationRule $rule, array $context): array
    {
        $templateKey = $rule->template_ref ?? 'appointment_reminder_sms';
        $phone = $context['phone'] ?? '';

        $sent = $this->sendSms->executeTemplate($templateKey, $phone, $context);

        return ['status' => $sent ? 'sent' : 'skipped', 'channel' => 'sms'];
    }
}
