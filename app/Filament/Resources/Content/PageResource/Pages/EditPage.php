<?php

namespace App\Filament\Resources\Content\PageResource\Pages;

use App\Filament\Concerns\HandlesCmsImageUploads;
use App\Filament\Concerns\HasContentPublishingActions;
use App\Filament\Resources\Content\PageResource;
use App\Filament\Resources\Pages\HeartWellEditRecord;

class EditPage extends HeartWellEditRecord
{
    use HandlesCmsImageUploads;
    use HasContentPublishingActions;

    protected static string $resource = PageResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->hydrateCmsImageFields($data, ['og_image']);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data = $this->normalizeCmsImageFields($data, ['og_image']);

        return $this->applyPendingContentStatus($data);
    }
}
