<?php

namespace Tests\Feature;

use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
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
            'slug' => 'specialized-support',
            'title' => 'Specialized Support',
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
}
