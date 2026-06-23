<?php

namespace App\Domains\Automation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AutomationRule extends Model
{
    protected $table = 'automation_rules';

    protected $fillable = [
        'name',
        'trigger_type',
        'channel',
        'template_ref',
        'delay_minutes',
        'conditions',
        'is_active',
    ];

    protected $casts = [
        'delay_minutes' => 'integer',
        'conditions' => 'array',
        'is_active' => 'boolean',
    ];

    public function logs(): HasMany
    {
        return $this->hasMany(AutomationLog::class, 'automation_rule_id')->latest();
    }
}
