<?php

namespace Tests\Feature;

use App\Domains\Automation\Models\AutomationLog;
use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Enums\ClinicalClearanceStatus;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\Lead;
use App\Domains\Integrations\Models\EmailTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class P1WorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach ([
            'booking_confirmation',
            'booking_admin_notify',
            'booking_pending_clearance_admin',
            'clinical_intake_reminder',
            'appointment_reminder',
            'post_visit_followup',
            'clinical_clearance_renewal',
        ] as $key) {
            EmailTemplate::query()->create([
                'key' => $key,
                'name' => $key,
                'subject' => 'Test',
                'body' => '<p>{{first_name}}</p>',
                'is_enabled' => true,
            ]);
        }
    }

    public function test_waitlist_stores_avatar_type_and_interests(): void
    {
        $this->post(route('contact.waitlist'), [
            'name' => 'Avatar User',
            'email' => 'avatar@heartwell.test',
            'avatar_type' => AvatarType::Depleted->value,
            'avatar_interests' => [AvatarType::Depleted->value, AvatarType::Frustrated->value],
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('crm_waitlist_entries', [
            'email' => 'avatar@heartwell.test',
            'avatar_type' => AvatarType::Depleted->value,
        ]);

        $this->assertDatabaseHas('crm_leads', [
            'email' => 'avatar@heartwell.test',
            'avatar_type' => AvatarType::Depleted->value,
        ]);
    }

    public function test_acuity_booking_schedules_communications_and_alerts_pending_clearance(): void
    {
        Mail::fake();

        $this->postJson(route('webhooks.acuity'), [
            'action' => 'scheduled',
            'id' => 'p1-booking-1',
            'email' => 'pending@heartwell.test',
            'firstName' => 'Pending',
            'lastName' => 'Guest',
            'datetime' => now()->addDays(3)->format('Y-m-d H:i:s'),
        ])->assertOk();

        $lead = Lead::query()->where('email', 'pending@heartwell.test')->first();
        $this->assertNotNull($lead);
        $this->assertSame(ClinicalClearanceStatus::Pending, $lead->clinical_clearance_status);

        $this->assertDatabaseHas('automation_logs', [
            'lead_id' => $lead->id,
            'status' => 'scheduled',
            'channel' => 'email',
        ]);
    }

    public function test_clinical_clearance_expires_after_six_months(): void
    {
        $lead = Lead::query()->create([
            'first_name' => 'Renew',
            'last_name' => 'Me',
            'email' => 'renew@heartwell.test',
            'source' => LeadSource::Waitlist,
            'status' => LeadStatus::NewLead,
            'clinical_clearance_status' => ClinicalClearanceStatus::Cleared,
            'clinical_cleared_at' => now()->subMonths(6)->subDay(),
            'clinical_clearance_expires_at' => now()->subDay(),
        ]);

        Mail::fake();

        $this->artisan('heartwell:process-clinical-clearance')->assertSuccessful();

        $lead->refresh();
        $this->assertSame(ClinicalClearanceStatus::Expired, $lead->clinical_clearance_status);
    }

    public function test_process_automation_sends_due_scheduled_email(): void
    {
        $log = AutomationLog::query()->create([
            'automation_rule_id' => null,
            'status' => 'scheduled',
            'channel' => 'email',
            'payload' => [
                'template_key' => 'appointment_reminder',
                'email' => 'due@heartwell.test',
                'first_name' => 'Due',
            ],
            'executed_at' => now()->subMinute(),
        ]);

        Mail::fake();

        $this->artisan('heartwell:process-automation')->assertSuccessful();

        $log->refresh();
        $this->assertSame('sent', $log->status);
    }
}
