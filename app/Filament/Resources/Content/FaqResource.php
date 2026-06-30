<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Models\Faq;
use App\Domains\Content\Models\Page;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Concerns\ConfiguresReorderableTables;
use App\Filament\Resources\Content\FaqResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;
    use ConfiguresReorderableTables;

    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 4;

    protected static function permissionPrefix(): string
    {
        return 'content.faqs';
    }

    public static function getSubheading(): ?string
    {
        return 'Drag rows to change FAQ order on the website.';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('FAQ content', 'heroicon-o-question-mark-circle', [
                    Forms\Components\TextInput::make('question')
                        ->required()
                        ->maxLength(500)
                        ->prefixIcon('heroicon-o-question-mark-circle')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('answer')
                        ->required()
                        ->rows(5)
                        ->columnSpanFull(),
                    Forms\Components\Select::make('page_slug')
                        ->label('Page')
                        ->options(fn () => Page::query()->orderBy('sort_order')->pluck('title', 'slug'))
                        ->required()
                        ->searchable()
                        ->helperText('FAQ appears only on pages that include an FAQ section with this slug.'),
                ], 1),
                static::formSection('Publishing', 'heroicon-o-eye', [
                    Forms\Components\Toggle::make('is_published')
                        ->default(true),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::applyReorderableSort(static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('question')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('page_slug')->label('Page'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->slideOver(),
            ])
            ->recordAction(Tables\Actions\EditAction::class)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
