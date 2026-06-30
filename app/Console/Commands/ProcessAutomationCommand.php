<?php

namespace App\Console\Commands;

use App\Domains\Automation\Engine\RuleExecutor;
use App\Domains\Automation\Models\AutomationLog;
use App\Domains\Integrations\Actions\SendSmsAction;
use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use Illuminate\Console\Command;

class ProcessAutomationCommand extends Command
{
    protected $signature = 'heartwell:process-automation';

    protected $description = 'Process scheduled automation rules that are due for execution';

    public function handle(
        RuleExecutor $executor,
        SendTemplatedEmailAction $sendTemplatedEmail,
        SendSmsAction $sendSms,
    ): int {
        $pending = AutomationLog::query()
            ->where('status', 'scheduled')
            ->whereNotNull('executed_at')
            ->where('executed_at', '<=', now())
            ->with('rule')
            ->get();

        $processed = 0;

        foreach ($pending as $log) {
            if ($log->rule && $log->rule->is_active) {
                try {
                    $result = $executor->process($log->rule, $log->payload ?? []);
                    $log->update([
                        'status' => $result['status'] ?? 'processed',
                        'executed_at' => now(),
                        'payload' => array_merge($log->payload ?? [], ['result' => $result]),
                    ]);
                    $processed++;
                } catch (\Throwable $exception) {
                    $log->update([
                        'status' => 'failed',
                        'error_message' => $exception->getMessage(),
                        'executed_at' => now(),
                    ]);
                }

                continue;
            }

            if (! $log->rule) {
                $payload = $log->payload ?? [];
                $status = 'skipped';

                try {
                    if ($log->channel === 'email' && filled($payload['template_key'] ?? null)) {
                        $sent = $sendTemplatedEmail->execute(
                            (string) $payload['template_key'],
                            (string) ($payload['email'] ?? ''),
                            $payload,
                        );
                        $status = $sent ? 'sent' : 'failed';
                    } elseif ($log->channel === 'sms' && filled($payload['template_key'] ?? null)) {
                        $sent = $sendSms->executeTemplate(
                            (string) $payload['template_key'],
                            (string) ($payload['phone'] ?? ''),
                            $payload,
                        );
                        $status = $sent ? 'sent' : 'failed';
                    }
                } catch (\Throwable $exception) {
                    $log->update([
                        'status' => 'failed',
                        'error_message' => $exception->getMessage(),
                        'executed_at' => now(),
                    ]);

                    continue;
                }

                $log->update(['status' => $status, 'executed_at' => now()]);
                $processed++;
            }
        }

        $this->info("Processed {$processed} scheduled automation(s).");

        return self::SUCCESS;
    }
}
