<?php

namespace App\Filament\Resources\Automation;

use App\Domains\Automation\Models\AutomationRule;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\Automation\AutomationRuleResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AutomationRuleResource extends Resource
{
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = AutomationRule::class;

    protected static ?string $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $navigationGroup = 'Automation';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Rule', 'heroicon-o-bolt', [
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-tag'),
                    Forms\Components\Select::make('trigger_type')
                        ->options([
                            'waitlist_joined' => 'Waitlist joined',
                            'consultation_requested' => 'Consultation requested',
                            'group_inquiry_submitted' => 'Group inquiry submitted',
                            'lead_status_changed' => 'Lead status changed',
                        ])
                        ->required(),
                    Forms\Components\Select::make('channel')
                        ->options([
                            'email' => 'Email (SendGrid)',
                            'mailchimp' => 'Mailchimp',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('template_ref')
                        ->label('Template reference')
                        ->helperText('SendGrid template ID or Mailchimp tag reference.')
                        ->maxLength(255),
                    Forms\Components\TextInput::make('delay_minutes')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->prefixIcon('heroicon-o-clock'),
                ]),
                static::formSection('Conditions & status', 'heroicon-o-adjustments-horizontal', [
                    Forms\Components\KeyValue::make('conditions')
                        ->helperText('Optional key/value conditions matched against event context.')
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true),
                ], 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('trigger_type')->badge(),
                Tables\Columns\TextColumn::make('channel')->badge(),
                Tables\Columns\TextColumn::make('delay_minutes')->label('Delay (min)')->sortable(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAutomationRules::route('/'),
            'create' => Pages\CreateAutomationRule::route('/create'),
            'edit' => Pages\EditAutomationRule::route('/{record}/edit'),
        ];
    }
}
