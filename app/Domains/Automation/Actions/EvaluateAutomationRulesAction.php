<?php

namespace App\Domains\Automation\Actions;

use App\Domains\Automation\Engine\RuleExecutor;
use App\Domains\Automation\Engine\RuleMatcher;
use App\Domains\Automation\Models\AutomationLog;
use Illuminate\Support\Collection;

class EvaluateAutomationRulesAction
{
    public function __construct(
        private readonly RuleMatcher $matcher,
        private readonly RuleExecutor $executor,
    ) {}

    /**
     * @param  array<string, mixed>  $context
     * @return Collection<int, AutomationLog>
     */
    public function execute(string $triggerType, array $context = []): Collection
    {
        $rules = $this->matcher->match($triggerType, $context);

        return $rules->map(function ($rule) use ($context) {
            if ($rule->delay_minutes > 0) {
                return AutomationLog::query()->create([
                    'automation_rule_id' => $rule->id,
                    'lead_id' => $context['lead_id'] ?? null,
                    'status' => 'scheduled',
                    'channel' => $rule->channel,
                    'payload' => $context,
                    'executed_at' => now()->addMinutes($rule->delay_minutes),
                ]);
            }

            return $this->executor->execute($rule, $context);
        });
    }
}
