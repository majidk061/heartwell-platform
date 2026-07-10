<?php

namespace Tests\Feature;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SectionTypeLayoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @return array<string, array{0: string, 1: string, 2: string, 3: string}>
     */
    public static function sectionVariantCases(): array
    {
        return [
            'journey default' => ['journey', 'default', 'hw-journey-horizontal', 'Step One'],
            'journey vertical_timeline' => ['journey', 'vertical_timeline', 'hw-journey-vertical', 'Timeline Step'],
            'features default' => ['features', 'default', 'hw-features-grid', 'Grid Feature'],
            'features two_column' => ['features', 'two_column', 'hw-features-two-col', 'Two Col Feature'],
            'rich_text default' => ['rich_text', 'default', 'prose prose-hw', 'Rich text body'],
            'rich_text image_inset' => ['rich_text', 'image_inset', 'hw-rich-text-inset', 'Inset body copy'],
            'intro default' => ['intro', 'default', 'hw-container', 'Intro default copy'],
            'intro image_side' => ['intro', 'image_side', 'md:grid-cols-2', 'Intro side copy'],
            'intro image_below' => ['intro', 'image_below', 'Intro below copy', 'Intro below copy'],
        ];
    }

    #[DataProvider('sectionVariantCases')]
    public function test_section_design_variant_renders_expected_markup(
        string $sectionType,
        string $designVariant,
        string $expectedMarker,
        string $contentMarker,
    ): void {
        $page = Page::query()->create([
            'slug' => 'why-heartwell',
            'title' => 'Why HeartWell',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 3,
        ]);

        $content = match ($sectionType) {
            'journey' => [
                'design_variant' => $designVariant,
                'steps' => [
                    ['title' => $contentMarker, 'description' => 'Step description'],
                ],
            ],
            'features' => [
                'design_variant' => $designVariant,
                'features' => [
                    ['title' => $contentMarker, 'body' => 'Feature description'],
                ],
            ],
            'rich_text' => [
                'design_variant' => $designVariant,
                'body' => '<p>'.$contentMarker.'</p>',
            ],
            'intro' => [
                'design_variant' => $designVariant,
                'body' => $contentMarker,
            ],
            default => ['design_variant' => $designVariant],
        };

        $template = SectionTemplate::query()->create([
            'name' => ucfirst($sectionType).' variant test',
            'section_type' => $sectionType,
            'heading' => 'Variant heading',
            'content' => $content,
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => $sectionType,
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('why-heartwell'))
            ->assertOk()
            ->assertSee($expectedMarker, false)
            ->assertSee($contentMarker);
    }

    /**
     * @return array<string, array{0: string, 1: string, 2: string}>
     */
    public static function layoutWidthCases(): array
    {
        return [
            'journey narrow' => ['journey', 'narrow', 'hw-container-narrow'],
            'features wide' => ['features', 'wide', 'hw-container-wide'],
            'rich_text extra_wide' => ['rich_text', 'extra_wide', 'hw-container-extra-wide'],
            'rich_text editorial_bridge comfortable' => ['rich_text', 'comfortable', 'hw-bridge-permission__wrap', 'editorial_bridge'],
            'features five_column extra_wide' => ['features', 'extra_wide', 'hw-container-extra-wide', 'five_column_dividers'],
        ];
    }

    #[DataProvider('layoutWidthCases')]
    public function test_section_layout_container_width_reflects_for_type(string $sectionType, string $width, string $expectedClass, string $designVariant = 'default'): void
    {
        $page = Page::query()->create([
            'slug' => 'why-heartwell',
            'title' => 'Why HeartWell',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 3,
        ]);

        $template = SectionTemplate::query()->create([
            'name' => 'Layout width',
            'section_type' => $sectionType,
            'heading' => 'Layout width heading',
            'content' => match ($sectionType) {
                'journey' => ['steps' => [['title' => 'Step', 'description' => 'Desc']]],
                'features' => array_filter([
                    'design_variant' => $designVariant !== 'default' ? $designVariant : null,
                    'features' => [['title' => 'Feature', 'body' => 'Body']],
                ]),
                'rich_text' => array_filter([
                    'design_variant' => $designVariant !== 'default' ? $designVariant : null,
                    'intro_text' => $designVariant === 'editorial_bridge' ? 'Bridge intro' : null,
                    'accent_line' => $designVariant === 'editorial_bridge' ? 'Bridge accent' : null,
                    'headline' => $designVariant === 'editorial_bridge' ? 'Bridge headline' : null,
                    'body' => $designVariant === 'editorial_bridge' ? null : '<p>Rich text</p>',
                ]),
                default => ['body' => '<p>Rich text</p>'],
            },
            'layout' => ['container_width' => $width],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        PageSection::query()->create([
            'page_id' => $page->id,
            'section_template_id' => $template->id,
            'section_type' => $sectionType,
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->get(route('why-heartwell'))
            ->assertOk()
            ->assertSee($expectedClass, false);
    }
}
