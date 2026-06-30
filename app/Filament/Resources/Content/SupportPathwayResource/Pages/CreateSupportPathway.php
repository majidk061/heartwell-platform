<?php

namespace App\Filament\Resources\Content\SupportPathwayResource\Pages;

use App\Filament\Resources\Content\SupportPathwayResource;
use App\Filament\Resources\Pages\HeartWellCreateRecord;

class CreateSupportPathway extends HeartWellCreateRecord
{
    use \App\Filament\Concerns\HandlesCmsImageUploads;

    protected static string $resource = SupportPathwayResource::class;
}
