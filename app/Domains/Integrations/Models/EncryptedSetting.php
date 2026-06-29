<?php

namespace App\Domains\Integrations\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EncryptedSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group',
        'updated_by',
        'last_tested_at',
    ];

    protected $casts = [
        'last_tested_at' => 'datetime',
    ];

    public function updatedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
