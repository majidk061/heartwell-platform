<?php

namespace App\Filament\Resources\Content\FaqResource\Pages;

use App\Filament\Resources\Content\FaqResource;
use Filament\Actions;
use App\Filament\Resources\Pages\HeartWellCreateRecord;

class CreateFaq extends HeartWellCreateRecord
{
    protected static string $resource = FaqResource::class;
}
