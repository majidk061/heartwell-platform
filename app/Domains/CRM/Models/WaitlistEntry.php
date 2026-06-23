<?php

namespace App\Domains\CRM\Models;

use App\Domains\CRM\Enums\AvatarType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitlistEntry extends Model
{
    protected $table = 'crm_waitlist_entries';

    protected $fillable = [
        'lead_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'interests',
        'source_page',
        'avatar_type',
        'status',
        'metadata',
    ];

    protected $casts = [
        'interests' => 'array',
        'avatar_type' => AvatarType::class,
        'metadata' => 'array',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
