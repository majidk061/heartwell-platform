<?php

namespace App\Filament\Resources\Content\SupportPathwayResource\Pages;

use App\Filament\Resources\Content\SupportPathwayResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSupportPathways extends ListRecords
{
    protected static string $resource = SupportPathwayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
