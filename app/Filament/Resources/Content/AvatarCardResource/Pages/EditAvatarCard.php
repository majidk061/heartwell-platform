<?php

namespace App\Filament\Resources\Content\AvatarCardResource\Pages;

use App\Filament\Resources\Content\AvatarCardResource;
use App\Filament\Resources\Pages\HeartWellEditRecord;

class EditAvatarCard extends HeartWellEditRecord
{
    use \App\Filament\Concerns\HandlesCmsImageUploads;

    protected static string $resource = AvatarCardResource::class;
}
