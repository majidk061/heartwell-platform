<?php

namespace App\Filament\Resources\System\EmailTemplateResource\Pages;

use App\Filament\Concerns\HandlesCmsImageUploads;
use App\Filament\Resources\Pages\HeartWellEditRecord;
use App\Filament\Resources\System\EmailTemplateResource;

class EditEmailTemplate extends HeartWellEditRecord
{
    use HandlesCmsImageUploads;

    protected static string $resource = EmailTemplateResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->hydrateCmsImageFields($data, ['logo_path']);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->normalizeCmsImageFields($data, ['logo_path']);
    }
}
