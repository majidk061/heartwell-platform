<?php

namespace Tests\Feature;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Support\ResolvedPageSection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CtaSectionLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_cta_section_renders_admin_container_width_from_layout_column(): void
    {
        Page::query()->create([
            'slug' => 'support-pathways',
            'title' => 'Support Pathways',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'CTA — support pathways',
            'section_type' => 'cta',
            'heading' => 'Find the pathway that fits you',
            'content' => [
                'body' => 'Not sure where to start?',
                'variant' => 'dual',
            ],
            'layout' => [
                'container_width' => 'extra_wide',
                'background' => 'dusty_blue',
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => Page::query()->where('slug', 'support-pathways')->value('id'),
            'section_template_id' => $template->id,
            'section_type' => 'cta',
            'sort_order' => 99,
            'is_published' => true,
        ]);

        $this->get(route('support-pathways'))
            ->assertOk()
            ->assertSee('hw-cta-inline-bar', false)
            ->assertSee('hw-container-extra-wide', false)
            ->assertSee('Find the pathway that fits you');
    }

    public function test_resolved_template_merges_layout_column_into_content(): void
    {
        $template = SectionTemplate::query()->create([
            'name' => 'CTA layout merge',
            'section_type' => 'cta',
            'heading' => 'CTA',
            'content' => ['variant' => 'dual'],
            'layout' => ['container_width' => 'wide'],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        $merged = ResolvedPageSection::mergeTemplateContent($template);

        $this->assertSame('wide', $merged['layout']['container_width'] ?? null);
    }

    public function test_split_guidance_cta_variant_renders_editorial_layout(): void
    {
        Page::query()->create([
            'slug' => 'support-pathways',
            'title' => 'Support Pathways',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'CTA split',
            'section_type' => 'cta',
            'heading' => 'Find the pathway that fits you',
            'content' => [
                'variant' => 'dual',
                'design_variant' => 'split_guidance',
                'body' => 'Not sure where to start?',
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => Page::query()->where('slug', 'support-pathways')->value('id'),
            'section_template_id' => $template->id,
            'section_type' => 'cta',
            'sort_order' => 99,
            'is_published' => true,
        ]);

        $this->get(route('support-pathways'))
            ->assertOk()
            ->assertSee('hw-cta-split', false)
            ->assertSee('Ready when you are');
    }
}
