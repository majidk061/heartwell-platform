<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Models\Testimonial;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Concerns\ConfiguresReorderableTables;
use App\Filament\Resources\Content\TestimonialResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;
    use ConfiguresReorderableTables;

    protected static ?string $model = Testimonial::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 3;

    protected static function permissionPrefix(): string
    {
        return 'content.testimonials';
    }

    public static function getSubheading(): ?string
    {
        return 'Drag rows to change testimonial order on the website.';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Testimonial', 'heroicon-o-chat-bubble-left-right', [
                    Forms\Components\TextInput::make('author_name')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-user'),
                    Forms\Components\FileUpload::make('image_path')
                        ->label('Author photo')
                        ->image()
                        ->imageEditor()
                        ->imageEditorAspectRatios(['1:1'])
                        ->maxSize(2048)
                        ->disk('public')
                        ->directory('cms/testimonials')
                        ->helperText(\App\Filament\Concerns\ConfiguresHeartWellAdminUx::testimonialUploadHelper()),
                    Forms\Components\Textarea::make('quote')
                        ->required()
                        ->rows(4)
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('attribution')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-tag'),
                ]),
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
                Tables\Columns\TextColumn::make('author_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('quote')->limit(60),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
