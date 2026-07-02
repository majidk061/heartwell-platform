<?php

namespace App\Filament\Resources\Content;

use App\Domains\Content\Models\SupportPathway;
use App\Domains\CRM\Enums\AvatarType;
use App\Filament\Concerns\AuthorizesWithPermissions;
use App\Filament\Concerns\ConfiguresCmsImageFields;
use App\Filament\Concerns\ConfiguresHeartWellForms;
use App\Filament\Concerns\ConfiguresHeartWellTables;
use App\Filament\Concerns\ConfiguresReorderableTables;
use App\Filament\Resources\Content\SupportPathwayResource\Pages;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SupportPathwayResource extends Resource
{
    use AuthorizesWithPermissions;
    use ConfiguresCmsImageFields;
    use ConfiguresHeartWellForms;
    use ConfiguresHeartWellTables;
    use ConfiguresReorderableTables;

    protected static ?string $model = SupportPathway::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?int $navigationSort = 2;

    protected static function permissionPrefix(): string
    {
        return 'content.pathways';
    }

    public static function getSubheading(): ?string
    {
        return 'Drag rows to change pathway order on the website.';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                static::formSection('Pathway details', 'heroicon-o-map', [
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true)
                        ->prefixIcon('heroicon-o-link'),
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-book-open'),
                    Forms\Components\Textarea::make('intro')
                        ->rows(4)
                        ->helperText('Overview paragraph shown on pathway cards.')
                        ->columnSpanFull(),
                    Forms\Components\TextInput::make('tagline')
                        ->maxLength(255)
                        ->helperText('One-line “who it’s for” label, e.g. “For feeling depleted, foggy, sluggish…”')
                        ->prefixIcon('heroicon-o-user'),
                    static::cmsImagePreviewPlaceholder('image_path', 'Current pathway image'),
                    static::cmsImageUploadField(
                        'image_path',
                        'Pathway image',
                        'cms/pathways',
                        \App\Filament\Concerns\ConfiguresHeartWellAdminUx::pathwayUploadHelper(),
                    )->imageEditorAspectRatios(['16:9'])->columnSpanFull(),
                ]),
                static::formSection('Pathway card content', 'heroicon-o-document-text', [
                    Forms\Components\TagsInput::make('options_may_include')
                        ->label('Options may include')
                        ->helperText('Use “supports” language — avoid claims that treat, cure, or diagnose.')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('common_support')
                        ->label('Common support options')
                        ->rows(4)
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('portal_cue')
                        ->label('Portal cue')
                        ->rows(3)
                        ->helperText('What clients may see in the secure Hydreight intake portal.')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('selection_note')
                        ->label('Selection note (optional)')
                        ->rows(2)
                        ->helperText('e.g. B12 vs B-Complex guidance for Energy pathway.')
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('coming_soon')
                        ->label('Coming soon note (optional)')
                        ->rows(2)
                        ->columnSpanFull(),
                ], 1),
                static::formSection('Legacy accordion (optional)', 'heroicon-o-list-bullet', [
                    Forms\Components\Repeater::make('accordion_content')
                        ->schema([
                            Forms\Components\TextInput::make('heading')
                                ->required()
                                ->prefixIcon('heroicon-o-bars-3'),
                            Forms\Components\Textarea::make('body')
                                ->rows(3),
                        ])
                        ->helperText('Used only when pathway card fields are empty (classic accordion layout).')
                        ->columnSpanFull(),
                ], 1),
                static::formSection('CTA & publishing', 'heroicon-o-cursor-arrow-rays', [
                    Forms\Components\Select::make('avatar_type')
                        ->options(collect(AvatarType::cases())->mapWithKeys(
                            fn (AvatarType $type) => [$type->value => $type->label()]
                        ))
                        ->prefixIcon('heroicon-o-heart'),
                    Forms\Components\TextInput::make('cta_label')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-cursor-arrow-rays'),
                    Forms\Components\TextInput::make('cta_url')
                        ->maxLength(255)
                        ->prefixIcon('heroicon-o-link'),
                    Forms\Components\Toggle::make('is_published')
                        ->default(true),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return static::applyReorderableSort(static::configureHeartWellTable($table
            ->columns([
                Tables\Columns\TextColumn::make('title')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\TextColumn::make('avatar_type')->badge(),
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
            'index' => Pages\ListSupportPathways::route('/'),
            'create' => Pages\CreateSupportPathway::route('/create'),
            'edit' => Pages\EditSupportPathway::route('/{record}/edit'),
        ];
    }
}
