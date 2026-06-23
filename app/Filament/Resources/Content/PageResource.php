<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Models\Page;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\Content\PageResource\Pages;
use App\Filament\Resources\Content\PageResource\RelationManagers\SectionsRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Page details', 'heroicon-o-document-text', [
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-book-open'),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->prefixIcon('heroicon-o-link'),
                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->prefixIcon('heroicon-o-arrows-up-down'),
                ]),
                static::formSection('SEO', 'heroicon-o-magnifying-glass', [
                    Forms\Components\TextInput::make('meta_title')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-tag'),
                    Forms\Components\Textarea::make('meta_description')
                        ->rows(3)
                        ->maxLength(500)
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('og_image')
                        ->label('Social share image')
                        ->image()
                        ->disk('public')
                        ->directory('cms/pages')
                        ->columnSpanFull(),
                ]),
                static::formSection('Publishing', 'heroicon-o-eye', [
                    Forms\Components\Toggle::make('is_published')
                        ->default(true),
                ], 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]));
    }

    public static function getRelations(): array
    {
        return [
            SectionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }
}
