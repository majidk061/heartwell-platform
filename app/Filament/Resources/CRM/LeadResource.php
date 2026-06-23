<?php

namespace App\Filament\Resources\CRM;

use App\Domains\CRM\Actions\TransitionLeadStatusAction;
use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\Lead;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\CRM\LeadResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class LeadResource extends Resource
{
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Leads & CRM';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Contact', 'heroicon-o-user', [
                    Forms\Components\TextInput::make('first_name')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('last_name')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('email')
                        ->email()
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-envelope'),
                    Forms\Components\TextInput::make('phone')
                        ->tel()
                        ->maxLength(50)
                        ->prefixIcon('heroicon-o-phone'),
                ]),
                static::formSection('CRM', 'heroicon-o-funnel', [
                    Forms\Components\Select::make('source')
                        ->options(collect(LeadSource::cases())->mapWithKeys(
                            fn (LeadSource $source) => [$source->value => $source->label()]
                        ))
                        ->required()
                        ->prefixIcon('heroicon-o-arrow-path'),
                    Forms\Components\Select::make('avatar_type')
                        ->options(collect(AvatarType::cases())->mapWithKeys(
                            fn (AvatarType $type) => [$type->value => $type->label()]
                        ))
                        ->prefixIcon('heroicon-o-heart'),
                    Forms\Components\Select::make('status')
                        ->options(collect(LeadStatus::cases())->mapWithKeys(
                            fn (LeadStatus $status) => [$status->value => $status->label()]
                        ))
                        ->required()
                        ->disabled(fn (?Model $record) => $record !== null)
                        ->dehydrated(fn (?Model $record) => $record === null)
                        ->helperText('Use the status transition action on the edit page for existing leads.')
                        ->prefixIcon('heroicon-o-flag'),
                    Forms\Components\Select::make('assigned_to')
                        ->relationship('assignedUser', 'name')
                        ->searchable()
                        ->preload()
                        ->prefixIcon('heroicon-o-user-circle'),
                ]),
                static::formSection('Notes', 'heroicon-o-document-text', [
                    Forms\Components\Textarea::make('notes')
                        ->rows(4)
                        ->columnSpanFull(),
                ], 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->getStateUsing(fn (Lead $record) => $record->fullName())
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['first_name']),
                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->copyable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (LeadStatus $state) => $state->label())
                    ->color(fn (LeadStatus $state) => match ($state) {
                        LeadStatus::NewLead => 'gray',
                        LeadStatus::Contacted => 'info',
                        LeadStatus::ConsultationScheduled => 'warning',
                        LeadStatus::Booked => 'success',
                        LeadStatus::Completed => 'success',
                        LeadStatus::FollowUp => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('source')
                    ->badge()
                    ->formatStateUsing(fn (LeadSource $state) => $state->label()),
                Tables\Columns\TextColumn::make('assignedUser.name')
                    ->label('Assigned to')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(LeadStatus::cases())->mapWithKeys(
                        fn (LeadStatus $status) => [$status->value => $status->label()]
                    )),
                Tables\Filters\SelectFilter::make('source')
                    ->options(collect(LeadSource::cases())->mapWithKeys(
                        fn (LeadSource $source) => [$source->value => $source->label()]
                    )),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('transitionStatus')
                    ->label('Change status')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->label('New status')
                            ->options(function (Lead $record) {
                                return collect($record->status->allowedTransitions())
                                    ->mapWithKeys(fn (LeadStatus $status) => [$status->value => $status->label()]);
                            })
                            ->required(),
                        Forms\Components\Textarea::make('notes')
                            ->rows(2),
                    ])
                    ->action(function (Lead $record, array $data, TransitionLeadStatusAction $action): void {
                        try {
                            $action->execute(
                                $record,
                                LeadStatus::from($data['status']),
                                auth()->id(),
                                $data['notes'] ?? null,
                            );

                            Notification::make()
                                ->title('Lead status updated')
                                ->success()
                                ->send();
                        } catch (ValidationException $e) {
                            Notification::make()
                                ->title('Invalid status transition')
                                ->body(collect($e->errors())->flatten()->first())
                                ->danger()
                                ->send();
                        }
                    })
                    ->visible(fn (Lead $record) => $record->status->allowedTransitions() !== []),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]), poll: true);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
