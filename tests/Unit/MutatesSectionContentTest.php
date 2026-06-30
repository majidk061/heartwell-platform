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
}
