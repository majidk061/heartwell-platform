<?php

namespace App\Filament\Resources\Content\PageResource\RelationManagers;

use App\Domains\Content\Actions\InsertSectionTemplateAction;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Filament\Concerns\ConfiguresHeartWellAdminUx;
use App\Filament\Resources\Content\SectionTemplateResource;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class SectionsRelationManager extends RelationManager
{
    use ConfiguresHeartWellAdminUx;

    protected static string $relationship = 'sections';

    protected static ?string $title = 'Page sections';

    protected static ?string $icon = 'heroicon-o-squares-2x2';

    public static function getDescription(): ?string
    {
        return 'Placement only — which library templates appear on this page and in what order. Edit headlines and body text in Section Library.';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('is_published')
                    ->label('Show on website')
                    ->default(true),
                Forms\Components\Hidden::make('sort_order'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn ($query) => $query->with('template'))
            ->recordTitleAttribute('section_type')
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->heading('')
            ->description(new HtmlString(view('filament.components.sections-instruction-banner')->render()))
            ->emptyStateHeading('No sections yet')
            ->emptyStateDescription('Insert a section from the Section Library to build this page.')
            ->columns([
                Tables\Columns\TextColumn::make('template.name')
                    ->label('Library template')
                    ->searchable()
                    ->placeholder('Not linked')
                    ->description(fn (PageSection $record): ?string => $record->isLinkedToLibrary()
                        ? null
                        : 'Link a template to show content on the site'),
                Tables\Columns\TextColumn::make('section_type')
                    ->label('Type')
                    ->badge(),
                Tables\Columns\IconColumn::make('section_template_id')
                    ->label('Linked')
                    ->boolean()
                    ->trueIcon('heroicon-o-link')
                    ->falseIcon('heroicon-o-exclamation-triangle')
                    ->trueColor('success')
                    ->falseColor('warning')
                    ->getStateUsing(fn (PageSection $record): bool => $record->isLinkedToLibrary()),
                Tables\Columns\IconColumn::make('is_published')
                    ->label('Live')
                    ->boolean(),
            ])
            ->headerActions([
                Tables\Actions\Action::make('insertFromLibrary')
                    ->label('Insert from library')
                    ->icon('heroicon-o-rectangle-stack')
                    ->color('primary')
                    ->form([
                        Forms\Components\Select::make('template_id')
                            ->label('Section template')
                            ->options(fn () => SectionTemplate::query()
                                ->where('is_published', true)
                                ->orderBy('sort_order')
                                ->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->helperText('Content is stored in Section Library — edits there update every page using this template.'),
                    ])
                    ->action(function (array $data): void {
                        $template = SectionTemplate::query()->findOrFail($data['template_id']);
                        app(InsertSectionTemplateAction::class)->execute(
                            $template,
                            (int) $this->getOwnerRecord()->getKey()
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('editContent')
                    ->label('Edit content')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->url(fn (PageSection $record): ?string => $record->section_template_id
                        ? SectionTemplateResource::getUrl('edit', [
                            'record' => $record->section_template_id,
                            'return_page_id' => $this->getOwnerRecord()->getKey(),
                        ])
                        : null)
                    ->visible(fn (PageSection $record): bool => $record->isLinkedToLibrary()),
                Tables\Actions\Action::make('changeTemplate')
                    ->label('Change template')
                    ->icon('heroicon-o-arrows-right-left')
                    ->form([
                        Forms\Components\Select::make('template_id')
                            ->label('Library template')
                            ->options(fn () => SectionTemplate::query()
                                ->orderBy('sort_order')
                                ->pluck('name', 'id'))
                            ->required()
                            ->searchable()
                            ->default(fn (PageSection $record): ?int => $record->section_template_id),
                    ])
                    ->action(function (PageSection $record, array $data): void {
                        $template = SectionTemplate::query()->findOrFail($data['template_id']);

                        $record->update([
                            'section_template_id' => $template->id,
                            'section_type' => $template->section_type,
                            'heading' => null,
                            'content' => null,
                        ]);
                    }),
                Tables\Actions\EditAction::make()
                    ->label('Placement')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->slideOver(),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove from page'),
            ]);
    }
}
