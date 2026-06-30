<?php

namespace App\Filament\Resources\Content\SectionTemplateResource\Pages;

use App\Filament\Concerns\ConfiguresContentTableFilters;
use App\Filament\Resources\Content\SectionTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSectionTemplates extends ListRecords
{
    use ConfiguresContentTableFilters;

    protected static string $resource = SectionTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return static::sectionTemplateUsageTabs();
    }
}
