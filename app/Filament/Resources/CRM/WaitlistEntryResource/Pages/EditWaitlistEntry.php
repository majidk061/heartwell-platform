<?php

namespace App\Filament\Resources\CRM\WaitlistEntryResource\Pages;

use App\Filament\Resources\CRM\WaitlistEntryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWaitlistEntry extends EditRecord
{
    protected static string $resource = WaitlistEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
