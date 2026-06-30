<?php

namespace Tests\Feature;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Support\SectionDesignRegistry;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class SectionPreviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_section_preview_requires_authentication(): void
    {
        $template = SectionTemplate::query()->create([
            'name' => 'Preview hero',
            'section_type' => 'hero',
            'heading' => 'Test',
            'content' => ['design_variant' => 'minimal'],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        $this->get(route('admin.preview.section', ['template' => $template->id]))
            ->assertRedirect();
    }

    public function test_section_preview_renders_template(): void
    {
        Page::query()->create([
            'slug' => 'home',
            'title' => 'Home',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $this->seed(PermissionSeeder::class);
        $user = User::factory()->create();
        $user->givePermissionTo(Permission::findByName('content.pages.view', 'web'));
        $template = SectionTemplate::query()->create([
            'name' => 'Preview hero',
            'section_type' => 'hero',
            'heading' => 'Preview Headline',
            'content' => [
                'design_variant' => 'minimal',
                'body' => 'Preview body copy.',
            ],
            'is_published' => true,
            'status' => ContentStatus::Published,
        ]);

        $this->actingAs($user)
            ->get(route('admin.preview.section', ['template' => $template->id]))
            ->assertOk()
            ->assertSee('Preview Headline')
            ->assertSee('Section preview');
    }

    public function test_design_registry_resolves_hero_variants(): void
    {
        $variants = SectionDesignRegistry::variantsFor('hero');

        $this->assertArrayHasKey('split_image_right', $variants);
        $this->assertSame('default', SectionDesignRegistry::resolveVariant('hero', []));
        $this->assertSame('split_image_right', SectionDesignRegistry::resolveVariant('hero', [
            'design_variant' => 'split_image_right',
        ]));
    }
}
