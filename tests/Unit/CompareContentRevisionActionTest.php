<?php

namespace Tests\Unit;

use App\Domains\Content\Actions\CompareContentRevisionAction;
use App\Domains\Content\Models\ContentRevision;
use Tests\TestCase;

class CompareContentRevisionActionTest extends TestCase
{
    public function test_summarizes_field_changes_against_previous_revision(): void
    {
        $previous = new ContentRevision([
            'snapshot' => [
                'title' => 'Old title',
                'status' => 'draft',
            ],
        ]);

        $current = new ContentRevision([
            'snapshot' => [
                'title' => 'New title',
                'status' => 'published',
            ],
        ]);

        $changes = app(CompareContentRevisionAction::class)->execute($current, $previous);

        $this->assertContains('Page title: "Old title" → "New title"', $changes);
        $this->assertContains('Status: "draft" → "published"', $changes);
    }

    public function test_marks_first_revision_as_initial_version(): void
    {
        $current = new ContentRevision([
            'snapshot' => ['title' => 'First'],
        ]);

        $changes = app(CompareContentRevisionAction::class)->execute($current, null);

        $this->assertSame(['Initial version'], $changes);
    }
}
