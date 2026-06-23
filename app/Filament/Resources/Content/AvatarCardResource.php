<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Models\AvatarCard;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\Content\AvatarCardResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AvatarCardResource extends Resource
{
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = AvatarCard::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationLabel = 'Avatar Cards';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Card content', 'heroicon-o-identification', [
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->prefixIcon('heroicon-o-link'),
                    Forms\Components\TextInput::make('headline')
                        ->required()
                        ->prefixIcon('heroicon-o-chat-bubble-left-right'),
                    Forms\Components\Textarea::make('subtext')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('image_path')
                        ->label('Card image')
                        ->image()
                        ->disk('public')
                        ->directory('cms/avatar-cards')
                        ->columnSpanFull(),
                ]),
                static::formSection('Link & display', 'heroicon-o-arrow-top-right-on-square', [
                    Forms\Components\TextInput::make('cta_label')
                        ->prefixIcon('heroicon-o-cursor-arrow-rays'),
                    Forms\Components\TextInput::make('pathway_slug')
                        ->label('Pathway slug')
                        ->prefixIcon('heroicon-o-map'),
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->prefixIcon('heroicon-o-arrows-up-down'),
                    Forms\Components\Toggle::make('is_published')
                        ->default(true),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('headline')->searchable(),
                Tables\Columns\TextColumn::make('pathway_slug'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
            ]));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAvatarCards::route('/'),
            'create' => Pages\CreateAvatarCard::route('/create'),
            'edit' => Pages\EditAvatarCard::route('/{record}/edit'),
        ];
    }
}
