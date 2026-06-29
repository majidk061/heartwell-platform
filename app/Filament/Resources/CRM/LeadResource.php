<?php

namespace App\Filament\Resources\CRM;

use App\Domains\CRM\Actions\TransitionLeadStatusAction;
use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Enums\LeadPriority;
use App\Domains\CRM\Enums\LeadSource;
use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Enums\PreferredContactMethod;
use App\Domains\CRM\Models\Lead;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\CRM\LeadResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;

class LeadResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = Lead::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Leads & CRM';

    protected static ?int $navigationSort = 1;

    protected static function permissionPrefix(): string
    {
        return 'crm.leads';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            static::formSection('Contact', 'heroicon-o-user', [
                Forms\Components\TextInput::make('first_name')->required()->maxLength(255),
                Forms\Components\TextInput::make('last_name')->maxLength(255),
                Forms\Components\TextInput::make('email')->email()->required(),
                Forms\Components\TextInput::make('phone')->tel(),
                Forms\Components\Select::make('preferred_contact_method')
                    ->options(collect(PreferredContactMethod::cases())->mapWithKeys(
                        fn (PreferredContactMethod $m) => [$m->value => $m->label()]
                    )),
            ]),
            static::formSection('CRM', 'heroicon-o-funnel', [
                Forms\Components\Select::make('source')
                    ->options(collect(LeadSource::cases())->mapWithKeys(
                        fn (LeadSource $source) => [$source->value => $source->label()]
                    ))
                    ->required(),
                Forms\Components\TextInput::make('source_page')->label('Source page'),
                Forms\Components\Select::make('avatar_type')
                    ->options(collect(AvatarType::cases())->mapWithKeys(
                        fn (AvatarType $type) => [$type->value => $type->label()]
                    )),
                Forms\Components\Select::make('status')
                    ->options(collect(LeadStatus::cases())->mapWithKeys(
                        fn (LeadStatus $status) => [$status->value => $status->label()]
                    ))
                    ->required()
                    ->disabled(fn (?Model $record) => $record !== null)
                    ->dehydrated(fn (?Model $record) => $record === null)
                    ->helperText('Use Change status action on the lead view for existing leads.'),
                Forms\Components\Select::make('priority')
                    ->options(collect(LeadPriority::cases())->mapWithKeys(
                        fn (LeadPriority $p) => [$p->value => $p->label()]
                    ))
                    ->default(LeadPriority::Normal->value),
                Forms\Components\Select::make('assigned_to')
                    ->relationship('assignedUser', 'name')
                    ->searchable()
                    ->preload(),
                Forms\Components\DateTimePicker::make('next_follow_up_at')->label('Next follow-up'),
                Forms\Components\Toggle::make('marketing_consent')->label('Marketing consent'),
                Forms\Components\TagsInput::make('tags'),
            ]),
            static::formSection('Notes', 'heroicon-o-document-text', [
                Forms\Components\Textarea::make('notes')->rows(4)->columnSpanFull(),
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
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('status')->badge()
                    ->formatStateUsing(fn (LeadStatus $state) => $state->label())
                    ->color(fn (LeadStatus $state) => match ($state) {
                        LeadStatus::NewLead => 'gray',
                        LeadStatus::Contacted => 'info',
                        LeadStatus::ConsultationScheduled => 'warning',
                        LeadStatus::Booked => 'success',
                        LeadStatus::Completed => 'success',
                        LeadStatus::FollowUp => 'danger',
                    }),
                Tables\Columns\TextColumn::make('priority')->badge()->toggleable(),
                Tables\Columns\TextColumn::make('source_page')->toggleable(),
                Tables\Columns\TextColumn::make('next_follow_up_at')->dateTime()->sortable()->toggleable(),
                Tables\Columns\TextColumn::make('assignedUser.name')->label('Assigned')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(collect(LeadStatus::cases())->mapWithKeys(
                        fn (LeadStatus $status) => [$status->value => $status->label()]
                    )),
                Tables\Filters\Filter::make('unassigned')
                    ->query(fn (Builder $query) => $query->whereNull('assigned_to')),
                Tables\Filters\Filter::make('overdue_follow_up')
                    ->query(fn (Builder $query) => $query->where('next_follow_up_at', '<', now())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('transitionStatus')
                    ->label('Change status')
                    ->icon('heroicon-o-arrow-path')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options(fn (Lead $record) => collect($record->status->allowedTransitions())
                                ->mapWithKeys(fn (LeadStatus $status) => [$status->value => $status->label()]))
                            ->required(),
                        Forms\Components\Textarea::make('notes')->rows(2),
                    ])
                    ->action(function (Lead $record, array $data): void {
                        try {
                            app(TransitionLeadStatusAction::class)->execute(
                                $record,
                                LeadStatus::from($data['status']),
                                auth()->id(),
                                $data['notes'] ?? null,
                            );
                            Notification::make()->title('Lead status updated')->success()->send();
                        } catch (ValidationException $e) {
                            Notification::make()->title('Invalid status transition')->danger()->send();
                        }
                    })
                    ->visible(fn (Lead $record) => $record->status->allowedTransitions() !== []),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('assign')
                        ->label('Assign to')
                        ->form([
                            Forms\Components\Select::make('assigned_to')
                                ->label('User')
                                ->options(fn () => \App\Models\User::query()->pluck('name', 'id'))
                                ->searchable()
                                ->required(),
                        ])
                        ->action(function ($records, array $data): void {
                            $records->each->update(['assigned_to' => $data['assigned_to']]);
                            Notification::make()->title('Leads assigned')->success()->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]), poll: true);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeads::route('/'),
            'create' => Pages\CreateLead::route('/create'),
            'view' => Pages\ViewLead::route('/{record}'),
            'edit' => Pages\EditLead::route('/{record}/edit'),
        ];
    }
}
