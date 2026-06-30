<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Concerns\HasContentRevisions;
use App\Domains\Content\Models\ContentRevision;
use Illuminate\Database\Eloquent\Model;

class SaveContentRevisionAction
{
    public function execute(Model $model, ?string $note = null): ?ContentRevision
    {
        if (! in_array(HasContentRevisions::class, class_uses_recursive($model), true)) {
            return null;
        }

        if (! $model->exists) {
            return null;
        }

        $revision = ContentRevision::query()->create([
            'revisable_type' => $model->getMorphClass(),
            'revisable_id' => $model->getKey(),
            'user_id' => auth()->id(),
            'snapshot' => $model->toRevisionSnapshot(),
            'note' => $note,
            'created_at' => now(),
        ]);

        $this->pruneOldRevisions($model);

        return $revision;
    }

    protected function pruneOldRevisions(Model $model): void
    {
        $idsToKeep = ContentRevision::query()
            ->where('revisable_type', $model->getMorphClass())
            ->where('revisable_id', $model->getKey())
            ->latest('created_at')
            ->limit(config('heartwell.cms.max_revisions', 10))
            ->pluck('id');

        ContentRevision::query()
            ->where('revisable_type', $model->getMorphClass())
            ->where('revisable_id', $model->getKey())
            ->whereNotIn('id', $idsToKeep)
            ->delete();
    }
}
