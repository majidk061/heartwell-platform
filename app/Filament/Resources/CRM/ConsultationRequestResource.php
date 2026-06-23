<?php

namespace App\Filament\Resources\CRM;

use App\Domains\CRM\Enums\AvatarType;
use App\Domains\CRM\Models\ConsultationRequest;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\CRM\ConsultationRequestResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ConsultationRequestResource extends Resource
{
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = ConsultationRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationGroup = 'Leads & CRM';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Consultations';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Contact', 'heroicon-o-user', [
                    Forms\Components\Select::make('lead_id')
                        ->relationship('lead', 'email')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('first_name')->required()->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('last_name')->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('email')->email()->required()->prefixIcon('heroicon-o-envelope'),
                    Forms\Components\TextInput::make('phone')->tel()->prefixIcon('heroicon-o-phone'),
                ]),
                static::formSection('Request details', 'heroicon-o-calendar-days', [
                    Forms\Components\Textarea::make('message')->rows(4)->columnSpanFull(),
                    Forms\Components\Select::make('preferred_contact_method')
                        ->options([
                            'email' => 'Email',
                            'phone' => 'Phone',
                            'either' => 'Either',
                        ]),
                    Forms\Components\TextInput::make('source_page')->prefixIcon('heroicon-o-globe-alt'),
                    Forms\Components\Select::make('avatar_type')
                        ->options(collect(AvatarType::cases())->mapWithKeys(
                            fn (AvatarType $type) => [$type->value => $type->label()]
                        )),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'contacted' => 'Contacted',
                            'scheduled' => 'Scheduled',
                            'closed' => 'Closed',
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
                Tables\Columns\TextColumn::make('email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('preferred_contact_method')->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->since()->dateTimeTooltip()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'contacted' => 'Contacted',
                        'scheduled' => 'Scheduled',
                        'closed' => 'Closed',
                    ]),
            ])
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
            'index' => Pages\ListConsultationRequests::route('/'),
            'create' => Pages\CreateConsultationRequest::route('/create'),
            'edit' => Pages\EditConsultationRequest::route('/{record}/edit'),
        ];
    }
}
