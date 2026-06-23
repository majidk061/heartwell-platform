<?php

namespace App\Filament\Resources\Automation\AutomationRuleResource\Pages;

use App\Filament\Resources\Automation\AutomationRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAutomationRules extends ListRecords
{
    protected static string $resource = AutomationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
