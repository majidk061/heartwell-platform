<?php

namespace App\Filament\Concerns;

use App\Domains\Content\Actions\PublishContentAction;
use App\Domains\Content\Actions\UnpublishContentAction;
use App\Domains\Content\Concerns\HasContentStatus;
use Filament\Notifications\Notification;
use Filament\Tables;
use Illuminate\Database\Eloquent\Model;

trait ConfiguresContentPublishingTableActions
{
    /**
     * @return array<int, Tables\Actions\Action>
     */
    protected static function contentPublishingTableActions(): array
    {
        return [
            Tables\Actions\Action::make('publish')
                ->label('Publish')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (Model $record): bool => static::recordIsDraft($record))
                ->requiresConfirmation()
                ->action(function (Model $record): void {
                    app(PublishContentAction::class)->execute($record);

                    Notification::make()
                        ->title('Published')
                        ->success()
                        ->send();
                }),
            Tables\Actions\Action::make('unpublish')
                ->label('Unpublish')
                ->icon('heroicon-o-eye-slash')
                ->color('warning')
                ->visible(fn (Model $record): bool => static::recordIsPublished($record))
                ->requiresConfirmation()
                ->action(function (Model $record): void {
                    app(UnpublishContentAction::class)->execute($record);

                    Notification::make()
                        ->title('Unpublished')
                        ->success()
                        ->send();
                }),
        ];
    }

    /**
     * @return array<int, Tables\Actions\BulkAction>
     */
    protected static function contentPublishingBulkActions(): array
    {
        return [
            Tables\Actions\BulkAction::make('publish')
                ->label('Publish selected')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                    $records->each(fn (Model $record) => app(PublishContentAction::class)->execute($record));

                    Notification::make()
                        ->title('Selected items published')
                        ->success()
                        ->send();
                }),
            Tables\Actions\BulkAction::make('unpublish')
                ->label('Unpublish selected')
                ->icon('heroicon-o-eye-slash')
                ->color('warning')
                ->requiresConfirmation()
                ->action(function (\Illuminate\Database\Eloquent\Collection $records): void {
                    $records->each(fn (Model $record) => app(UnpublishContentAction::class)->execute($record));

                    Notification::make()
                        ->title('Selected items unpublished')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected static function recordIsDraft(Model $record): bool
    {
        return in_array(HasContentStatus::class, class_uses_recursive($record), true)
            && method_exists($record, 'isDraft')
            && $record->isDraft();
    }

    protected static function recordIsPublished(Model $record): bool
    {
        return in_array(HasContentStatus::class, class_uses_recursive($record), true)
            && method_exists($record, 'isPublishedStatus')
            && $record->isPublishedStatus();
    }
}
