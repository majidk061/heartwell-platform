<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\Page;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresCmsImageFields;
use App\Filament\Concerns\ConfiguresContentPublishingTableActions;
use App\Filament\Concerns\ConfiguresContentTableFilters;
use App\Filament\Concerns\ConfiguresHeartWellAdminUx;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Concerns\ConfiguresReorderableTables;
use App\Filament\Concerns\HasContentPublishingActions;
use App\Filament\Resources\Content\Concerns\RevisionsRelationManager;
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
    use ConfiguresCmsImageFields;
    use ConfiguresContentPublishingTableActions;
    use ConfiguresContentTableFilters;
    use ConfiguresHeartWellAdminUx;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;
    use ConfiguresReorderableTables;

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
        return 'Drag rows to change menu order. Page sections are placement only — edit content in Section Library (Help & Guide → Page sections).';
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
                        ->helperText('The web address path, e.g. "contact" for /contact. Privacy Policy uses slug "privacy".')
                        ->prefixIcon('heroicon-o-link'),
                    HasContentPublishingActions::publishingStatusField(),
                    HasContentPublishingActions::contentAuditPlaceholder(),
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
                    static::cmsImagePreviewPlaceholder('og_image', 'Current social share image'),
                    static::cmsImageUploadField(
                        'og_image',
                        'Social share image',
                        'cms/pages',
                        ConfiguresHeartWellAdminUx::ogImageUploadHelper(),
                    )
                        ->imageEditor()
                        ->imageEditorAspectRatios(['1200:630', '16:9'])
                        ->columnSpanFull(),
                    Forms\Components\Select::make('robots_index')
                        ->label('Search indexing')
                        ->options([
                            '' => 'Use site default',
                            '1' => 'Index this page',
                            '0' => 'Noindex this page',
                        ])
                        ->formatStateUsing(fn ($state) => $state === null ? '' : ($state ? '1' : '0'))
                        ->dehydrateStateUsing(fn ($state) => $state === '' || $state === null ? null : $state === '1'),
                    Forms\Components\TextInput::make('canonical_url')
                        ->label('Canonical URL')
                        ->url()
                        ->placeholder('Leave blank to use current page URL'),
                    Forms\Components\TextInput::make('focus_keyword')
                        ->label('Focus keyword (admin helper)'),
                    Forms\Components\Select::make('og_type')
                        ->label('Open Graph type')
                        ->options(['website' => 'Website', 'article' => 'Article'])
                        ->default('website'),
                    Forms\Components\Select::make('twitter_card')
                        ->label('Twitter card type')
                        ->options([
                            'summary' => 'Summary',
                            'summary_large_image' => 'Summary large image',
                        ])
                        ->default('summary_large_image'),
                    Forms\Components\Select::make('schema_type')
                        ->label('Structured data type')
                        ->options([
                            'none' => 'None',
                            'WebPage' => 'WebPage',
                            'MedicalBusiness' => 'MedicalBusiness',
                        ])
                        ->default('none'),
                ]),
                static::formSection('Sitemap', 'heroicon-o-map', [
                    Forms\Components\Toggle::make('include_in_sitemap')
                        ->label('Include in sitemap')
                        ->default(true),
                    Forms\Components\TextInput::make('sitemap_priority')
                        ->label('Priority')
                        ->numeric()
                        ->minValue(0.1)
                        ->maxValue(1.0)
                        ->step(0.1)
                        ->default(0.8),
                    Forms\Components\Select::make('sitemap_changefreq')
                        ->label('Change frequency')
                        ->options([
                            'always' => 'Always',
                            'hourly' => 'Hourly',
                            'daily' => 'Daily',
                            'weekly' => 'Weekly',
                            'monthly' => 'Monthly',
                        ])
                        ->default('weekly'),
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
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (ContentStatus|string|null $state) => $state instanceof ContentStatus
                        ? $state->label()
                        : ucfirst($state ?? ContentStatus::Draft->value)),
                Tables\Columns\TextColumn::make('updatedBy.name')
                    ->label('Last editor')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last updated')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters(static::contentStatusFilters())
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('Preview live')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn (Page $record) => $record->slug === 'home' ? url('/') : url('/'.$record->slug))
                    ->openUrlInNewTab()
                    ->visible(fn (Page $record) => $record->isPublishedStatus()),
                Tables\Actions\Action::make('previewDraft')
                    ->label('Preview draft')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Page $record) => route('admin.preview.page', ['slug' => $record->slug]))
                    ->openUrlInNewTab()
                    ->visible(fn (Page $record) => $record->isDraft()),
                Tables\Actions\EditAction::make(),
                ...static::contentPublishingTableActions(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ...static::contentPublishingBulkActions(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])));
    }

    public static function getRelations(): array
    {
        return [
            SectionsRelationManager::class,
            RevisionsRelationManager::class,
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
