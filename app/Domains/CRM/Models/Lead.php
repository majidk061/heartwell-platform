<?php

namespace App\Domains\CRM\Models;

use App\Domains\Booking\Models\Booking;
use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lead extends Model
{
    protected $table = 'crm_leads';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'source',
        'avatar_type',
        'status',
        'notes',
        'mailchimp_member_id',
        'assigned_to',
        'metadata',
    ];

    protected $casts = [
        'source' => LeadSource::class,
        'avatar_type' => AvatarType::class,
        'status' => LeadStatus::class,
        'metadata' => 'array',
    ];

    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(LeadStatusHistory::class, 'lead_id')->latest();
    }

    public function consultationRequests(): HasMany
    {
        return $this->hasMany(ConsultationRequest::class, 'lead_id');
    }

    public function waitlistEntries(): HasMany
    {
        return $this->hasMany(WaitlistEntry::class, 'lead_id');
    }

    public function groupInquiries(): HasMany
    {
        return $this->hasMany(GroupInquiry::class, 'lead_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'lead_id');
    }

    public function fullName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
