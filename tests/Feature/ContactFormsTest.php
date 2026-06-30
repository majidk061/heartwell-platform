<?php

namespace Tests\Feature;

use App\Domains\CRM\Models\GroupInquiry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormsTest extends TestCase
{
    use RefreshDatabase;

    public function test_waitlist_accepts_single_name_field_from_public_form(): void
    {
        $this->post(route('contact.waitlist'), [
            'name' => 'Jane Doe',
            'email' => 'jane@heartwell.test',
            'phone' => '555-0100',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('crm_waitlist_entries', [
            'email' => 'jane@heartwell.test',
            'first_name' => 'Jane',
        ]);
    }

    public function test_consultation_accepts_single_name_field_from_public_form(): void
    {
        $this->post(route('contact.consultation'), [
            'name' => 'Maria Lopez',
            'email' => 'maria@heartwell.test',
            'message' => 'Looking for support with energy.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('crm_consultation_requests', [
            'email' => 'maria@heartwell.test',
            'first_name' => 'Maria',
        ]);
    }

    public function test_group_inquiry_accepts_public_form_field_names(): void
    {
        $this->post(route('contact.group-inquiry'), [
            'host_name' => 'Alex Host',
            'email' => 'alex@heartwell.test',
            'phone' => '555-0200',
            'guest_count' => 8,
            'event_details' => 'Birthday wellness gathering in May.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('crm_group_inquiries', [
            'host_email' => 'alex@heartwell.test',
            'host_name' => 'Alex Host',
        ]);

        $inquiry = GroupInquiry::query()->where('host_email', 'alex@heartwell.test')->first();
        $this->assertNotNull($inquiry);
        $this->assertStringContainsString('Birthday wellness', (string) $inquiry->message);
    }

    public function test_group_inquiry_accepts_canonical_field_names(): void
    {
        $this->post(route('contact.group-inquiry'), [
            'host_name' => 'Sam Host',
            'host_email' => 'sam@heartwell.test',
            'host_phone' => '555-0300',
            'guest_count' => 6,
            'message' => 'Corporate retreat inquiry.',
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('crm_group_inquiries', [
            'host_email' => 'sam@heartwell.test',
        ]);
    }
}
