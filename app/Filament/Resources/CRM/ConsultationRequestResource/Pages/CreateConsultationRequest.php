<?php

namespace App\Filament\Resources\CRM\ConsultationRequestResource\Pages;

use App\Filament\Resources\CRM\ConsultationRequestResource;
use Filament\Actions;
use App\Filament\Resources\Pages\HeartWellCreateRecord;

class CreateConsultationRequest extends HeartWellCreateRecord
{
    protected static string $resource = ConsultationRequestResource::class;
}
