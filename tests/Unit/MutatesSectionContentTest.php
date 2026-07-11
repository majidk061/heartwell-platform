<?php

namespace Tests\Unit;

use App\Filament\Resources\Content\SectionTemplateResource;
use PHPUnit\Framework\TestCase;

class MutatesSectionContentTest extends TestCase
{
    public function test_avatar_intro_hydrate_does_not_pass_string_to_repeater_columns(): void
    {
        $data = SectionTemplateResource::hydrateTemplateData([
            'section_type' => 'avatar_intro',
            'content' => [
                'columns' => '3',
                'unifying_message' => 'Test message',
            ],
        ]);

        $this->assertSame([], $data['content_columns']);
        $this->assertSame('3', $data['content_avatar_columns']);
    }

    public function test_avatar_intro_mutate_persists_card_columns(): void
    {
        $data = SectionTemplateResource::mutateTemplateData([
            'section_type' => 'avatar_intro',
            'content_avatar_columns' => '2',
            'content_unifying_message' => 'Hello',
        ]);

        $this->assertSame('2', $data['content']['card_columns']);
        $this->assertArrayNotHasKey('columns', $data['content']);
    }

    public function test_journey_split_hero_fields_persist_when_layout_changes_with_existing_content(): void
    {
        $existing = [
            'design_variant' => 'journey_split_hero',
            'eyebrow' => 'The HeartWell Wellness Journey',
            'hero_title' => 'Your Wellness Journey Can Begin with One Simple Question',
            'lead_question' => 'What have you been noticing?',
            'body' => 'Intro copy.',
            'image_url' => 'cms/sections/wellness-journey-hero-desktop.png',
            'quotes' => [['text' => 'Quote one']],
            'layout' => ['container_width' => 'default'],
        ];

        $data = SectionTemplateResource::mutateTemplateData([
            'section_type' => 'hero',
            'heading' => 'Your Wellness Journey Can Begin with One Simple Question',
            'content' => $existing,
            'content_body' => 'Intro copy.',
            'content_design_variant' => 'journey_split_hero',
            'layout_container_width' => 'extra_wide',
        ]);

        $this->assertSame('The HeartWell Wellness Journey', $data['content']['eyebrow']);
        $this->assertSame('Your Wellness Journey Can Begin with One Simple Question', $data['content']['hero_title']);
        $this->assertSame('What have you been noticing?', $data['content']['lead_question']);
        $this->assertSame('extra_wide', $data['content']['layout']['container_width']);
        $this->assertSame('Quote one', $data['content']['quotes'][0]['text']);
    }
}
