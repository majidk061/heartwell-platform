<?php

namespace App\Domains\Automation\Engine;

use App\Domains\Automation\Models\AutomationRule;
use Illuminate\Support\Collection;

class RuleMatcher
{
    /**
     * @param  array<string, mixed>  $context
     * @return Collection<int, AutomationRule>
     */
    public function match(string $triggerType, array $context = []): Collection
    {
        return AutomationRule::query()
            ->where('is_active', true)
            ->where('trigger_type', $triggerType)
            ->get()
            ->filter(fn (AutomationRule $rule) => $this->conditionsMet($rule, $context));
    }

    /**
     * @param  array<string, mixed>  $context
     */
    private function conditionsMet(AutomationRule $rule, array $context): bool
    {
        $conditions = $rule->conditions ?? [];

        if ($conditions === []) {
            return true;
        }

        foreach ($conditions as $key => $expected) {
            if (! array_key_exists($key, $context)) {
                return false;
            }

            $actual = $context[$key];

            if (is_array($expected)) {
                if (! in_array($actual, $expected, true)) {
                    return false;
                }
            } elseif ($actual !== $expected) {
                return false;
            }
        }

        return true;
    }
}
