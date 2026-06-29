<?php

namespace App\Filament\Resources\Content\PageResource\RelationManagers;

use App\Domains\Content\Models\PageSection;
use App\Filament\Concerns\ConfiguresHeartWellAdminUx;
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
        return 'Add and edit content blocks for this page. Use Edit content on each row; drag rows to reorder.';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('section_type')
                    ->label('Section type')
                    ->options(static::sectionTypeOptions())
                    ->required()
                    ->live()
                    ->helperText('Choose the layout block that best matches what you want on the public page.')
                    ->prefixIcon('heroicon-o-squares-2x2'),
                Forms\Components\TextInput::make('heading')
                    ->label('Section headline')
                    ->maxLength(255)
                    ->helperText('Main title visitors see for this section.')
                    ->prefixIcon('heroicon-o-bars-3-bottom-left'),
                Forms\Components\Hidden::make('sort_order')
                    ->default(fn () => (int) PageSection::query()->max('sort_order') + 1),
                Forms\Components\Toggle::make('is_published')
                    ->label('Show on website')
                    ->default(true),
                Forms\Components\TextInput::make('content_subheading')
                    ->label('Subheading')
                    ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser', 'avatar_intro', 'cta']))
                    ->prefixIcon('heroicon-o-chat-bubble-bottom-center-text'),
                Forms\Components\Textarea::make('content_body')
                    ->label('Body text')
                    ->rows(4)
                    ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser', 'cta', 'avatar_intro']))
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content_rich_body')
                    ->label('Content')
                    ->toolbarButtons(['bold', 'italic', 'link', 'bulletList', 'orderedList'])
                    ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['rich_text', 'group_individual']))
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('content_image')
                    ->label('Section image')
                    ->disk('public')
                    ->directory('cms/sections')
                    ->visibility('public')
                    ->image()
                    ->imageEditor()
                    ->imageEditorAspectRatios(['4:3', '1:1'])
                    ->maxSize(2048)
                    ->helperText(static::imageUploadHelper())
                    ->visible(fn (Forms\Get $get) => in_array($get('section_type'), ['hero', 'intro', 'founder_teaser', 'rich_text']))
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('content_steps')
                    ->label('Journey steps')
                    ->schema([
                        Forms\Components\TextInput::make('title')->label('Step title')->required(),
                        Forms\Components\Textarea::make('description')->label('Step description')->rows(2),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('section_type') === 'journey')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('content_features')
                    ->label('Feature cards')
                    ->schema([
                        Forms\Components\TextInput::make('title')->required(),
                        Forms\Components\Textarea::make('body')->rows(2),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('section_type') === 'features')
                    ->columnSpanFull(),
                Forms\Components\Repeater::make('content_columns')
                    ->label('Comparison columns')
                    ->schema([
                        Forms\Components\TextInput::make('title')->required(),
                        Forms\Components\Textarea::make('body')->rows(3),
                    ])
                    ->maxItems(2)
                    ->visible(fn (Forms\Get $get) => $get('section_type') === 'group_individual')
                    ->columnSpanFull(),
                Forms\Components\TagsInput::make('content_credentials')
                    ->label('Credentials (e.g. BSN, RN, MBA)')
                    ->visible(fn (Forms\Get $get) => $get('section_type') === 'founder_teaser')
                    ->columnSpanFull(),
                Forms\Components\CheckboxList::make('content_forms')
                    ->label('Forms to show')
                    ->options([
                        'waitlist' => 'Waitlist',
                        'consultation' => 'Consultation',
                        'book' => 'Book appointment (Acuity)',
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
            ->heading('')
            ->description(new HtmlString(view('filament.components.sections-instruction-banner')->render()))
            ->emptyStateHeading('No sections yet')
            ->emptyStateDescription('Add sections to build this page. Start with a Hero section.')
            ->columns([
                Tables\Columns\TextColumn::make('section_type')->label('Type')->badge(),
                Tables\Columns\TextColumn::make('heading')
                    ->label('Headline')
                    ->searchable()
                    ->tooltip('Click Edit content to change this section'),
                Tables\Columns\IconColumn::make('is_published')->label('Live')->boolean(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Add section')
                    ->slideOver()
                    ->mutateFormDataUsing(function (array $data): array {
                        if (empty($data['sort_order'])) {
                            $pageId = $this->getOwnerRecord()->getKey();
                            $data['sort_order'] = (int) PageSection::query()
                                ->where('page_id', $pageId)
                                ->max('sort_order') + 1;
                        }

                        return $this->mutateSectionData($data);
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit content')
                    ->icon('heroicon-o-pencil-square')
                    ->color('primary')
                    ->slideOver()
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

        if (! empty($data['content_rich_body'])) {
            $content['body'] = $data['content_rich_body'];
        }

        if (! empty($data['content_image'])) {
            $path = is_array($data['content_image'])
                ? ($data['content_image'][0] ?? null)
                : $data['content_image'];
            $content['image_url'] = static::normalizeStoragePath($path);
        }

        if (! empty($data['content_steps'])) {
            $content['steps'] = collect($data['content_steps'])
                ->map(fn (array $step) => [
                    'title' => $step['title'] ?? '',
                    'description' => $step['description'] ?? null,
                ])
                ->filter(fn (array $step) => $step['title'] !== '')
                ->values()
                ->all();
        }

        if (! empty($data['content_features'])) {
            $content['features'] = $data['content_features'];
        }

        if (! empty($data['content_columns'])) {
            $content['columns'] = $data['content_columns'];
        }

        if (! empty($data['content_credentials'])) {
            $content['credentials'] = $data['content_credentials'];
        }

        if (! empty($data['content_forms'])) {
            $content['forms'] = $data['content_forms'];
        }

        $data['content'] = $content;

        unset(
            $data['content_subheading'],
            $data['content_body'],
            $data['content_rich_body'],
            $data['content_image'],
            $data['content_steps'],
            $data['content_features'],
            $data['content_columns'],
            $data['content_credentials'],
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
        $data['content_rich_body'] = in_array($data['section_type'] ?? '', ['rich_text', 'group_individual'])
            ? ($content['body'] ?? null)
            : null;
        $data['content_image'] = isset($content['image_url'])
            ? [static::normalizeStoragePath($content['image_url'])]
            : null;
        $data['content_steps'] = $content['steps'] ?? [];
        $data['content_features'] = $content['features'] ?? [];
        $data['content_columns'] = $content['columns'] ?? [];
        $data['content_credentials'] = $content['credentials'] ?? [];
        $data['content_forms'] = $content['forms'] ?? [];

        return $data;
    }

    protected static function normalizeStoragePath(?string $path): ?string
    {
        if (blank($path)) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $path = parse_url($path, PHP_URL_PATH) ?: $path;
        }

        return ltrim(str_replace('/storage/', '', $path), '/');
    }
}
