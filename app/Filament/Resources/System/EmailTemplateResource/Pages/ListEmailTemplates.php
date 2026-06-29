<?php

namespace App\Filament\Resources\System\EmailTemplateResource\Pages;

use App\Filament\Resources\System\EmailTemplateResource;
use Filament\Resources\Pages\ListRecords;

class ListEmailTemplates extends ListRecords
{
    protected static string $resource = EmailTemplateResource::class;
}
