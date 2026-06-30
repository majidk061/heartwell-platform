<?php

namespace App\Filament\Resources\Content\PageResource\Pages;

use App\Filament\Concerns\ConfiguresContentTableFilters;
use App\Filament\Resources\Content\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPages extends ListRecords
{
    use ConfiguresContentTableFilters;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return static::contentStatusTabs();
    }
}
