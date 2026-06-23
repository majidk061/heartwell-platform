<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Models\SupportPathway;
use App\Domains\CRM\Enums\AvatarType;
use App\Filament\Resources\Content\SupportPathwayResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportPathwayResource extends Resource
{
    protected static ?string $model = SupportPathway::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('intro')
                    ->rows(4)
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('accordion_content')
                    ->schema([
                        Forms\Components\TextInput::make('heading')
                            ->required(),
                        Forms\Components\Textarea::make('body')
                            ->rows(3),
                    ])
                    ->columnSpanFull(),
                Forms\Components\Select::make('avatar_type')
                    ->options(collect(AvatarType::cases())->mapWithKeys(
                        fn (AvatarType $type) => [$type->value => $type->label()]
                    )),
                Forms\Components\TextInput::make('cta_label')
                    ->maxLength(255),
                Forms\Components\TextInput::make('cta_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0),
                Forms\Components\Toggle::make('is_published')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('avatar_type')
                    ->badge(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportPathways::route('/'),
            'create' => Pages\CreateSupportPathway::route('/create'),
            'edit' => Pages\EditSupportPathway::route('/{record}/edit'),
        ];
    }
}
