<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Concerns\HasContentRevisions;
use App\Domains\Content\Exceptions\ContentRevisionRestoreException;
use App\Domains\Content\Models\ContentRevision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RestoreContentRevisionAction
{
    public function execute(ContentRevision $revision): Model
    {
        /** @var (Model&HasContentRevisions)|null $model */
        $model = $revision->revisable;

        if ($model === null) {
            throw new ContentRevisionRestoreException('The content this revision belonged to no longer exists.');
        }

        return DB::transaction(function () use ($revision, $model): Model {
            app(SaveContentRevisionAction::class)->execute($model, 'Before restore');

            $snapshot = $revision->snapshot ?? [];
            $attributes = array_intersect_key(
                $snapshot,
                array_flip($model->revisionSnapshotAttributes()),
            );

            $attributes = array_filter(
                $attributes,
                static fn (mixed $value): bool => $value !== null,
            );

            $model->fill($attributes);
            $model->save();

            return $model->fresh();
        });
    }
}
