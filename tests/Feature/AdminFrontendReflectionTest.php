<?php

namespace Tests\Feature;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Faq;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Models\SiteSetting;
use App\Domains\Content\Models\SupportPathway;
use App\Domains\Content\Support\CmsImage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AdminFrontendReflectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: string}>
     */
    public static function publicPageSlugs(): array
    {
        return [
            'home' => ['home', '/'],
            'support-pathways' => ['support-pathways', 'support-pathways'],
            'your-experience' => ['your-experience', 'your-experience'],
            'why-heartwell' => ['why-heartwell', 'why-heartwell'],
            'wellness-journey' => ['wellness-journey', 'wellness-journey'],
            'meet-the-founder' => ['meet-the-founder', 'meet-the-founder'],
            'contact' => ['contact', 'contact'],
        ];
    }

    #[DataProvider('publicPageSlugs')]
    public function test_public_pages_render_when_published(string $slug, string $routeName): void
    {
        Page::query()->create([
            'slug' => $slug,
            'title' => ucfirst(str_replace('-', ' ', $slug)),
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $url = $routeName === '/' ? '/' : route($routeName);

        $this->get($url)->assertOk();
    }

    public function test_inner_page_hero_uses_variant_system_not_legacy_page_hero(): void
    {
        $page = Page::query()->create([
            'slug' => 'why-heartwell',
            'title' => 'Why HeartWell',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 3,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Hero — why',
            'section_type' => 'hero',
            'heading' => 'Unique Hero Marker XYZ',
            'content' => [
                'design_variant' => 'minimal',
                'body' => 'Hero body for reflection test.',
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
            ->assertSee('Unique Hero Marker XYZ')
            ->assertSee('hw-hero', false);
    }

    public function test_section_template_heading_reflects_on_page(): void
    {
        $page = Page::query()->create([
            'slug' => 'your-experience',
            'title' => 'Your Experience',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 4,
        ]);

        $marker = 'Reflection Marker '.uniqid();

        $template = SectionTemplate::query()->create([
            'name' => 'Features reflection',
            'section_type' => 'features',
            'heading' => $marker,
            'content' => [
                'features' => [
                    ['title' => 'Feature A', 'body' => 'Body A'],
                ],
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'features',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('your-experience'))
            ->assertOk()
            ->assertSee($marker);
    }

    public function test_section_layout_container_width_reflects_on_frontend(): void
    {
        $page = Page::query()->create([
            'slug' => 'wellness-journey',
            'title' => 'Wellness Journey',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 5,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Rich text extra wide',
            'section_type' => 'rich_text',
            'heading' => 'Layout width test',
            'content' => ['body' => '<p>Layout body</p>'],
            'layout' => ['container_width' => 'extra_wide'],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'rich_text',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('wellness-journey'))
            ->assertOk()
            ->assertSee('hw-container-extra-wide', false);
    }

    public function test_pathway_cards_design_variant_renders_structured_markup(): void
    {
        $page = Page::query()->create([
            'slug' => 'support-pathways',
            'title' => 'Support Pathways',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Pathways cards',
            'section_type' => 'pathways_teaser',
            'heading' => 'Choose your pathway',
            'content' => ['design_variant' => 'pathway_cards'],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'pathways_teaser',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        SupportPathway::query()->create([
            'slug' => 'test-pathway',
            'title' => 'Test Pathway Title',
            'tagline' => 'Unique tagline marker',
            'intro' => 'Intro copy',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('support-pathways'))
            ->assertOk()
            ->assertSee('hw-pathway-card', false)
            ->assertSee('Unique tagline marker');
    }

    public function test_compliance_callout_intro_variant_renders_markup(): void
    {
        $page = Page::query()->create([
            'slug' => 'support-pathways',
            'title' => 'Support Pathways',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Compliance intro',
            'section_type' => 'intro',
            'heading' => 'Clinical clearance required',
            'content' => [
                'design_variant' => 'compliance_callout',
                'body' => 'NJ regulations apply to all visits.',
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'intro',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('support-pathways'))
            ->assertOk()
            ->assertSee('hw-compliance-callout', false)
            ->assertSee('NJ regulations apply to all visits.');
    }

    public function test_site_settings_logo_reflects_admin_upload_not_trimmed_override(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('cms/branding/admin-logo.png', 'logo-bytes');
        Storage::disk('public')->put('cms/branding/heartwell-logo-trimmed.png', 'trimmed-bytes');

        SiteSetting::query()->updateOrCreate(['key' => 'branding'], ['value' => [
            'logo_mode' => 'image',
            'logo_text' => 'HeartWell',
            'logo_image_path' => 'cms/branding/admin-logo.png',
        ]]);

        Page::query()->create([
            'slug' => 'home',
            'title' => 'Home',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        view()->share('siteSettings', app(\App\Domains\Content\Actions\GetSiteSettingsAction::class)->execute());

        $response = $this->get(route('home'))->assertOk();

        $response->assertSee('cms/branding/admin-logo.png', false);
        $response->assertDontSee('heartwell-logo-trimmed.png', false);
    }

    public function test_site_settings_navigation_label_reflects_in_header(): void
    {
        SiteSetting::query()->updateOrCreate(['key' => 'navigation'], ['value' => [
            ['label' => 'Custom Nav Label', 'route' => 'home'],
        ]]);

        Page::query()->create([
            'slug' => 'home',
            'title' => 'Home',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Custom Nav Label');
    }

    public function test_site_settings_cta_labels_reflect_in_header(): void
    {
        SiteSetting::query()->updateOrCreate(['key' => 'ctas'], ['value' => [
            'primary' => ['label' => 'Book Custom Visit', 'anchor' => '#book'],
            'secondary' => ['waitlist' => ['label' => 'Join Custom Waitlist', 'anchor' => '#waitlist']],
        ]]);

        Page::query()->create([
            'slug' => 'home',
            'title' => 'Home',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Book Custom Visit')
            ->assertSee('Join Custom Waitlist');
    }

    public function test_faq_page_slug_filter_only_shows_matching_faqs(): void
    {
        $page = Page::query()->create([
            'slug' => 'wellness-journey',
            'title' => 'Wellness Journey',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 5,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'FAQ block',
            'section_type' => 'faq',
            'heading' => 'Questions',
            'content' => ['include_unassigned' => false],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'faq',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        Faq::query()->create([
            'question' => 'Wellness journey question?',
            'answer' => 'Wellness answer.',
            'page_slug' => 'wellness-journey',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        Faq::query()->create([
            'question' => 'Contact-only question?',
            'answer' => 'Should not appear.',
            'page_slug' => 'contact',
            'sort_order' => 2,
            'is_published' => true,
        ]);

        $this->get(route('wellness-journey'))
            ->assertOk()
            ->assertSee('Wellness journey question?')
            ->assertDontSee('Contact-only question?');
    }

    public function test_no_support_pathways_fallback_accordion_without_teaser_section(): void
    {
        $page = Page::query()->create([
            'slug' => 'support-pathways',
            'title' => 'Support Pathways',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Hero only',
            'section_type' => 'hero',
            'heading' => 'Support Pathways Hero',
            'content' => ['design_variant' => 'minimal'],
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

        SupportPathway::query()->create([
            'slug' => 'hidden-pathway',
            'title' => 'Hidden Pathway Fallback Test',
            'intro' => 'Should not render without teaser section.',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('support-pathways'))
            ->assertOk()
            ->assertSee('Support Pathways Hero')
            ->assertDontSee('Should not render without teaser section.');
    }

    public function test_cms_image_appends_cache_busting_version_for_stored_files(): void
    {
        Storage::fake('public');
        $path = 'cms/branding/versioned.png';
        Storage::disk('public')->put($path, 'image');

        $url = CmsImage::url($path);

        $this->assertNotNull($url);
        $this->assertStringContainsString('v=', $url);
    }

    public function test_pathway_accordion_respects_section_layout_background(): void
    {
        $page = Page::query()->create([
            'slug' => 'support-pathways',
            'title' => 'Support Pathways',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Pathways accordion',
            'section_type' => 'pathways_teaser',
            'heading' => 'Explore pathways',
            'content' => ['design_variant' => 'accordion'],
            'layout' => ['background' => 'dusty_blue'],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'pathways_teaser',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        SupportPathway::query()->create([
            'slug' => 'accordion-pathway',
            'title' => 'Accordion Pathway',
            'intro' => 'Accordion intro',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('support-pathways'))
            ->assertOk()
            ->assertSee('bg-hw-dusty-blue-light/40', false)
            ->assertSee('Accordion Pathway');
    }
}
