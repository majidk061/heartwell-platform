<?php

namespace App\Filament\Resources\CRM\ConsultationRequestResource\Pages;

use App\Filament\Resources\CRM\ConsultationRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsultationRequest extends EditRecord
{
    protected static string $resource = ConsultationRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
