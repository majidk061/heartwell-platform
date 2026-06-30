<?php

namespace App\Filament\Resources\Content\SectionTemplateResource\Pages;

use App\Filament\Concerns\HasContentPublishingActions;
use App\Filament\Resources\Content\PageResource;
use App\Filament\Resources\Content\SectionTemplateResource;
use App\Filament\Resources\Pages\HeartWellEditRecord;
use Filament\Actions;
use Illuminate\Contracts\Support\Htmlable;

class EditSectionTemplate extends HeartWellEditRecord
{
    use HasContentPublishingActions;

    protected static string $resource = SectionTemplateResource::class;

    public function getSubheading(): string|Htmlable|null
    {
        $returnPageId = request()->integer('return_page_id');

        if ($returnPageId) {
            return 'Editing library content. When finished, return to the page placement tab.';
        }

        return parent::getSubheading();
    }

    protected function getHeaderActions(): array
    {
        $actions = parent::getHeaderActions();

        $returnPageId = request()->integer('return_page_id');

        if ($returnPageId) {
            array_unshift($actions, Actions\Action::make('backToPage')
                ->label('Back to page sections')
                ->icon('heroicon-o-arrow-left')
                ->url(PageResource::getUrl('edit', [
                    'record' => $returnPageId,
                    'activeRelationManager' => 0,
                ])));
        }

        array_unshift($actions, Actions\Action::make('previewSection')
            ->label('Preview section')
            ->icon('heroicon-o-eye')
            ->url(fn (): string => route('admin.preview.section', ['template' => $this->record->getKey()]))
            ->openUrlInNewTab()
            ->visible(fn (): bool => $this->record->exists));

        return $actions;
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return SectionTemplateResource::hydrateTemplateData($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $existingImage = is_array($this->record->content ?? null)
            ? ($this->record->content['image_url'] ?? null)
            : null;

        if (blank($data['content_image'] ?? null) && filled($existingImage)) {
            $data['content'] = array_merge(
                is_array($this->record->content) ? $this->record->content : [],
                ['image_url' => $existingImage],
            );
        }

        return $this->applyPendingContentStatus(
            SectionTemplateResource::mutateTemplateData($data)
        );
    }
}
