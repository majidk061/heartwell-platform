<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Models\Page;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresReorderableTables;
use App\Filament\Concerns\ConfiguresHeartWellAdminUx;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Concerns\ProvidesAdminGuidance;
use App\Filament\Resources\Content\PageResource\Pages;
use App\Filament\Resources\Content\PageResource\RelationManagers\SectionsRelationManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresHeartWellAdminUx;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;
    use ConfiguresReorderableTables;
    use ProvidesAdminGuidance;

    protected static ?string $model = Page::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 1;

    protected static function permissionPrefix(): string
    {
        return 'content.pages';
    }

    public static function getSubheading(): ?string
    {
        return 'Drag rows to change menu order on the website. Edit a page to manage its sections.';
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Page::query()->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Page details', 'heroicon-o-document-text', [
                    Forms\Components\TextInput::make('title')
                        ->label('Page title (admin only)')
                        ->required()
                        ->maxLength(255)
                        ->helperText('Used in the admin panel. Visitors see section headlines instead.')
                        ->prefixIcon('heroicon-o-book-open'),
                    Forms\Components\TextInput::make('slug')
                        ->label('URL slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->helperText('The web address path, e.g. "contact" for /contact')
                        ->prefixIcon('heroicon-o-link'),
                ]),
                static::formSection('Search & social (SEO)', 'heroicon-o-magnifying-glass', [
                    Forms\Components\TextInput::make('meta_title')
                        ->label('Browser tab title')
                        ->maxLength(255)
                        ->helperText('Shown in Google results and browser tabs. Leave blank to use page title.')
                        ->prefixIcon('heroicon-o-tag'),
                    Forms\Components\Textarea::make('meta_description')
                        ->label('Search description')
                        ->rows(3)
                        ->maxLength(500)
                        ->helperText('Short summary for Google and social sharing (max 500 characters).')
                        ->columnSpanFull(),
                    Forms\Components\FileUpload::make('og_image')
                        ->label('Social share image')
                        ->image()
                        ->disk('public')
                        ->directory('cms/pages')
                        ->helperText(ConfiguresHeartWellAdminUx::imageUploadHelper())
                        ->columnSpanFull(),
                ]),
                static::formSection('Publishing', 'heroicon-o-eye', [
                    Forms\Components\Toggle::make('is_published')
                        ->label('Show on website')
                        ->default(true)
                        ->helperText('Turn off to hide this page from the public site.'),
                ], 1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::applyReorderableSort(static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_published')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Page $record) => $record->slug === 'home' ? url('/') : url('/'.$record->slug))
                    ->openUrlInNewTab(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])));
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
