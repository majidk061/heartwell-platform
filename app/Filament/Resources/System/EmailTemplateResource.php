<?php

namespace App\Filament\Resources\System;

use App\Domains\Integrations\Actions\SendTemplatedEmailAction;
use App\Domains\Integrations\Models\EmailTemplate;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresCmsImageFields;
use App\Filament\Concerns\ConfiguresHeartWellAdminUx;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\System\EmailTemplateResource\Pages;
use Database\Seeders\EmailTemplateSeeder;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmailTemplateResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresCmsImageFields;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = EmailTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-envelope-open';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?string $navigationLabel = 'Email Templates';

    protected static ?int $navigationSort = 2;

    protected static function permissionPrefix(): string
    {
        return 'system.email_templates';
    }

    public static function getSubheading(): ?string
    {
        return 'Edit logo, subject, heading, and body for automated emails. Use merge tags like {{first_name}} and {{email}}.';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Template', 'heroicon-o-envelope-open', [
                    Forms\Components\TextInput::make('name')->required()->disabled(),
                    Forms\Components\TextInput::make('key')->disabled(),
                    Forms\Components\Select::make('audience')->options(['user' => 'User', 'admin' => 'Admin'])->disabled(),
                    Forms\Components\Toggle::make('is_enabled')->label('Enabled'),
                    Forms\Components\TextInput::make('subject')->required()->columnSpanFull(),
                    Forms\Components\TextInput::make('heading')->columnSpanFull(),
                    Forms\Components\RichEditor::make('body')->columnSpanFull(),
                    static::cmsImagePreviewPlaceholder('logo_path', 'Current logo'),
                    static::cmsImageUploadField(
                        'logo_path',
                        'Logo override',
                        'cms/email',
                        ConfiguresHeartWellAdminUx::logoUploadHelper(),
                    )->imageEditor()->imageEditorAspectRatios(['10:3'])->maxSize(1024),
                    Forms\Components\TextInput::make('button_label'),
                    Forms\Components\TextInput::make('button_url')->helperText('Supports merge tags, e.g. {{reset_url}}'),
                    Forms\Components\Textarea::make('footer_text')->rows(2)->columnSpanFull(),
                ]),
                static::formSection('Merge tags', 'heroicon-o-code-bracket', [
                    Forms\Components\Placeholder::make('merge_tags')
                        ->content('Common tags: {{first_name}}, {{last_name}}, {{email}}, {{phone}}, {{message}}, {{event_name}}, {{guest_count}}, {{booking_date}}, {{reset_url}}, {{name}}, {{source}}'),
                ], 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('key')->toggleable(),
                Tables\Columns\TextColumn::make('audience')->badge(),
                Tables\Columns\IconColumn::make('is_enabled')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('testSend')
                    ->label('Test send')
                    ->icon('heroicon-o-paper-airplane')
                    ->action(function (EmailTemplate $record) {
                        $email = auth()->user()?->email;

                        if (! $email) {
                            return;
                        }

                        app(SendTemplatedEmailAction::class)->execute($record->key, $email, [
                            'first_name' => 'Test',
                            'last_name' => 'User',
                            'email' => $email,
                            'name' => auth()->user()?->name ?? 'Admin',
                            'reset_url' => url('/admin'),
                        ]);

                        Notification::make()->title('Test email sent to '.$email)->success()->send();
                    }),
                Tables\Actions\Action::make('resetDefault')
                    ->label('Reset to default')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->action(function (EmailTemplate $record) {
                        $default = collect(EmailTemplateSeeder::defaults())->firstWhere('key', $record->key);

                        if ($default) {
                            $record->update($default);
                            Notification::make()->title('Template reset')->success()->send();
                        }
                    }),
            ]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmailTemplates::route('/'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
