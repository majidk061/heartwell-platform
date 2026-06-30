<?php

namespace App\Domains\CRM\Models;

use App\Domains\Booking\Models\Booking;
use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Enums\ClinicalClearanceStatus;
use App\Domains\CRM\Enums\LeadPriority;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Enums\PreferredContactMethod;
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
        'source_page',
        'avatar_type',
        'status',
        'clinical_clearance_status',
        'clinical_cleared_at',
        'clinical_clearance_expires_at',
        'priority',
        'preferred_contact_method',
        'notes',
        'mailchimp_member_id',
        'assigned_to',
        'last_contacted_at',
        'next_follow_up_at',
        'marketing_consent',
        'marketing_consent_at',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'tags',
        'closed_reason',
        'metadata',
    ];

    protected $casts = [
        'source' => LeadSource::class,
        'avatar_type' => AvatarType::class,
        'status' => LeadStatus::class,
        'clinical_clearance_status' => ClinicalClearanceStatus::class,
        'clinical_cleared_at' => 'datetime',
        'clinical_clearance_expires_at' => 'datetime',
        'priority' => LeadPriority::class,
        'preferred_contact_method' => PreferredContactMethod::class,
        'last_contacted_at' => 'datetime',
        'next_follow_up_at' => 'datetime',
        'marketing_consent' => 'boolean',
        'marketing_consent_at' => 'datetime',
        'tags' => 'array',
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

    public function hasValidClinicalClearance(): bool
    {
        if ($this->clinical_clearance_status !== ClinicalClearanceStatus::Cleared) {
            return false;
        }

        if ($this->clinical_clearance_expires_at === null) {
            return true;
        }

        return $this->clinical_clearance_expires_at->isFuture();
    }
}
