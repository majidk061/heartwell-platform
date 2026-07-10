<?php

namespace Tests\Feature;

use App\Domains\Content\Models\SupportPathway;
use App\Domains\Content\Support\ClientCopyCatalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PathwayRenameAndImagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_pathway_compact_title_uses_short_title_when_present(): void
    {
        $pathway = SupportPathway::query()->create([
            'slug' => 'individualized-collaborative-care',
            'title' => 'Individualized & Collaborative Care',
            'short_title' => 'Individualized',
            'intro' => 'Intro copy',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $this->assertSame('Individualized & Collaborative Care', $pathway->displayTitle());
        $this->assertSame('Individualized', $pathway->displayTitle(compact: true));
    }

    public function test_sync_maps_client_image_paths_for_all_pathways(): void
    {
        $this->artisan('heartwell:sync-client-copy')
            ->assertSuccessful();

        foreach (ClientCopyCatalog::pathwayImagePaths() as $slug => $imagePath) {
            $this->assertDatabaseHas('content_support_pathways', [
                'slug' => $slug,
                'image_path' => $imagePath,
            ]);
        }
    }
}
