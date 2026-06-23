<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Models\Faq;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Resources\Content\FaqResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;

    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 4;

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
                    Forms\Components\TextInput::make('page_slug')
                        ->label('Page slug')
                        ->helperText('Leave blank for global FAQs.')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-link'),
                ], 1),
                static::formSection('Publishing', 'heroicon-o-eye', [
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
                Tables\Columns\TextColumn::make('question')->searchable()->limit(50),
                Tables\Columns\TextColumn::make('page_slug')->label('Page'),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
