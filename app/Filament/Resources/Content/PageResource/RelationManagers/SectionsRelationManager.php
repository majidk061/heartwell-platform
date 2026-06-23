<?php

namespace App\Filament\Resources\Content\PageResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class SectionsRelationManager extends RelationManager
{
    protected static string $relationship = 'sections';

    protected static ?string $title = 'Page Sections';

    protected static ?string $icon = 'heroicon-o-squares-2x2';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('section_type')
                    ->label('Section type')
                    ->options([
                        'hero' => 'Hero',
                        'intro' => 'Intro',
                        'journey' => 'Journey steps',
                        'founder_teaser' => 'Founder teaser',
                        'cta' => 'Call to action',
                        'forms' => 'Contact forms',
                    ])
                    ->required()
                    ->live()
                    ->prefixIcon('heroicon-o-squares-2x2'),
                Forms\Components\TextInput::make('heading')
                    ->maxLength(255)
                    ->prefixIcon('heroicon-o-bars-3-bottom-left'),
                Forms\Components\TextInput::make('sort_order')
                    ->numeric()
                    ->default(0)
                    ->prefixIcon('heroicon-o-arrows-up-down'),
                Forms\Components\Toggle::make('is_published')
                    ->default(true),
                Forms\Components\TextInput::make('content_subheading')
                    ->label('Subheading')
                    ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser']))
                    ->prefixIcon('heroicon-o-chat-bubble-bottom-center-text'),
                Forms\Components\Textarea::make('content_body')
                    ->label('Body text')
                    ->rows(4)
                    ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser', 'cta']))
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('content_image')
                    ->label('Section image')
                    ->image()
                    ->disk('public')
                    ->directory('cms/sections')
                    ->visibility('public')
                    ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser']))
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('content_steps')
                    ->label('Journey steps')
                    ->schema([
                        Forms\Components\TextInput::make('title')->required(),
                        Forms\Components\Textarea::make('description')->rows(2),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('section_type') === 'journey')
                    ->columnSpanFull(),
                Forms\Components\CheckboxList::make('content_forms')
                    ->label('Forms to show')
                    ->options([
                        'waitlist' => 'Waitlist',
                        'consultation' => 'Consultation',
                        'group_inquiry' => 'Group inquiry',
                    ])
                    ->visible(fn (Forms\Get $get) => $get('section_type') === 'forms')
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('heading')
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
                Tables\Columns\TextColumn::make('section_type')->badge(),
                Tables\Columns\TextColumn::make('heading')->searchable(),
                Tables\Columns\IconColumn::make('is_published')->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(fn (array $data) => $this->mutateSectionData($data)),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->mutateRecordDataUsing(fn (array $data) => $this->hydrateSectionData($data))
                    ->mutateFormDataUsing(fn (array $data) => $this->mutateSectionData($data)),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateSectionData(array $data): array
    {
        $content = [];

        if (! empty($data['content_subheading'])) {
            $content['subheading'] = $data['content_subheading'];
        }

        if (! empty($data['content_body'])) {
            $content['body'] = $data['content_body'];
        }

        if (! empty($data['content_image'])) {
            $content['image_url'] = is_array($data['content_image'])
                ? ($data['content_image'][0] ?? null)
                : $data['content_image'];
        }

        if (! empty($data['content_steps'])) {
            $content['steps'] = collect($data['content_steps'])
                ->pluck('title')
                ->filter()
                ->values()
                ->all();
        }

        if (! empty($data['content_forms'])) {
            $content['forms'] = $data['content_forms'];
        }

        $data['content'] = $content;

        unset(
            $data['content_subheading'],
            $data['content_body'],
            $data['content_image'],
            $data['content_steps'],
            $data['content_forms'],
        );

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function hydrateSectionData(array $data): array
    {
        $content = $data['content'] ?? [];

        $data['content_subheading'] = $content['subheading'] ?? null;
        $data['content_body'] = $content['body'] ?? null;
        $data['content_image'] = isset($content['image_url']) ? [$content['image_url']] : null;

        if (! empty($content['steps']) && is_array($content['steps'])) {
            $data['content_steps'] = collect($content['steps'])
                ->map(fn ($step) => is_array($step) ? $step : ['title' => $step, 'description' => null])
                ->all();
        }

        $data['content_forms'] = $content['forms'] ?? [];

        return $data;
    }
}
