<?php

namespace App\Filament\Resources\Content\AvatarCardResource\Pages;

use App\Filament\Resources\Content\AvatarCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAvatarCard extends EditRecord
{
    protected static string $resource = AvatarCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
