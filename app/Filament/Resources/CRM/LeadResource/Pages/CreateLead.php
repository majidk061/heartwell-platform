<?php

namespace App\Filament\Resources\CRM\LeadResource\Pages;

use App\Domains\CRM\Events\LeadCreated;
use App\Filament\Resources\CRM\LeadResource;
use App\Filament\Resources\Pages\HeartWellCreateRecord;

class CreateLead extends HeartWellCreateRecord
{
    protected static string $resource = LeadResource::class;

    protected function afterCreate(): void
    {
        LeadCreated::dispatch($this->record);
    }
}
