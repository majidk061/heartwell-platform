<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasHeartWellNavigation;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

abstract class HeartWellEditRecord extends EditRecord
{
    use HasHeartWellNavigation;

    protected function getHeaderActions(): array
    {
        return array_merge(
            $this->getHeaderActionsForEdit(),
            [Actions\DeleteAction::make()],
        );
    }
}
