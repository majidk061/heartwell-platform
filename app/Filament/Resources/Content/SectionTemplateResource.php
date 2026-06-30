<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Enums\ContentStatus;
use App\Domains\Content\Models\SectionTemplate;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresContentPublishingTableActions;
use App\Filament\Concerns\ConfiguresContentTableFilters;
use App\Filament\Concerns\ConfiguresHeartWellAdminUx;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Concerns\ConfiguresReorderableTables;
use App\Filament\Concerns\ConfiguresSectionFields;
use App\Filament\Concerns\HasContentPublishingActions;
use App\Filament\Concerns\MutatesSectionContent;
use App\Filament\Resources\Content\Concerns\RevisionsRelationManager;
use App\Filament\Resources\Content\SectionTemplateResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class SectionTemplateResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresContentPublishingTableActions;
    use ConfiguresContentTableFilters;
    use ConfiguresHeartWellAdminUx;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;
    use ConfiguresReorderableTables;
    use ConfiguresSectionFields;
    use MutatesSectionContent;

    protected static ?string $model = SectionTemplate::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Section Library';

    protected static ?int $navigationSort = 2;

    protected static function permissionPrefix(): string
    {
        return 'content.pages';
    }

    public static function getSubheading(): ?string
    {
        return 'Single source of truth for section content. Edit here once — every linked page updates automatically. See Help & Guide → Section Library.';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(static::templateFormSchema());
    }

    /**
     * @return array<int, Forms\Components\Component>
     */
    public static function templateFormSchema(bool $includeTypeField = true): array
    {
        $details = [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description')
                ->rows(2)
                ->columnSpanFull(),
            Forms\Components\TextInput::make('heading')
                ->label('Section headline')
                ->maxLength(255)
                ->columnSpanFull(),
            HasContentPublishingActions::publishingStatusField(),
            HasContentPublishingActions::contentAuditPlaceholder(),
            Forms\Components\Placeholder::make('used_on_pages')
                ->label('Used on pages')
                ->content(function (?SectionTemplate $record): HtmlString {
                    if (! $record?->exists) {
                        return new HtmlString('<span class="text-sm text-gray-500">Save this template to see linked pages.</span>');
                    }

                    $pages = $record->pageSections()
                        ->with('page:id,title,slug')
                        ->get()
                        ->pluck('page')
                        ->filter()
                        ->unique('id')
                        ->sortBy('title');

                    if ($pages->isEmpty()) {
                        return new HtmlString('<span class="text-sm text-gray-500">Not placed on any page yet.</span>');
                    }

                    $links = $pages->map(fn ($page) => e($page->title))->join(', ');

                    return new HtmlString('<span class="text-sm">'.$links.'</span>');
                })
                ->columnSpanFull()
                ->visible(fn (?SectionTemplate $record): bool => $record?->exists ?? false),
        ];

        if ($includeTypeField) {
            array_splice($details, 2, 0, [
                Forms\Components\Select::make('section_type')
                    ->label('Section type')
                    ->options(static::sectionTypeOptions())
                    ->required()
                    ->live()
                    ->helperText('Pick the block layout. Create a new template when you need different text on another page.'),
            ]);
        } else {
            $details[] = Forms\Components\Hidden::make('section_type')->required();
        }

        return [
            static::formSection('Template details', 'heroicon-o-rectangle-stack', $details),
            static::formSection('Section content', 'heroicon-o-document-text', static::sectionContentFormSchema()),
            ...static::layoutFieldsetSchema(),
        ];
    }

    /**
     * @return array<int, Forms\Components\Component>
     */
    public static function sectionTypeWizardSchema(): array
    {
        return [
            Forms\Components\Radio::make('section_type')
                ->label('Choose section type')
                ->options(static::sectionTypeOptions())
                ->required()
                ->columns(1)
                ->helperText('Each type maps to a tested layout on the public site. You can create unlimited named templates per type.'),
        ];
    }

    public static function table(Table $table): Table
    {
        return static::applyReorderableSort(static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('section_type')->badge(),
                Tables\Columns\TextColumn::make('heading')->limit(40)->searchable(),
                Tables\Columns\TextColumn::make('page_sections_count')
                    ->label('Used on')
                    ->counts('pageSections')
                    ->suffix(' pages'),
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
            ->filters(array_merge(
                static::contentStatusFilters(),
                [
                    Tables\Filters\SelectFilter::make('section_type')
                        ->label('Section type')
                        ->options(static::sectionTypeOptions())
                        ->multiple(),
                    Tables\Filters\TernaryFilter::make('used')
                        ->label('Used on pages')
                        ->queries(
                            true: fn ($query) => $query->whereHas('pageSections'),
                            false: fn ($query) => $query->whereDoesntHave('pageSections'),
                        ),
                ],
            ))
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RevisionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSectionTemplates::route('/'),
            'create' => Pages\CreateSectionTemplate::route('/create'),
            'edit' => Pages\EditSectionTemplate::route('/{record}/edit'),
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function mutateTemplateData(array $data): array
    {
        return static::mutateSectionContent($data);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public static function hydrateTemplateData(array $data): array
    {
        return static::hydrateSectionContent($data);
    }
}
