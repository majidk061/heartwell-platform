<?php

namespace App\Filament\Resources\CRM;

use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Models\WaitlistEntry;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\CRM\WaitlistEntryResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WaitlistEntryResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = WaitlistEntry::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationGroup = 'Leads & CRM';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationLabel = 'Waitlist';

    protected static function permissionPrefix(): string
    {
        return 'crm.waitlist';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Contact', 'heroicon-o-user', [
                    Forms\Components\Select::make('lead_id')
                        ->relationship('lead', 'email')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->prefixIcon('heroicon-o-link'),
                    Forms\Components\TextInput::make('first_name')->required()->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('last_name')->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('email')->email()->required()->prefixIcon('heroicon-o-envelope'),
                    Forms\Components\TextInput::make('phone')->tel()->prefixIcon('heroicon-o-phone'),
                ]),
                static::formSection('Details', 'heroicon-o-queue-list', [
                    Forms\Components\TagsInput::make('interests')->columnSpanFull(),
                    Forms\Components\TextInput::make('source_page')->prefixIcon('heroicon-o-globe-alt'),
                    Forms\Components\Select::make('avatar_type')
                        ->options(collect(AvatarType::cases())->mapWithKeys(
                            fn (AvatarType $type) => [$type->value => $type->label()]
                        )),
                    Forms\Components\Select::make('status')
                        ->options([
                            'active' => 'Active',
                            'converted' => 'Converted',
                            'unsubscribed' => 'Unsubscribed',
                        ])
                        ->required(),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('last_name')->searchable(),
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('avatar_type')->badge(),
                Tables\Columns\TextColumn::make('source_page')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since()->dateTimeTooltip()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListWaitlistEntries::route('/'),
            'create' => Pages\CreateWaitlistEntry::route('/create'),
            'edit' => Pages\EditWaitlistEntry::route('/{record}/edit'),
        ];
    }
}
