<?php

namespace App\Filament\Resources\CRM\WaitlistEntryResource\Pages;

use App\Filament\Resources\CRM\WaitlistEntryResource;
use Filament\Actions;
use App\Filament\Resources\Pages\HeartWellCreateRecord;

class CreateWaitlistEntry extends HeartWellCreateRecord
{
    protected static string $resource = WaitlistEntryResource::class;
}
