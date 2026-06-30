<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Concerns\HasContentStatus;
use App\Domains\Content\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Model;

class UnpublishContentAction
{
    public function execute(Model $model): Model
    {
        if (! in_array(HasContentStatus::class, class_uses_recursive($model), true)) {
            return $model;
        }

        $model->status = ContentStatus::Draft;
        $model->is_published = false;
        $model->save();

        return $model->fresh();
    }
}
