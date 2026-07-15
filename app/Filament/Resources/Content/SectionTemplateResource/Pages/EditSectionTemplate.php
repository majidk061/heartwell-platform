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
        $existing = is_array($this->record->content) ? $this->record->content : [];

        $data['content'] = array_merge($existing, is_array($data['content'] ?? null) ? $data['content'] : []);

        if (blank($data['content_image'] ?? null) && filled($existing['image_url'] ?? null)) {
            $data['content']['image_url'] = $existing['image_url'];
        }

        if (blank($data['content_image_mobile'] ?? null) && filled($existing['image_url_mobile'] ?? null)) {
            $data['content']['image_url_mobile'] = $existing['image_url_mobile'];
        }

        $data = SectionTemplateResource::mutateTemplateData($data);

        if (($data['content']['design_variant'] ?? null) === 'journey_split_hero' && filled($data['heading'] ?? null)) {
            $data['content']['hero_title'] = $data['heading'];
        }

        return $this->applyPendingContentStatus($data);
    }
}
