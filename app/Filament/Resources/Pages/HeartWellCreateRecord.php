<?php

namespace App\Filament\Resources\Pages;

use App\Filament\Concerns\HasHeartWellNavigation;
use Filament\Resources\Pages\CreateRecord;

abstract class HeartWellCreateRecord extends CreateRecord
{
    use HasHeartWellNavigation;
}
