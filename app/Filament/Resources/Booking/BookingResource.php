<?php

namespace App\Filament\Resources\Booking;

use App\Domains\Booking\Models\Booking;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\Booking\BookingResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BookingResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Bookings';

    protected static ?int $navigationSort = 1;

    protected static function permissionPrefix(): string
    {
        return 'bookings';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            static::formSection('Booking', 'heroicon-o-calendar', [
                Forms\Components\Select::make('lead_id')
                    ->relationship('lead', 'email')
                    ->searchable()
                    ->preload(),
                Forms\Components\TextInput::make('type')->required(),
                Forms\Components\TextInput::make('external_acuity_id')->label('Acuity ID'),
                Forms\Components\Select::make('status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required(),
                Forms\Components\DateTimePicker::make('scheduled_at'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('lead.email')->label('Lead')->searchable(),
                Tables\Columns\TextColumn::make('type')->badge(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('scheduled_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('external_acuity_id')->label('Acuity ID')->toggleable(),
            ])
            ->defaultSort('scheduled_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
