<?php

namespace App\Filament\Resources\Automation\AutomationRuleResource\Pages;

use App\Filament\Resources\Automation\AutomationRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAutomationRule extends EditRecord
{
    protected static string $resource = AutomationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
