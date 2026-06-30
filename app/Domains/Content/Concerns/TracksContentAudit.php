<?php

namespace App\Domains\Content\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait TracksContentAudit
{
    public static function bootTracksContentAudit(): void
    {
        static::creating(function (self $model): void {
            if (auth()->check()) {
                $model->created_by_id ??= auth()->id();
                $model->updated_by_id = auth()->id();
            }
        });

        static::updating(function (self $model): void {
            if (auth()->check()) {
                $model->updated_by_id = auth()->id();
            }
        });
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_id');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_id');
    }
}
