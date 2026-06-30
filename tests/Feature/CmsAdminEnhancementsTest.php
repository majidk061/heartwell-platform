<?php

namespace Tests\Feature;

use App\Domains\Content\Actions\PublishContentAction;
use App\Domains\Content\Actions\RestoreContentRevisionAction;
use App\Domains\Content\Actions\SaveContentRevisionAction;
use App\Domains\Content\Actions\ShowPageAction;
use App\Domains\Content\Actions\UnpublishContentAction;
use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\ContentRevision;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\SectionTemplate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsAdminEnhancementsTest extends TestCase
{
    use RefreshDatabase;

    public function test_draft_page_is_not_returned_by_public_show_action(): void
    {
        Page::query()->create([
            'slug' => 'draft-only',
            'title' => 'Draft Only',
            'status' => ContentStatus::Draft,
            'is_published' => false,
            'sort_order' => 99,
        ]);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        app(ShowPageAction::class)->execute('draft-only');
    }

    public function test_preview_route_requires_authentication(): void
    {
        Page::query()->create([
            'slug' => 'preview-me',
            'title' => 'Preview Me',
            'status' => ContentStatus::Draft,
            'is_published' => false,
            'sort_order' => 1,
        ]);

        $this->get(route('admin.preview.page', ['slug' => 'preview-me']))
            ->assertRedirect();
    }

    public function test_revision_restore_updates_template_heading_and_creates_before_restore_revision(): void
    {
        $user = User::factory()->create();

        $template = SectionTemplate::query()->create([
            'name' => 'Test hero',
            'section_type' => 'hero',
            'heading' => 'Version one',
            'content' => ['body' => 'Body one'],
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $revision = ContentRevision::query()->create([
            'revisable_type' => $template->getMorphClass(),
            'revisable_id' => $template->id,
            'user_id' => $user->id,
            'snapshot' => $template->toRevisionSnapshot(),
            'created_at' => now()->subMinute(),
        ]);

        $template->update(['heading' => 'Version two']);

        $this->actingAs($user);

        app(RestoreContentRevisionAction::class)->execute($revision);

        $this->assertSame('Version one', $template->fresh()->heading);
        $this->assertTrue(
            ContentRevision::query()
                ->where('revisable_id', $template->id)
                ->where('note', 'Before restore')
                ->exists()
        );
    }

    public function test_revision_restore_updates_page_title(): void
    {
        $user = User::factory()->create();

        $page = Page::query()->create([
            'slug' => 'restore-me',
            'title' => 'Original title',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        $revision = ContentRevision::query()->create([
            'revisable_type' => $page->getMorphClass(),
            'revisable_id' => $page->id,
            'user_id' => $user->id,
            'snapshot' => $page->toRevisionSnapshot(),
            'created_at' => now()->subMinute(),
        ]);

        $page->update(['title' => 'Changed title']);

        $this->actingAs($user);

        app(RestoreContentRevisionAction::class)->execute($revision);

        $this->assertSame('Original title', $page->fresh()->title);
    }

    public function test_unpublish_hides_page_from_public_site(): void
    {
        $page = Page::query()->create([
            'slug' => 'published-then-hidden',
            'title' => 'Published Then Hidden',
            'status' => ContentStatus::Published,
            'is_published' => true,
            'sort_order' => 1,
        ]);

        app(UnpublishContentAction::class)->execute($page);

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        app(ShowPageAction::class)->execute('published-then-hidden');
    }

    public function test_publish_makes_page_publicly_visible(): void
    {
        $page = Page::query()->create([
            'slug' => 'draft-then-live',
            'title' => 'Draft Then Live',
            'status' => ContentStatus::Draft,
            'is_published' => false,
            'sort_order' => 1,
        ]);

        app(PublishContentAction::class)->execute($page);

        $result = app(ShowPageAction::class)->execute('draft-then-live');

        $this->assertSame('Draft Then Live', $result['page']->title);
    }

    public function test_section_template_save_creates_revision(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $template = SectionTemplate::query()->create([
            'name' => 'Hero block',
            'section_type' => 'hero',
            'heading' => 'Hello',
            'content' => ['body' => 'Body'],
            'status' => ContentStatus::Draft,
            'is_published' => false,
            'sort_order' => 1,
        ]);

        $template->update(['heading' => 'Updated']);
        app(SaveContentRevisionAction::class)->execute($template->fresh());

        $this->assertSame(1, ContentRevision::query()->where('revisable_id', $template->id)->count());
    }
}
