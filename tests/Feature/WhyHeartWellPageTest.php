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

    public function test_why_heartwell_page_renders_client_mock_layout(): void
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
        $response->assertSee('hw-hero--split-quotes', false);
        $response->assertSee('hw-hero--why-banner__photo', false);
        $response->assertSee('for the Moments', false);
        $response->assertSee('WHY HEARTWELL');
        $response->assertSee('hw-reflective-quotes', false);
        $response->assertSee('I slept, but I still feel tired.');
        $response->assertSee('Explore Support Pathways');
        $response->assertSee('Can Mean So Many Things', false);
        $response->assertSee('hw-editorial-bridge', false);
        $response->assertSee('hw-bridge-permission', false);
        $response->assertSee('hw-bridge-permission__headline', false);
        $response->assertSee('hw-rich-text-section--bridge', false);
        $response->assertSee('That is part of why HeartWell was created.');
        $response->assertSee('You can just start with what you are noticing.');
        $response->assertSee('hw-three-column-narrative', false);
        $response->assertSee('Why HeartWell Was Created');
        $response->assertSee('Compassion Is Not Separate from Good Care');
        $response->assertSee('Guided by Nursing Experience');
        $response->assertSee('how a person is treated while receiving it');
        $response->assertSee('hw-features-five-col', false);
        $response->assertSee('What You Can Expect from HeartWell');
        $response->assertSee('secure clinical process');
        $response->assertSee('thoughtfully planned support');
        $response->assertSee('Your needs may change, your goals may evolve');
        $response->assertSee('You Do Not Have to Have It All Figured Out');
        $response->assertSee('You do not need to arrive with the right words.');
        $response->assertSee('You just have to be willing to listen to what you are noticing.');
        $response->assertSee('id="compassion-care"', false);
        $response->assertSee('id="nursing-experience"', false);
        $response->assertSee('id="personalized-attention"', false);
        $response->assertSee('id="safe-compliant-care"', false);
        $response->assertSee('Begin with a Private Wellness Conversation');
    }
}
