<?php

namespace Tests\Feature;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Support\ClientCopyCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhyHeartWellPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_why_heartwell_page_renders_client_stack_and_anchors(): void
    {
        $page = Page::query()->create([
            'slug' => 'why-heartwell',
            'title' => 'Why HeartWell',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 4,
        ]);

        $templates = ClientCopyCatalog::sectionTemplates();
        $stack = ClientCopyCatalog::pageSectionStacks()['why-heartwell'];

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

        $response = $this->get(route('why-heartwell'));

        $response->assertOk();
        $response->assertSee('hw-page-sections--why-heartwell', false);
        $response->assertSee('hw-hero-eyebrow', false);
        $response->assertSee('Why HeartWell');
        $response->assertSee('I slept, but I still feel tired.');
        $response->assertSee('Because &ldquo;I&rsquo;m Fine&rdquo; Can Mean So Many Things', false);
        $response->assertSee('What You Can Expect from HeartWell');
        $response->assertSee('Personalized Attention');
        $response->assertSee('Begin with a Private Wellness Conversation');
        $response->assertSee('id="compassion-care"', false);
        $response->assertSee('id="nursing-experience"', false);
        $response->assertSee('id="personalized-attention"', false);
        $response->assertSee('id="safe-compliant-care"', false);
    }
}
