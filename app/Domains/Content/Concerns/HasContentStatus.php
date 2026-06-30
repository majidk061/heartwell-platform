<?php

namespace App\Domains\Content\Concerns;

use App\Domains\Content\Enums\ContentStatus;
use Illuminate\Database\Eloquent\Builder;

trait HasContentStatus
{
    public static function bootHasContentStatus(): void
    {
        static::saving(function (self $model): void {
            if ($model->isDirty('status')) {
                $status = $model->status instanceof ContentStatus
                    ? $model->status
                    : ContentStatus::tryFrom((string) $model->status);

                if ($status) {
                    $model->is_published = $status->isPublished();
                }

                return;
            }

            if ($model->isDirty('is_published')) {
                $model->status = $model->is_published
                    ? ContentStatus::Published
                    : ContentStatus::Draft;
            }
        });
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', ContentStatus::Published->value);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', ContentStatus::Draft->value);
    }

    public function isDraft(): bool
    {
        $status = $this->status;

        if ($status instanceof ContentStatus) {
            return $status === ContentStatus::Draft;
        }

        return ($status ?? ContentStatus::Published->value) === ContentStatus::Draft->value;
    }

    public function isPublishedStatus(): bool
    {
        return ! $this->isDraft();
    }
}
