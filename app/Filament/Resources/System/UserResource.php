<?php

namespace App\Filament\Resources\System;

use App\Domains\Admin\Actions\SendAdminPasswordResetInviteAction;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\System\UserResource\Pages;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Team Members';

    protected static ?int $navigationSort = 3;

    protected static ?string $modelLabel = 'Team Member';

    public static function permissionPrefix(): string
    {
        return 'system.users';
    }

    public static function getSubheading(): ?string
    {
        return 'Invite sub-admins with specific roles and permissions. They receive an email to set their password.';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Account', 'heroicon-o-user', [
                    Forms\Components\TextInput::make('name')->required()->maxLength(255),
                    Forms\Components\TextInput::make('email')->email()->required()->maxLength(255)->unique(ignoreRecord: true),
                    Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
                ]),
                static::formSection('Roles', 'heroicon-o-shield-check', [
                    Forms\Components\Placeholder::make('super_admin_notice')
                        ->label('')
                        ->content('Super admin always has full access to all areas.')
                        ->visible(fn (?User $record) => $record?->hasRole('super_admin')),
                    Forms\Components\CheckboxList::make('roles')
                        ->options(Role::query()->pluck('name', 'name'))
                        ->disabled(fn (?User $record) => $record?->hasRole('super_admin'))
                        ->columns(2)
                        ->columnSpanFull(),
                ], 1),
                static::formSection('Permissions', 'heroicon-o-key', [
                    Forms\Components\TagsInput::make('effective_permissions')
                        ->label('Effective permissions (from roles + direct)')
                        ->disabled()
                        ->dehydrated(false)
                        ->columnSpanFull(),
                    Forms\Components\CheckboxList::make('permissions')
                        ->label('Direct permissions (extra grants)')
                        ->options(collect(PermissionSeeder::allPermissions())
                            ->mapWithKeys(fn (string $p) => [$p => str_replace('.', ' › ', $p)])
                            ->all())
                        ->disabled(fn (?User $record) => $record?->hasRole('super_admin'))
                        ->columns(3)
                        ->searchable()
                        ->columnSpanFull(),
                ], 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable(),
                Tables\Columns\TextColumn::make('roles.name')->badge(),
                Tables\Columns\IconColumn::make('is_active')->label('Active')->boolean(),
                Tables\Columns\TextColumn::make('invited_at')->dateTime()->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('resendInvite')
                    ->label('Resend invite')
                    ->icon('heroicon-o-envelope')
                    ->visible(fn (User $record) => filled($record->invited_at))
                    ->action(function (User $record) {
                        try {
                            app(SendAdminPasswordResetInviteAction::class)->execute($record, true);

                            $record->update(['invited_at' => now()]);

                            Notification::make()->title('Invite resent')->success()->send();
                        } catch (\Throwable $exception) {
                            report($exception);

                            Notification::make()
                                ->title('Invite email failed')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\Action::make('forceReset')
                    ->label('Force password reset')
                    ->icon('heroicon-o-key')
                    ->action(function (User $record) {
                        try {
                            app(SendAdminPasswordResetInviteAction::class)->execute($record, true);

                            Notification::make()->title('Password reset email sent')->success()->send();
                        } catch (\Throwable $exception) {
                            report($exception);

                            Notification::make()
                                ->title('Password reset email failed')
                                ->body($exception->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $record) => ! $record->hasRole('super_admin')),
            ]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
