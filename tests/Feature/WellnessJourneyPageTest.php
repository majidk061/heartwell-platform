<?php

namespace Tests\Feature;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Support\ClientCopyCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WellnessJourneyPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_wellness_journey_page_renders_guided_flow_and_pathway_grid(): void
    {
        $page = Page::query()->create([
            'slug' => 'wellness-journey',
            'title' => 'Wellness Journey',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 5,
        ]);

        $templates = ClientCopyCatalog::sectionTemplates();
        $stack = ClientCopyCatalog::pageSectionStacks()['wellness-journey'];

        foreach ($stack as $index => $templateName) {
            $definition = $templates[$templateName];
            $template = SectionTemplate::query()->create([
                'name' => $templateName,
                'section_type' => $definition['section_type'],
                'heading' => $definition['heading'] ?? null,
                'content' => $definition['content'],
                'layout' => $definition['content']['layout'] ?? ['container_width' => 'default', 'background' => 'white'],
                'is_published' => true,
                'status' => ContentStatus::Published,
            ]);

            PageSection::query()->create([
                'page_id' => $page->id,
                'section_template_id' => $template->id,
                'section_type' => $template->section_type,
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);
        }

        $response = $this->get(route('wellness-journey'));

        $response->assertOk();
        $response->assertSee('hw-page-sections--wellness-journey', false);
        $response->assertSee('hw-wj-hero', false);
        $response->assertSee('The HeartWell Wellness Journey');
        $response->assertSee('Your Wellness Journey Can Begin with One Simple Question');
        $response->assertSee('What have you been noticing?');
        $response->assertSee('The journey is designed to help you move from uncertainty toward a clearer next step.');
        $response->assertSee('Step 3 — Explore Where Support May Begin');
        $response->assertSee('Individualized & Collaborative Care');
        $response->assertSee('Precision Glow Therapy');
        $response->assertSee('hw-wj-pathways', false);
        $response->assertSee("Step 4 — Choose How You'd Like to Begin");
        $response->assertSee('Begin with a Private Wellness Conversation');
        $response->assertSee('Request a Private Mobile Visit');
        $response->assertSee('hw-wj-dual-start', false);
        $response->assertSee('What You Can Expect:', false);
        $response->assertSee('hw-wj-expect-split', false);
        $response->assertSee('You Deserve to Feel Like Yourself Again');
        $response->assertSee('hw-wj-cta-split', false);
    }
}
