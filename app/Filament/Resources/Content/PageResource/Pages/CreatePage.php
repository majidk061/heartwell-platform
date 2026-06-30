<?php

namespace App\Filament\Resources\Content\PageResource\Pages;

use App\Filament\Concerns\HandlesCmsImageUploads;
use App\Filament\Concerns\HasContentPublishingActions;
use App\Filament\Resources\Content\PageResource;
use App\Filament\Resources\Pages\HeartWellCreateRecord;

class CreatePage extends HeartWellCreateRecord
{
    use HandlesCmsImageUploads;
    use HasContentPublishingActions;

    protected static string $resource = PageResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data = $this->normalizeCmsImageFields($data, ['og_image']);

        return $this->applyPendingContentStatus($data);
    }
}
