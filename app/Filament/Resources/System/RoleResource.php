<?php

namespace App\Filament\Resources\System;

use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\System\RoleResource\Pages;
use Database\Seeders\PermissionSeeder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Roles';

    protected static ?int $navigationSort = 4;

    public static function permissionPrefix(): string
    {
        return 'system.users';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Role details', 'heroicon-o-shield-check', [
                    Forms\Components\TextInput::make('name')->required()->maxLength(255)->unique(ignoreRecord: true),
                    Forms\Components\Hidden::make('guard_name')->default('web'),
                ]),
                static::formSection('Permissions', 'heroicon-o-key', [
                    Forms\Components\Placeholder::make('all_permissions_notice')
                        ->label('')
                        ->content('Super admin has all permissions automatically.')
                        ->visible(fn (?Role $record) => $record?->name === 'super_admin'),
                    Forms\Components\CheckboxList::make('permissions')
                        ->relationship('permissions', 'name')
                        ->getOptionLabelFromRecordUsing(fn ($record) => str_replace('.', ' › ', $record->name))
                        ->columns(3)
                        ->searchable()
                        ->disabled(fn (?Role $record) => $record?->name === 'super_admin')
                        ->columnSpanFull(),
                ], 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('permissions_count')->counts('permissions')->label('Permissions'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoles::route('/'),
            'create' => Pages\CreateRole::route('/create'),
            'edit' => Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
