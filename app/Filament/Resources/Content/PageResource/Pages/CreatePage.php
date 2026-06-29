<?php

namespace App\Filament\Resources\Content\PageResource\Pages;

use App\Filament\Resources\Content\PageResource;
use Filament\Actions;
use App\Filament\Resources\Pages\HeartWellCreateRecord;

class CreatePage extends HeartWellCreateRecord
{
    protected static string $resource = PageResource::class;
}
