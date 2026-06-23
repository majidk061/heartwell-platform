<?php

namespace App\Filament\Resources\CRM\GroupInquiryResource\Pages;

use App\Filament\Resources\CRM\GroupInquiryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGroupInquiries extends ListRecords
{
    protected static string $resource = GroupInquiryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
