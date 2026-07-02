<?php

namespace Tests\Feature;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Faq;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Models\SupportPathway;
use App\Domains\Content\Support\ClientCopyCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportPathwaysPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_support_pathways_page_renders_client_copy_and_pathway_cards(): void
    {
        $page = Page::query()->create([
            'slug' => 'support-pathways',
            'title' => 'Support Pathways',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 2,
        ]);

        $templates = [
            ['name' => 'Hero — support pathways', 'type' => 'hero', 'heading' => 'Support Pathways', 'content' => ClientCopyCatalog::sectionTemplates()['Hero — support pathways']['content']],
            ['name' => 'Intro — clinical intake clearance', 'type' => 'intro', 'heading' => 'Required Clinical Intake & Clearance', 'content' => ClientCopyCatalog::sectionTemplates()['Intro — clinical intake clearance']['content']],
            ['name' => 'Pathways teaser — guided cards', 'type' => 'pathways_teaser', 'heading' => null, 'content' => ClientCopyCatalog::sectionTemplates()['Pathways teaser — guided cards']['content']],
            ['name' => 'Journey — Hydreight portal flow', 'type' => 'journey', 'heading' => 'What Happens After You Choose a Pathway', 'content' => ClientCopyCatalog::sectionTemplates()['Journey — Hydreight portal flow']['content']],
        ];

        foreach ($templates as $index => $templateData) {
            $template = SectionTemplate::query()->create([
                'name' => $templateData['name'],
                'section_type' => $templateData['type'],
                'heading' => $templateData['heading'],
                'content' => $templateData['content'],
                'layout' => ['container_width' => 'default', 'background' => 'white'],
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

        foreach (ClientCopyCatalog::supportPathways() as $index => $pathway) {
            unset($pathway['migrate_from_slug']);
            SupportPathway::query()->create(array_merge($pathway, [
                'sort_order' => $index + 1,
                'is_published' => true,
            ]));
        }

        $response = $this->get(route('support-pathways'));

        $response->assertOk();
        $response->assertSee('Thoughtful Wellness Support, Guided by Your Goals');
        $response->assertSee('Required Clinical Intake');
        $response->assertSee('New Jersey medical regulations');
        $response->assertSee('Precision Glow Therapy');
        $response->assertSee('Specialized Support');
        $response->assertSee('What you may see in the secure medical intake portal');
        $response->assertSee('Secure Hydreight Portal');
        $response->assertDontSee('Confidence & Aesthetic Support');
        $response->assertDontSee('Advanced Cellular Support');
    }
}
