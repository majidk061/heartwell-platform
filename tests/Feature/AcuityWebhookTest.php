<?php

namespace Tests\Feature;

use App\Domains\Booking\Models\Booking;
use App\Domains\Integrations\Models\EmailTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AcuityWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(\App\Domains\Integrations\Services\SettingsResolver::class)
            ->set('acuity_webhook_secret', null, 'integrations');
        config(['integrations.acuity.webhook_secret' => null]);

        EmailTemplate::query()->create([
            'key' => 'booking_confirmation',
            'name' => 'Booking confirmation',
            'subject' => 'Confirmed',
            'body' => '<p>Hi {{first_name}}, your visit on {{booking_date}} is confirmed.</p>',
            'is_enabled' => true,
        ]);

        EmailTemplate::query()->create([
            'key' => 'booking_admin_notify',
            'name' => 'Booking admin',
            'subject' => 'New booking',
            'body' => '<p>{{email}}</p>',
            'is_enabled' => true,
        ]);
    }

    public function test_acuity_webhook_creates_booking_and_lead_from_payload(): void
    {
        Mail::fake();

        $this->postJson(route('webhooks.acuity'), [
            'action' => 'scheduled',
            'id' => 'acuity-123',
            'email' => 'booked@heartwell.test',
            'firstName' => 'Taylor',
            'lastName' => 'Guest',
            'datetime' => '2026-07-15 14:00:00',
        ])->assertOk()->assertJson(['ok' => true]);

        $this->assertDatabaseHas('booking_bookings', [
            'external_acuity_id' => 'acuity-123',
            'status' => 'confirmed',
        ]);

        $this->assertDatabaseHas('crm_leads', [
            'email' => 'booked@heartwell.test',
            'first_name' => 'Taylor',
        ]);
    }

    public function test_acuity_webhook_rejects_invalid_secret_when_configured(): void
    {
        app(\App\Domains\Integrations\Services\SettingsResolver::class)
            ->set('acuity_webhook_secret', 'test-secret', 'integrations');
        config(['integrations.acuity.webhook_secret' => 'test-secret']);

        $this->postJson(route('webhooks.acuity'), [
            'action' => 'scheduled',
            'id' => '999',
        ])->assertForbidden();

        $this->postJson(route('webhooks.acuity', ['secret' => 'test-secret']), [
            'action' => 'scheduled',
            'id' => '999',
            'email' => 'ok@heartwell.test',
            'firstName' => 'Ok',
            'datetime' => '2026-08-01 10:00:00',
        ])->assertOk();

        $this->assertDatabaseHas('booking_bookings', [
            'external_acuity_id' => '999',
        ]);
    }

    public function test_duplicate_webhook_updates_same_booking(): void
    {
        $this->postJson(route('webhooks.acuity'), [
            'action' => 'scheduled',
            'id' => 'dup-1',
            'email' => 'dup@heartwell.test',
            'firstName' => 'Dup',
            'datetime' => '2026-07-01 09:00:00',
        ])->assertOk();

        $this->postJson(route('webhooks.acuity'), [
            'action' => 'scheduled',
            'id' => 'dup-1',
            'email' => 'dup@heartwell.test',
            'firstName' => 'Dup',
            'datetime' => '2026-07-02 09:00:00',
        ])->assertOk();

        $this->assertSame(1, Booking::query()->where('external_acuity_id', 'dup-1')->count());
    }
}
