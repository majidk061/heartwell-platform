<?php

namespace App\Filament\Resources\Content\SectionTemplateResource\Pages;

use App\Filament\Concerns\HasContentPublishingActions;
use App\Filament\Resources\Content\SectionTemplateResource;
use App\Filament\Resources\Pages\HeartWellCreateRecord;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;

class CreateSectionTemplate extends HeartWellCreateRecord
{
    use HasContentPublishingActions;

    protected static string $resource = SectionTemplateResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                Wizard\Step::make('Choose type')
                    ->description('Pick the section layout for this template')
                    ->schema(SectionTemplateResource::sectionTypeWizardSchema()),
                Wizard\Step::make('Template content')
                    ->description('Name your template and add content')
                    ->schema(SectionTemplateResource::templateFormSchema(includeTypeField: false)),
            ])->columnSpanFull(),
        ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->applyPendingContentStatus(
            SectionTemplateResource::mutateTemplateData($data)
        );
    }
}
