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

        return $rules->map(fn ($rule) => $this->executor->execute($rule, $context));
    }
}
