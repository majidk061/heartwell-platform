<?php

namespace Tests\Feature;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Models\Testimonial;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaunchRefinementTest extends TestCase
{
    use RefreshDatabase;

    public function test_privacy_page_renders_as_cms_page(): void
    {
        $page = Page::query()->create([
            'slug' => 'privacy',
            'title' => 'Privacy Policy',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 8,
        ]);

        $hero = SectionTemplate::query()->create([
            'name' => 'Hero — privacy test',
            'section_type' => 'hero',
            'heading' => 'Privacy Policy',
            'content' => [
                'design_variant' => 'minimal',
                'show_pathway_bar' => false,
                'show_consultation_link' => false,
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        $body = SectionTemplate::query()->create([
            'name' => 'Rich text — privacy policy test',
            'section_type' => 'rich_text',
            'heading' => null,
            'content' => [
                'body' => '<p>Custom CMS privacy paragraph for launch test.</p><h2>Information we collect</h2><p>Contact details you provide.</p>',
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        foreach ([$hero, $body] as $index => $template) {
            PageSection::query()->create([
                'page_id' => $page->id,
                'section_template_id' => $template->id,
                'section_type' => $template->section_type,
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);
        }

        $this->get(route('privacy'))
            ->assertOk()
            ->assertSee('Privacy Policy')
            ->assertSee('Custom CMS privacy paragraph for launch test.')
            ->assertSee('Information we collect');
    }

    public function test_meet_the_founder_renders_founder_photo_when_image_in_storage(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('cms/sections/founder-jacquie.png', 'fake-image-content');

        $page = Page::query()->create([
            'slug' => 'meet-the-founder',
            'title' => 'Meet the Founder',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 6,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Founder teaser — full page',
            'section_type' => 'founder_teaser',
            'heading' => 'Meet the Founder',
            'content' => [
                'design_variant' => 'photo_left',
                'name' => 'Jacquie Wilson',
                'body' => 'Founder bio for photo test.',
                'image_url' => 'cms/sections/founder-jacquie.png',
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'founder_teaser',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('meet-the-founder'))
            ->assertOk()
            ->assertSee('Jacquie Wilson', false)
            ->assertSee('cms/sections/founder-jacquie.png', false)
            ->assertDontSee('Founder photo placeholder', false);
    }

    public function test_footer_uses_support_pathways_label(): void
    {
        Page::query()->create([
            'slug' => 'home',
            'title' => 'Home',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Support Pathways')
            ->assertDontSee('Support Options');
    }

    public function test_contact_page_does_not_show_most_popular_badge(): void
    {
        Page::query()->create([
            'slug' => 'contact',
            'title' => 'Contact',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 7,
        ]);

        SectionTemplate::query()->create([
            'name' => 'Contact forms',
            'section_type' => 'forms',
            'heading' => 'Connect',
            'content' => ['forms' => ['waitlist', 'consultation']],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        $template = SectionTemplate::query()->where('name', 'Contact forms')->first();

        PageSection::query()->create([
            'page_id' => Page::query()->where('slug', 'contact')->value('id'),
            'section_template_id' => $template->id,
            'section_type' => 'forms',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('contact'))
            ->assertOk()
            ->assertDontSee('Most popular', false);
    }

    public function test_home_renders_what_you_can_expect_instead_of_testimonials(): void
    {
        $page = Page::query()->create([
            'slug' => 'home',
            'title' => 'Home',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $trustTemplate = SectionTemplate::query()->create([
            'name' => 'Testimonials — grid',
            'section_type' => 'testimonials',
            'heading' => 'What You Can Expect',
            'content' => [
                'enabled' => false,
                'trust_features' => [
                    ['title' => 'Nurse-Led Care', 'body' => 'Every visit is guided by clinical experience.'],
                ],
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $trustTemplate->id,
            'section_type' => 'testimonials',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        Testimonial::query()->create([
            'author_name' => 'Jane Placeholder',
            'quote' => 'Lorem ipsum testimonial that should not appear.',
            'attribution' => 'Guest',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('What You Can Expect')
            ->assertSee('Nurse-Led Care')
            ->assertDontSee('Jane Placeholder');
    }

    public function test_hero_tagline_does_not_use_blush_utility_class(): void
    {
        $page = Page::query()->create([
            'slug' => 'why-heartwell',
            'title' => 'Why HeartWell',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 4,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Hero test',
            'section_type' => 'hero',
            'heading' => 'Why HeartWell',
            'content' => [
                'design_variant' => 'default',
                'subheading' => 'For Every Stage of Life',
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'hero',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('why-heartwell'))
            ->assertOk()
            ->assertSee('hw-hero-tagline', false)
            ->assertDontSee('text-hw-blush', false);
    }

    public function test_founder_eyebrow_does_not_use_blush_utility_class(): void
    {
        $page = Page::query()->create([
            'slug' => 'home',
            'title' => 'Home',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Founder teaser test',
            'section_type' => 'founder_teaser',
            'heading' => 'Meet the Founder',
            'content' => [
                'design_variant' => 'photo_left',
                'body' => 'Founder bio copy for test.',
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'founder_teaser',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('hw-founder-eyebrow', false)
            ->assertDontSee('text-hw-blush', false);
    }
}
