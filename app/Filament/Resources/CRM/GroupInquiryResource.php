<?php

namespace App\Filament\Resources\CRM;

use App\Domains\CRM\Models\GroupInquiry;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\CRM\GroupInquiryResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GroupInquiryResource extends Resource
{
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = GroupInquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Leads & CRM';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationLabel = 'Group Inquiries';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Host contact', 'heroicon-o-user', [
                    Forms\Components\Select::make('lead_id')
                        ->relationship('lead', 'email')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Forms\Components\TextInput::make('host_name')->required()->prefixIcon('heroicon-o-user'),
                    Forms\Components\TextInput::make('host_email')->email()->required()->prefixIcon('heroicon-o-envelope'),
                    Forms\Components\TextInput::make('host_phone')->tel()->prefixIcon('heroicon-o-phone'),
                ]),
                static::formSection('Event details', 'heroicon-o-calendar', [
                    Forms\Components\TextInput::make('event_name')->prefixIcon('heroicon-o-sparkles'),
                    Forms\Components\DatePicker::make('event_date'),
                    Forms\Components\TextInput::make('guest_count')->numeric()->minValue(1)->prefixIcon('heroicon-o-users'),
                    Forms\Components\Textarea::make('message')->rows(4)->columnSpanFull(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'pending' => 'Pending',
                            'contacted' => 'Contacted',
                            'confirmed' => 'Confirmed',
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
                Tables\Columns\TextColumn::make('host_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('host_email')->searchable()->copyable(),
                Tables\Columns\TextColumn::make('event_name')->searchable(),
                Tables\Columns\TextColumn::make('event_date')->date()->sortable(),
                Tables\Columns\TextColumn::make('guest_count')->sortable(),
                Tables\Columns\TextColumn::make('status')->badge(),
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
            'index' => Pages\ListGroupInquiries::route('/'),
            'create' => Pages\CreateGroupInquiry::route('/create'),
            'edit' => Pages\EditGroupInquiry::route('/{record}/edit'),
        ];
    }
}
