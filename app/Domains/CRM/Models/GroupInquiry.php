<?php

namespace App\Domains\CRM\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GroupInquiry extends Model
{
    protected $table = 'crm_group_inquiries';

    protected $fillable = [
        'lead_id',
        'host_name',
        'host_email',
        'host_phone',
        'event_name',
        'event_date',
        'guest_count',
        'message',
        'status',
        'metadata',
    ];

    protected $casts = [
        'event_date' => 'date',
        'guest_count' => 'integer',
        'metadata' => 'array',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
