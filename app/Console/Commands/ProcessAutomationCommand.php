<?php

namespace App\Console\Commands;

use App\Domains\Automation\Engine\RuleExecutor;
use App\Domains\Automation\Models\AutomationLog;
use Illuminate\Console\Command;

class ProcessAutomationCommand extends Command
{
    protected $signature = 'heartwell:process-automation';

    protected $description = 'Process scheduled automation rules that are due for execution';

    public function handle(RuleExecutor $executor): int
    {
        $pending = AutomationLog::query()
            ->where('status', 'scheduled')
            ->whereNotNull('executed_at')
            ->where('executed_at', '<=', now())
            ->with('rule')
            ->get();

        $processed = 0;

        foreach ($pending as $log) {
            $rule = $log->rule;

            if (! $rule || ! $rule->is_active) {
                $log->update(['status' => 'skipped']);

                continue;
            }

            $executor->execute($rule, $log->payload ?? []);
            $log->update(['status' => 'processed']);
            $processed++;
        }

        $this->info("Processed {$processed} scheduled automation(s).");

        return self::SUCCESS;
    }
}
