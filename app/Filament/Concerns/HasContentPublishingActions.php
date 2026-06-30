<?php

namespace App\Filament\Concerns;

use App\Domains\Content\Actions\SaveContentRevisionAction;
use App\Domains\Content\Enums\ContentStatus;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\HtmlString;

trait HasContentPublishingActions
{
    protected ?ContentStatus $pendingContentStatus = null;

    /**
     * @return array<int, Actions\Action>
     */
    protected function getFormActions(): array
    {
        if ($this instanceof EditRecord && $this->getRecord()->isPublishedStatus()) {
            return [
                Actions\Action::make('save')
                    ->label('Save')
                    ->action(fn () => $this->saveWithStatus(ContentStatus::Published)),
                Actions\Action::make('unpublish')
                    ->label('Unpublish')
                    ->color('warning')
                    ->icon('heroicon-o-eye-slash')
                    ->action(fn () => $this->saveWithStatus(ContentStatus::Draft)),
                $this->getCancelFormAction(),
            ];
        }

        return [
            Actions\Action::make('saveDraft')
                ->label('Save draft')
                ->action(fn () => $this->saveWithStatus(ContentStatus::Draft)),
            Actions\Action::make('publish')
                ->label('Publish')
                ->color('success')
                ->icon('heroicon-o-check-circle')
                ->action(fn () => $this->saveWithStatus(ContentStatus::Published)),
            $this->getCancelFormAction(),
        ];
    }

    protected function saveWithStatus(ContentStatus $status): void
    {
        $this->pendingContentStatus = $status;

        if ($this instanceof CreateRecord) {
            $this->create();
        } elseif ($this instanceof EditRecord) {
            $this->save();
        }

        $this->pendingContentStatus = null;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function applyPendingContentStatus(array $data): array
    {
        $status = $this->pendingContentStatus ?? ContentStatus::Draft;

        $data['status'] = $status->value;
        $data['is_published'] = $status->isPublished();

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this instanceof EditRecord && $this->getRecord()->exists) {
            app(SaveContentRevisionAction::class)->execute($this->getRecord());
        }

        if (method_exists(parent::class, 'afterSave')) {
            parent::afterSave();
        }
    }

    protected function afterCreate(): void
    {
        if ($this->getRecord()->exists) {
            app(SaveContentRevisionAction::class)->execute($this->getRecord(), 'Initial version');
        }

        if (method_exists(parent::class, 'afterCreate')) {
            parent::afterCreate();
        }
    }

    public static function publishingStatusField(): Forms\Components\Select
    {
        return Forms\Components\Select::make('status')
            ->label('Status')
            ->options(collect(ContentStatus::cases())->mapWithKeys(
                fn (ContentStatus $status) => [$status->value => $status->label()]
            )->all())
            ->formatStateUsing(fn ($state) => $state instanceof ContentStatus ? $state->value : $state)
            ->default(ContentStatus::Draft->value)
            ->disabled()
            ->dehydrated();
    }

    public static function contentAuditPlaceholder(): Forms\Components\Placeholder
    {
        return Forms\Components\Placeholder::make('content_audit')
            ->label('Audit trail')
            ->content(function ($record): HtmlString {
                if (! $record?->exists) {
                    return new HtmlString('<span class="text-sm text-gray-500">Available after first save.</span>');
                }

                $record->loadMissing(['createdBy', 'updatedBy']);
                $created = $record->createdBy?->name ?? 'System';
                $updated = $record->updatedBy?->name ?? 'System';
                $createdAt = $record->created_at?->format('M j, Y g:i A') ?? '—';
                $updatedAt = $record->updated_at?->format('M j, Y g:i A') ?? '—';

                return new HtmlString(
                    '<div class="text-sm text-gray-600 space-y-1">'
                    .'<div><strong>Created</strong> by '.e($created).' on '.e($createdAt).'</div>'
                    .'<div><strong>Last updated</strong> by '.e($updated).' on '.e($updatedAt).'</div>'
                    .'</div>'
                );
            })
            ->columnSpanFull();
    }
}
