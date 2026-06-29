<?php

namespace App\Filament\Resources\Automation;

use App\Domains\Automation\Models\AutomationLog;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\Automation\AutomationLogResource\Pages;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AutomationLogResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresHeartWellTables;

    protected static ?string $model = AutomationLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Automation';

    protected static ?string $navigationLabel = 'Automation Logs';

    protected static ?int $navigationSort = 2;

    protected static function permissionPrefix(): string
    {
        return 'automation.logs';
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('executed_at')->dateTime()->sortable(),
                Tables\Columns\TextColumn::make('channel')->badge(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\TextColumn::make('lead.email')->label('Lead'),
                Tables\Columns\TextColumn::make('error_message')->limit(40)->toggleable(),
            ])
            ->defaultSort('executed_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAutomationLogs::route('/'),
        ];
    }
}
