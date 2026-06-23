<?php

namespace App\Filament\Resources\Content\AvatarCardResource\Pages;

use App\Filament\Resources\Content\AvatarCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAvatarCards extends ListRecords
{
    protected static string $resource = AvatarCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
