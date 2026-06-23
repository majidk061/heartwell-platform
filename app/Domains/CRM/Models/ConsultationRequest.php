<?php

namespace App\Domains\CRM\Models;

use App\Domains\CRM\Enums\AvatarType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultationRequest extends Model
{
    protected $table = 'crm_consultation_requests';

    protected $fillable = [
        'lead_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'message',
        'preferred_contact_method',
        'source_page',
        'avatar_type',
        'status',
        'metadata',
    ];

    protected $casts = [
        'avatar_type' => AvatarType::class,
        'metadata' => 'array',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
}
