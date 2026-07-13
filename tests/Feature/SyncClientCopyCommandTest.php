<?php

namespace Tests\Feature;

use App\Domains\Content\Models\Faq;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Models\SiteSetting;
use App\Domains\Content\Models\SupportPathway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SyncClientCopyCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_sync_command_updates_pathways_without_deleting_page_sections(): void
    {
        $page = Page::query()->create([
            'slug' => 'support-pathways',
            'title' => 'Support Pathways',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Hero — support pathways',
            'section_type' => 'hero',
            'heading' => 'Old heading',
            'content' => ['body' => 'Old body'],
            'is_published' => true,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => 'hero',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        SupportPathway::query()->create([
            'slug' => 'advanced-cellular',
            'title' => 'Advanced Cellular Support',
            'intro' => 'Old intro',
            'sort_order' => 4,
            'is_published' => true,
        ]);

        $this->artisan('heartwell:sync-client-copy')
            ->assertSuccessful();

        $this->assertDatabaseHas('content_section_templates', [
            'name' => 'Hero — support pathways',
            'heading' => 'Support Pathways',
        ]);

        $this->assertDatabaseHas('content_support_pathways', [
            'slug' => 'individualized-collaborative-care',
            'title' => 'Individualized & Collaborative Care',
            'short_title' => 'Individualized',
        ]);

        $this->assertDatabaseMissing('content_support_pathways', [
            'slug' => 'specialized-support',
        ]);

        $this->assertDatabaseMissing('content_support_pathways', [
            'slug' => 'advanced-cellular',
        ]);

        $this->assertSame(1, PageSection::query()->where('page_id', $page->id)->count());
    }

    public function test_attach_missing_sections_appends_without_reordering_existing(): void
    {
        $page = Page::query()->create([
            'slug' => 'support-pathways',
            'title' => 'Support Pathways',
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $existing = SectionTemplate::query()->create([
            'name' => 'Hero — support pathways',
            'section_type' => 'hero',
            'heading' => 'Support Pathways',
            'content' => [],
            'is_published' => true,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $existing->id,
            'section_type' => 'hero',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->artisan('heartwell:sync-client-copy', [
            '--attach-missing-sections' => 'support-pathways',
        ])->assertSuccessful();

        $this->assertGreaterThan(1, PageSection::query()->where('page_id', $page->id)->count());

        $first = PageSection::query()
            ->where('page_id', $page->id)
            ->orderBy('sort_order')
            ->first();

        $this->assertSame($existing->id, $first?->section_template_id);
    }

    public function test_sync_preserves_local_section_image_when_catalog_omits_image_url(): void
    {
        SectionTemplate::query()->create([
            'name' => 'Founder teaser — full page',
            'section_type' => 'founder_teaser',
            'heading' => 'Meet the Founder',
            'content' => [
                'body' => 'Bio copy',
                'image_url' => 'cms/sections/founder-jacquie.png',
            ],
            'is_published' => true,
        ]);

        $this->artisan('heartwell:sync-client-copy')
            ->assertSuccessful();

        $template = SectionTemplate::query()->where('name', 'Founder teaser — full page')->first();

        $this->assertSame(
            'cms/sections/founder-jacquie.png',
            $template?->content['image_url'] ?? null
        );
    }

    public function test_sync_preserves_design_variant_when_catalog_omits_it(): void
    {
        SectionTemplate::query()->create([
            'name' => 'Hero — inner page',
            'section_type' => 'hero',
            'heading' => 'Custom Page',
            'content' => [
                'design_variant' => 'centered_overlay',
                'body' => 'Custom hero copy',
            ],
            'is_published' => true,
        ]);

        $this->artisan('heartwell:sync-client-copy')
            ->assertSuccessful();

        $template = SectionTemplate::query()->where('name', 'Hero — inner page')->first();

        $this->assertSame('centered_overlay', $template?->content['design_variant'] ?? null);
    }

    public function test_sync_sets_split_hero_for_why_heartwell_in_catalog(): void
    {
        $this->artisan('heartwell:sync-client-copy')
            ->assertSuccessful();

        $template = SectionTemplate::query()->where('name', 'Hero — why heartwell')->first();

        $this->assertSame('split_image_quotes', $template?->content['design_variant'] ?? null);
        $this->assertSame('WHY HEARTWELL', $template?->content['eyebrow'] ?? null);
        $this->assertSame('Explore Support Pathways', $template?->content['primary_label'] ?? null);
    }

    public function test_sync_updates_site_settings_navigation_and_ctas(): void
    {
        SiteSetting::query()->create([
            'key' => 'navigation',
            'value' => [['label' => 'Old Nav', 'route' => 'home']],
        ]);

        $this->artisan('heartwell:sync-client-copy')
            ->assertSuccessful();

        $navigation = SiteSetting::query()->where('key', 'navigation')->value('value');

        $this->assertIsArray($navigation);
        $this->assertContains('Meet Jacquie', collect($navigation)->pluck('label')->all());
        $this->assertNotContains('Wellness Journey', collect($navigation)->pluck('label')->all());

        $ctas = SiteSetting::query()->where('key', 'ctas')->value('value');
        $this->assertSame('Request a Private Mobile Visit', $ctas['primary']['label'] ?? null);
    }

    public function test_sync_replaces_wellness_journey_faqs_with_eight_final_questions(): void
    {
        Faq::query()->create([
            'question' => 'Placeholder FAQ question?',
            'answer' => 'Placeholder answer.',
            'page_slug' => 'wellness-journey',
            'sort_order' => 99,
            'is_published' => true,
        ]);

        $this->artisan('heartwell:sync-client-copy')
            ->assertSuccessful();

        $this->assertSame(8, Faq::query()->where('page_slug', 'wellness-journey')->count());
        $this->assertDatabaseHas('content_faqs', [
            'question' => 'What happens during a Private Wellness Conversation?',
        ]);
        $this->assertDatabaseMissing('content_faqs', [
            'question' => 'Placeholder FAQ question?',
        ]);
    }

    public function test_sync_creates_home_full_bleed_overlay_template_without_forcing_variant(): void
    {
        SectionTemplate::query()->create([
            'name' => 'Hero — full bleed overlay',
            'section_type' => 'hero',
            'heading' => 'Custom Home Hero',
            'content' => [
                'design_variant' => 'full_bleed_overlay',
                'intro_question' => 'Old question?',
            ],
            'is_published' => true,
        ]);

        $this->artisan('heartwell:sync-client-copy')
            ->assertSuccessful();

        $template = SectionTemplate::query()->where('name', 'Hero — full bleed overlay')->first();

        $this->assertSame('full_bleed_overlay', $template?->content['design_variant'] ?? null);
        $this->assertSame(
            'Feeling exhausted? Stuck? Not feeling like yourself?',
            $template?->content['intro_question'] ?? null
        );
    }
}
