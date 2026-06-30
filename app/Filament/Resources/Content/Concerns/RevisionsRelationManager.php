<?php

namespace App\Filament\Resources\Content\Concerns;

use App\Domains\Content\Actions\CompareContentRevisionAction;
use App\Domains\Content\Actions\RestoreContentRevisionAction;
use App\Domains\Content\Exceptions\ContentRevisionRestoreException;
use App\Domains\Content\Models\ContentRevision;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class RevisionsRelationManager extends RelationManager
{
    protected static string $relationship = 'revisions';

    protected static ?string $title = 'Revision history';

    protected static ?string $icon = 'heroicon-o-clock';

    public function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Date')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('user.name')->label('Author')->placeholder('System'),
                Tables\Columns\TextColumn::make('note')->label('Note')->placeholder('—'),
                Tables\Columns\TextColumn::make('changes_summary')
                    ->label('Changes')
                    ->wrap()
                    ->getStateUsing(fn (ContentRevision $record): string => $this->summarizeChanges($record)),
            ])
            ->actions([
                Tables\Actions\Action::make('viewChanges')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('Revision changes')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close')
                    ->modalContent(fn (ContentRevision $record): HtmlString => new HtmlString(
                        '<ul class="list-disc space-y-2 pl-5 text-sm text-gray-700">'
                        .collect($this->listChanges($record))
                            ->map(fn (string $change): string => '<li>'.e($change).'</li>')
                            ->implode('')
                        .'</ul>'
                    )),
                Tables\Actions\Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalDescription('This saves the current version first, then restores the selected revision.')
                    ->action(function (ContentRevision $record): void {
                        try {
                            app(RestoreContentRevisionAction::class)->execute($record);
                        } catch (ContentRevisionRestoreException $exception) {
                            Notification::make()
                                ->title('Unable to restore revision')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();

                            return;
                        }

                        Notification::make()
                            ->title('Revision restored')
                            ->success()
                            ->send();

                        $url = $this->getResource()::getUrl('edit', [
                            'record' => $this->getOwnerRecord(),
                        ]);

                        if (request()->filled('return_page_id')) {
                            $url .= '?return_page_id='.request('return_page_id');
                        }

                        redirect($url);
                    }),
            ])
            ->emptyStateHeading('No revisions yet')
            ->emptyStateDescription('Each save creates a revision you can restore later.');
    }

    /**
     * @return list<string>
     */
    protected function listChanges(ContentRevision $record): array
    {
        return app(CompareContentRevisionAction::class)->execute(
            $record,
            $this->previousRevision($record),
        );
    }

    protected function summarizeChanges(ContentRevision $record): string
    {
        return app(CompareContentRevisionAction::class)->summarize(
            $record,
            $this->previousRevision($record),
        );
    }

    protected function previousRevision(ContentRevision $record): ?ContentRevision
    {
        return ContentRevision::query()
            ->where('revisable_type', $record->revisable_type)
            ->where('revisable_id', $record->revisable_id)
            ->where('created_at', '<', $record->created_at)
            ->latest('created_at')
            ->first();
    }
}
