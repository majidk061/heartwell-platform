<?php

namespace App\Domains\Content\Concerns;

use App\Domains\Content\Models\ContentRevision;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasContentRevisions
{
    public function revisions(): MorphMany
    {
        return $this->morphMany(ContentRevision::class, 'revisable')->latest('created_at');
    }

    /**
     * @return array<string, mixed>
     */
    public function toRevisionSnapshot(): array
    {
        return $this->only($this->revisionSnapshotAttributes());
    }

    /**
     * @return list<string>
     */
    public function revisionSnapshotAttributes(): array
    {
        return array_values(array_diff(
            $this->getFillable(),
            ['created_by_id', 'updated_by_id'],
        ));
    }
}
