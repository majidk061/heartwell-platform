<?php

namespace App\Filament\Pages;

use App\Domains\Content\Actions\GetSiteSettingsAction;
use App\Domains\Content\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.manage-site-settings';

    protected static ?string $title = 'Site Settings';

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function mount(): void
    {
        $settings = app(GetSiteSettingsAction::class)->execute();

        $this->form->fill([
            'brand_name' => $settings['brand']['name'] ?? '',
            'brand_tagline' => $settings['brand']['tagline'] ?? '',
            'brand_promise' => $settings['brand']['promise'] ?? '',
            'navigation' => $settings['navigation'] ?? [],
            'cta_primary_label' => $settings['ctas']['primary']['label'] ?? '',
            'cta_primary_route' => $settings['ctas']['primary']['route'] ?? 'contact',
            'cta_waitlist_label' => $settings['ctas']['secondary']['waitlist']['label'] ?? '',
            'cta_consultation_label' => $settings['ctas']['secondary']['consultation']['label'] ?? '',
            'footer_note' => $settings['compliance']['footer_note'] ?? '',
            'contact_disclaimer' => $settings['compliance']['contact_disclaimer'] ?? '',
            'privacy_summary' => $settings['compliance']['privacy_summary'] ?? '',
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Brand')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Forms\Components\TextInput::make('brand_name')
                                    ->label('Site name')
                                    ->required()
                                    ->prefixIcon('heroicon-o-building-storefront'),
                                Forms\Components\TextInput::make('brand_tagline')
                                    ->prefixIcon('heroicon-o-chat-bubble-left-ellipsis'),
                                Forms\Components\Textarea::make('brand_promise')
                                    ->rows(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Navigation')
                            ->icon('heroicon-o-bars-3')
                            ->schema([
                                Forms\Components\Repeater::make('navigation')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')
                                            ->required()
                                            ->prefixIcon('heroicon-o-tag'),
                                        Forms\Components\TextInput::make('route')
                                            ->label('Route name')
                                            ->required()
                                            ->prefixIcon('heroicon-o-link'),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make('CTAs')
                            ->icon('heroicon-o-cursor-arrow-rays')
                            ->schema([
                                Forms\Components\TextInput::make('cta_primary_label')
                                    ->label('Primary button label')
                                    ->prefixIcon('heroicon-o-calendar'),
                                Forms\Components\TextInput::make('cta_primary_route')
                                    ->label('Primary route name')
                                    ->prefixIcon('heroicon-o-link'),
                                Forms\Components\TextInput::make('cta_waitlist_label')
                                    ->label('Waitlist button label')
                                    ->prefixIcon('heroicon-o-queue-list'),
                                Forms\Components\TextInput::make('cta_consultation_label')
                                    ->label('Consultation button label')
                                    ->prefixIcon('heroicon-o-phone'),
                            ])
                            ->columns(2),
                        Forms\Components\Tabs\Tab::make('Compliance')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Textarea::make('footer_note')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('contact_disclaimer')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('privacy_summary')
                                    ->rows(3)
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        SiteSetting::query()->updateOrCreate(
            ['key' => 'brand'],
            ['value' => [
                'name' => $data['brand_name'],
                'tagline' => $data['brand_tagline'],
                'promise' => $data['brand_promise'],
            ]],
        );

        SiteSetting::query()->updateOrCreate(
            ['key' => 'navigation'],
            ['value' => $data['navigation'] ?? []],
        );

        SiteSetting::query()->updateOrCreate(
            ['key' => 'ctas'],
            ['value' => [
                'primary' => [
                    'label' => $data['cta_primary_label'],
                    'route' => $data['cta_primary_route'],
                    'anchor' => '#book',
                ],
                'secondary' => [
                    'waitlist' => [
                        'label' => $data['cta_waitlist_label'],
                        'route' => 'contact',
                        'anchor' => '#waitlist',
                    ],
                    'consultation' => [
                        'label' => $data['cta_consultation_label'],
                        'route' => 'contact',
                        'anchor' => '#consultation',
                    ],
                ],
            ]],
        );

        SiteSetting::query()->updateOrCreate(
            ['key' => 'compliance'],
            ['value' => [
                'footer_note' => $data['footer_note'],
                'contact_disclaimer' => $data['contact_disclaimer'],
                'privacy_summary' => $data['privacy_summary'],
                'hydreight_note' => config('heartwell.compliance.hydreight_note'),
            ]],
        );

        Notification::make()
            ->title('Site settings saved')
            ->success()
            ->send();
    }
}
