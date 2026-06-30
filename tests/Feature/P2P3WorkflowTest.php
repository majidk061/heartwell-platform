<?php

namespace Tests\Feature;

use App\Domains\Automation\Models\AutomationLog;
use App\Domains\Automation\Models\AutomationRule;
use App\Domains\CRM\Enums\ClinicalClearanceStatus;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\Lead;
use App\Domains\Integrations\Models\EmailTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class P2P3WorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_hydreight_webhook_updates_clinical_clearance(): void
    {
        Lead::query()->create([
            'first_name' => 'Clinical',
            'last_name' => 'Guest',
            'email' => 'clinical@heartwell.test',
            'source' => LeadSource::Acuity,
            'status' => LeadStatus::Booked,
            'clinical_clearance_status' => ClinicalClearanceStatus::Pending,
        ]);

        config(['integrations.hydreight.webhook_secret' => 'hydreight-secret']);

        $this->postJson(route('webhooks.hydreight', ['secret' => 'hydreight-secret']), [
            'email' => 'clinical@heartwell.test',
            'status' => 'cleared',
        ])->assertOk()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('crm_leads', [
            'email' => 'clinical@heartwell.test',
            'clinical_clearance_status' => ClinicalClearanceStatus::Cleared->value,
        ]);
    }

    public function test_my_visit_hub_page_renders(): void
    {
        $this->get(route('my-visit'))
            ->assertOk()
            ->assertSee('Your HeartWell Visit')
            ->assertSee('Complete clinical intake');
    }

    public function test_waitlist_listener_does_not_duplicate_welcome_email(): void
    {
        EmailTemplate::query()->create([
            'key' => 'waitlist_welcome',
            'name' => 'Welcome',
            'subject' => 'Welcome',
            'body' => '<p>Hi</p>',
            'is_enabled' => true,
        ]);

        AutomationRule::query()->create([
            'name' => 'Waitlist Welcome Email',
            'trigger_type' => 'waitlist_joined',
            'channel' => 'email',
            'template_ref' => 'waitlist_welcome',
            'delay_minutes' => 0,
            'is_active' => true,
        ]);

        Mail::fake();

        $this->post(route('contact.waitlist'), [
            'name' => 'Once Only',
            'email' => 'once@heartwell.test',
        ])->assertRedirect();

        $welcomeExecutions = AutomationLog::query()
            ->whereHas('rule', fn ($query) => $query->where('template_ref', 'waitlist_welcome'))
            ->count();

        $this->assertSame(1, $welcomeExecutions);
    }
}
