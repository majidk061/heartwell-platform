<?php

namespace Tests\Feature;

use App\Domains\Content\Support\ClientCopyCatalog;
use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
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

    public function test_book_tab_shows_scheduling_fallback_when_acuity_is_not_configured(): void
    {
        config(['integrations.acuity.embed_url' => null]);

        $page = Page::query()->create([
            'slug' => 'contact',
            'title' => 'Contact',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 7,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Contact forms — book fallback',
            'section_type' => 'forms',
            'heading' => 'Connect',
            'content' => ['forms' => ['waitlist', 'consultation', 'group_inquiry']],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'forms',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('contact').'#book')
            ->assertOk()
            ->assertSee('Online scheduling is coming soon', false)
            ->assertSee('Join the Waitlist', false)
            ->assertSee('Begin with a Private Wellness Conversation', false)
            ->assertDontSee('<iframe', false);
    }

    public function test_contact_forms_show_pre_form_guidance_and_avatar_hint(): void
    {
        config(['integrations.acuity.embed_url' => null]);

        $page = Page::query()->create([
            'slug' => 'contact',
            'title' => 'Contact',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 7,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Contact forms — guidance',
            'section_type' => 'forms',
            'heading' => 'Connect',
            'content' => ['forms' => ['waitlist', 'consultation', 'group_inquiry']],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'forms',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('contact').'#waitlist')
            ->assertOk()
            ->assertSee('hw-contact-nav--stacked', false)
            ->assertSee('Select all that resonate.', false)
            ->assertSee('general inquiry purposes only', false);
    }

    public function test_contact_form_success_message_uses_approved_copy(): void
    {
        $this->post(route('contact.waitlist'), [
            'name' => 'Jane Doe',
            'email' => 'jane@heartwell.test',
        ])->assertRedirect()->assertSessionHas('success', ClientCopyCatalog::FORM_THANK_YOU);
    }
}
