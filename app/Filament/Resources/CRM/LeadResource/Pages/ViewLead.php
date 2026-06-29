<?php

namespace App\Filament\Resources\CRM\LeadResource\Pages;

use App\Domains\CRM\Actions\TransitionLeadStatusAction;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\Lead;
use App\Filament\Resources\CRM\LeadResource;
use App\Filament\Resources\Pages\HeartWellEditRecord;
use Filament\Actions;
use Filament\Forms;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Validation\ValidationException;

class ViewLead extends ViewRecord
{
    protected static string $resource = LeadResource::class;

    protected function resolveRecord(int|string $key): \Illuminate\Database\Eloquent\Model
    {
        return parent::resolveRecord($key)->load(['statusHistory', 'assignedUser']);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to list')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(LeadResource::getUrl('index')),
            Actions\EditAction::make(),
            Actions\Action::make('assignToMe')
                ->label('Assign to me')
                ->icon('heroicon-o-user-circle')
                ->visible(fn (Lead $record) => $record->assigned_to !== auth()->id())
                ->action(function (Lead $record): void {
                    $record->update(['assigned_to' => auth()->id()]);
                    $this->record = $record->fresh(['statusHistory', 'assignedUser']);
                    Notification::make()->title('Lead assigned to you')->success()->send();
                }),
            Actions\Action::make('markContacted')
                ->label('Mark contacted')
                ->icon('heroicon-o-phone')
                ->visible(fn (Lead $record) => $record->status === LeadStatus::NewLead)
                ->action(function (Lead $record): void {
                    app(TransitionLeadStatusAction::class)->execute($record, LeadStatus::Contacted, auth()->id());
                    $record->update(['last_contacted_at' => now()]);
                    Notification::make()->title('Marked as contacted')->success()->send();
                }),
            Actions\Action::make('changeStatus')
                ->label('Change status')
                ->icon('heroicon-o-arrow-path')
                ->form([
                    Forms\Components\Select::make('status')
                        ->options(fn (Lead $record) => collect($record->status->allowedTransitions())
                            ->mapWithKeys(fn (LeadStatus $s) => [$s->value => $s->label()]))
                        ->required(),
                    Forms\Components\Textarea::make('notes')->rows(2),
                    Forms\Components\DateTimePicker::make('next_follow_up_at')->label('Next follow-up'),
                ])
                ->action(function (Lead $record, array $data): void {
                    try {
                        app(TransitionLeadStatusAction::class)->execute(
                            $record,
                            LeadStatus::from($data['status']),
                            auth()->id(),
                            $data['notes'] ?? null,
                        );
                        if (! empty($data['next_follow_up_at'])) {
                            $record->update(['next_follow_up_at' => $data['next_follow_up_at']]);
                        }
                        Notification::make()->title('Status updated')->success()->send();
                    } catch (ValidationException $e) {
                        Notification::make()->title('Invalid transition')->danger()->send();
                    }
                }),
            Actions\Action::make('addNote')
                ->label('Add note')
                ->icon('heroicon-o-document-plus')
                ->form([
                    Forms\Components\Textarea::make('note')->required()->rows(3),
                ])
                ->action(function (Lead $record, array $data): void {
                    $timestamp = now()->format('Y-m-d H:i');
                    $user = auth()->user()?->name ?? 'Admin';
                    $note = "[{$timestamp} — {$user}] ".$data['note'];
                    $record->update(['notes' => trim(($record->notes ?? '')."\n\n".$note)]);
                    Notification::make()->title('Note added')->success()->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Contact')->schema([
                Infolists\Components\TextEntry::make('full_name')
                    ->label('Name')
                    ->getStateUsing(fn (Lead $record) => $record->fullName()),
                Infolists\Components\TextEntry::make('email')->copyable(),
                Infolists\Components\TextEntry::make('phone'),
                Infolists\Components\TextEntry::make('preferred_contact_method')
                    ->label('Preferred contact')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? '—'),
            ])->columns(2),
            Infolists\Components\Section::make('Pipeline')->schema([
                Infolists\Components\TextEntry::make('status')->badge()
                    ->formatStateUsing(fn (LeadStatus $state) => $state->label()),
                Infolists\Components\TextEntry::make('source')->badge()
                    ->formatStateUsing(fn ($state) => $state->label()),
                Infolists\Components\TextEntry::make('source_page')->label('Source page'),
                Infolists\Components\TextEntry::make('avatar_type')
                    ->formatStateUsing(fn ($state) => $state?->label() ?? '—'),
                Infolists\Components\TextEntry::make('priority')->badge(),
                Infolists\Components\TextEntry::make('assignedUser.name')->label('Assigned to'),
                Infolists\Components\TextEntry::make('last_contacted_at')->dateTime(),
                Infolists\Components\TextEntry::make('next_follow_up_at')->dateTime(),
                Infolists\Components\IconEntry::make('marketing_consent')->boolean()->label('Marketing consent'),
            ])->columns(3),
            Infolists\Components\Section::make('Notes')
                ->schema([
                    Infolists\Components\TextEntry::make('notes')->columnSpanFull()->markdown(),
                ])
                ->collapsible()
                ->visible(fn (Lead $record) => filled($record->notes)),
            Infolists\Components\Section::make('Status history')->schema([
                Infolists\Components\RepeatableEntry::make('statusHistory')
                    ->schema([
                        Infolists\Components\TextEntry::make('to_status')
                            ->formatStateUsing(fn ($state) => $state instanceof LeadStatus ? $state->label() : $state),
                        Infolists\Components\TextEntry::make('notes'),
                        Infolists\Components\TextEntry::make('created_at')->dateTime(),
                    ])
                    ->columns(3),
            ])->collapsible(),
        ]);
    }
}
