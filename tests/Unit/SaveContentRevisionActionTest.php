<?php

namespace Tests\Unit;

use App\Domains\Content\Actions\SaveContentRevisionAction;
use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\ContentRevision;
use App\Domains\Content\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SaveContentRevisionActionTest extends TestCase
{
    use RefreshDatabase;

    public function test_prunes_revisions_beyond_configured_maximum(): void
    {
        config(['heartwell.cms.max_revisions' => 10]);

        $page = Page::query()->create([
            'slug' => 'revision-test',
            'title' => 'Revision Test',
            'status' => ContentStatus::Draft,
            'is_published' => false,
            'sort_order' => 1,
        ]);

        $action = app(SaveContentRevisionAction::class);

        for ($i = 1; $i <= 12; $i++) {
            $page->update(['title' => "Title {$i}"]);
            $action->execute($page->fresh());
        }

        $count = ContentRevision::query()
            ->where('revisable_type', $page->getMorphClass())
            ->where('revisable_id', $page->id)
            ->count();

        $this->assertSame(10, $count);
    }
}
