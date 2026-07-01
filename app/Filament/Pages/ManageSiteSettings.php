<?php

namespace App\Filament\Pages;

use App\Domains\Content\Actions\GenerateSitemapAction;
use App\Domains\Content\Actions\GetSiteSettingsAction;
use App\Domains\Content\Models\SiteSetting;
use App\Domains\Content\Support\SectionLayout;
use App\Domains\Integrations\Services\SettingsResolver;
use App\Filament\Concerns\ConfiguresSectionFields;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSiteSettings extends Page implements HasForms
{
    use ConfiguresSectionFields;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Website Content';

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.manage-site-settings';

    protected static ?string $title = 'Site Settings';

    public static function canAccess(): bool
    {
        $user = auth()->user();

        if (! $user) {
            return false;
        }

        if (! \Illuminate\Support\Facades\Schema::hasTable('roles')) {
            return true;
        }

        return $user->hasRole('super_admin') || $user->can('content.site_settings.view');
    }

    /** @var array<string, mixed>|null */
    public ?array $data = [];

    public function getSubheading(): ?string
    {
        return 'Update your logo, menu, buttons, footer text, and Google settings — changes appear on the public website immediately.';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('back')
                ->label('Back to dashboard')
                ->icon('heroicon-o-arrow-left')
                ->color('gray')
                ->url(Dashboard::getUrl()),
            Actions\Action::make('preview')
                ->label('Preview website')
                ->icon('heroicon-o-arrow-top-right-on-square')
                ->url(url('/'))
                ->openUrlInNewTab(),
        ];
    }

    public function mount(): void
    {
        $settings = app(GetSiteSettingsAction::class)->execute();
        $resolver = app(SettingsResolver::class);

        $this->form->fill([
            'logo_mode' => $settings['branding']['logo_mode'] ?? 'text',
            'logo_text' => $settings['branding']['logo_text'] ?? 'HeartWell',
            'logo_tagline' => $settings['branding']['logo_tagline'] ?? ($settings['brand']['tagline'] ?? ''),
            'logo_image' => isset($settings['branding']['logo_image_path']) ? [$settings['branding']['logo_image_path']] : null,
            'logo_white' => isset($settings['branding']['logo_white_path']) ? [$settings['branding']['logo_white_path']] : null,
            'favicon' => isset($settings['branding']['favicon_path']) ? [$settings['branding']['favicon_path']] : null,
            'brand_name' => $settings['brand']['name'] ?? '',
            'brand_tagline' => $settings['brand']['tagline'] ?? '',
            'brand_promise' => $settings['brand']['promise'] ?? '',
            'navigation' => $settings['navigation'] ?? [],
            'cta_primary_label' => $settings['ctas']['primary']['label'] ?? '',
            'cta_primary_route' => $settings['ctas']['primary']['route'] ?? 'contact',
            'cta_primary_anchor' => $settings['ctas']['primary']['anchor'] ?? '#book',
            'cta_waitlist_label' => $settings['ctas']['secondary']['waitlist']['label'] ?? '',
            'cta_waitlist_anchor' => $settings['ctas']['secondary']['waitlist']['anchor'] ?? '#waitlist',
            'cta_consultation_label' => $settings['ctas']['secondary']['consultation']['label'] ?? '',
            'cta_consultation_anchor' => $settings['ctas']['secondary']['consultation']['anchor'] ?? '#consultation',
            'footer_email' => $settings['footer']['email'] ?? '',
            'footer_phone' => $settings['footer']['phone'] ?? '',
            'footer_address' => $settings['footer']['address'] ?? '',
            'social_links' => $settings['social'] ?? [],
            'footer_note' => $settings['compliance']['footer_note'] ?? '',
            'contact_disclaimer' => $settings['compliance']['contact_disclaimer'] ?? '',
            'privacy_summary' => $settings['compliance']['privacy_summary'] ?? '',
            'clinical_portal_note' => $settings['compliance']['clinical_portal_note'] ?? ($settings['compliance']['hydreight_note'] ?? ''),
            'group_intake_note' => $settings['compliance']['group_intake_note'] ?? '',
            'ga4_measurement_id' => $settings['seo']['ga4_measurement_id'] ?? $resolver->get('ga4_measurement_id', 'HEARTWELL_GA4_MEASUREMENT_ID'),
            'default_meta_title' => $settings['seo']['default_meta_title'] ?? '',
            'default_meta_description' => $settings['seo']['default_meta_description'] ?? '',
            'default_og_image' => isset($settings['seo']['default_og_image']) ? [$settings['seo']['default_og_image']] : null,
            'robots_index' => $settings['seo']['robots_index'] ?? true,
            'robots_txt_content' => $settings['seo']['robots_txt_content'] ?? SectionLayout::defaultRobotsTxt(),
            'sitemap_enabled' => $settings['seo']['sitemap_enabled'] ?? true,
            'sitemap_extra_urls' => $settings['seo']['sitemap_extra_urls'] ?? [
                ['path' => '/clinical-intake', 'priority' => 0.5, 'changefreq' => 'monthly'],
            ],
            'site_width' => $settings['theme']['site_width'] ?? 'standard',
            'default_container_width' => $settings['theme']['default_container_width'] ?? 'default',
            'default_section_padding' => $settings['theme']['default_section_padding'] ?? 'normal',
            'default_section_background' => $settings['theme']['default_section_background'] ?? 'white',
            'header_mode' => $settings['theme']['header_mode'] ?? 'sticky',
            'header_style' => $settings['theme']['header_style'] ?? 'solid_cream',
            'header_show_border' => $settings['theme']['header_show_border'] ?? true,
            'nav_hover_effect' => $settings['theme']['navigation_style']['hover_effect'] ?? 'color',
            'nav_hover_color' => $settings['theme']['navigation_style']['hover_color'] ?? '#e8967a',
            'nav_active_style' => $settings['theme']['navigation_style']['active_style'] ?? 'underline',
            'nav_active_color' => $settings['theme']['navigation_style']['active_color'] ?? '#e8967a',
            ...collect(static::defaultThemeColors())->mapWithKeys(fn ($color, $key) => ['theme_color_'.$key => $settings['theme']['colors'][$key] ?? $color])->all(),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Tabs::make('Settings')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Logo & Brand')
                            ->icon('heroicon-o-sparkles')
                            ->schema([
                                Forms\Components\Select::make('logo_mode')
                                    ->label('Logo style')
                                    ->options([
                                        'text' => 'Text only',
                                        'image' => 'Image only',
                                        'both' => 'Image + text',
                                    ])
                                    ->required()
                                    ->helperText('How your logo appears in the website header and footer.'),
                                Forms\Components\TextInput::make('logo_text')
                                    ->label('Logo text')
                                    ->helperText('Shown when logo style is Text or Image + text.'),
                                Forms\Components\TextInput::make('logo_tagline')
                                    ->label('Logo tagline')
                                    ->helperText('Small line under the logo, e.g. "For Every Stage of Life".'),
                                Forms\Components\FileUpload::make('logo_image')
                                    ->label('Logo image')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios(['3:4', '2:3', '10:3'])
                                    ->maxSize(2048)
                                    ->disk('public')
                                    ->directory('cms/branding')
                                    ->helperText(\App\Filament\Concerns\ConfiguresHeartWellAdminUx::logoUploadHelper()),
                                Forms\Components\FileUpload::make('logo_white')
                                    ->label('White logo (footer)')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios(['10:3'])
                                    ->maxSize(2048)
                                    ->disk('public')
                                    ->directory('cms/branding'),
                                Forms\Components\FileUpload::make('favicon')
                                    ->label('Favicon')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios(['1:1'])
                                    ->maxSize(512)
                                    ->disk('public')
                                    ->directory('cms/branding')
                                    ->helperText(\App\Filament\Concerns\ConfiguresHeartWellAdminUx::faviconUploadHelper()),
                                Forms\Components\TextInput::make('brand_name')
                                    ->label('Full site name')
                                    ->required()
                                    ->helperText('Used in page titles and legal text.'),
                                Forms\Components\TextInput::make('brand_tagline')
                                    ->label('Brand tagline'),
                                Forms\Components\Textarea::make('brand_promise')
                                    ->label('Brand promise')
                                    ->rows(2)
                                    ->helperText('Short line shown in the footer.'),
                            ])
                            ->columns(2),
                        Forms\Components\Tabs\Tab::make('Menu & Buttons')
                            ->icon('heroicon-o-bars-3')
                            ->schema([
                                Forms\Components\Repeater::make('navigation')
                                    ->label('Main menu items')
                                    ->schema([
                                        Forms\Components\TextInput::make('label')->label('Menu label')->required(),
                                        Forms\Components\TextInput::make('route')->label('Page route name')->required()
                                            ->helperText('Laravel route name, e.g. home, contact, support-pathways'),
                                    ])
                                    ->reorderable()
                                    ->columns(2)
                                    ->columnSpanFull(),
                                Forms\Components\Fieldset::make('Primary button')
                                    ->schema([
                                        Forms\Components\TextInput::make('cta_primary_label')->label('Button label'),
                                        Forms\Components\TextInput::make('cta_primary_route')->label('Route name'),
                                        Forms\Components\TextInput::make('cta_primary_anchor')->label('Anchor (optional)'),
                                    ])
                                    ->columns(3),
                                Forms\Components\Fieldset::make('Secondary buttons')
                                    ->schema([
                                        Forms\Components\TextInput::make('cta_waitlist_label')->label('Waitlist label'),
                                        Forms\Components\TextInput::make('cta_waitlist_anchor')->label('Waitlist anchor'),
                                        Forms\Components\TextInput::make('cta_consultation_label')->label('Consultation label'),
                                        Forms\Components\TextInput::make('cta_consultation_anchor')->label('Consultation anchor'),
                                    ])
                                    ->columns(2),
                            ]),
                        Forms\Components\Tabs\Tab::make('Theme & Layout')
                            ->icon('heroicon-o-paint-brush')
                            ->schema([
                                Forms\Components\Select::make('site_width')
                                    ->label('Site content width')
                                    ->options([
                                        'standard' => 'Standard (72rem)',
                                        'wide' => 'Wide (84rem)',
                                        'full' => 'Full width',
                                    ])
                                    ->default('standard'),
                                Forms\Components\Select::make('default_container_width')
                                    ->label('Default section container')
                                    ->options(static::layoutSelectOptions())
                                    ->default('default'),
                                Forms\Components\Select::make('default_section_padding')
                                    ->label('Default section padding')
                                    ->options([
                                        'none' => 'None',
                                        'compact' => 'Compact',
                                        'normal' => 'Normal',
                                        'spacious' => 'Spacious',
                                    ])
                                    ->default('normal'),
                                Forms\Components\Select::make('default_section_background')
                                    ->label('Default section background')
                                    ->options([
                                        'white' => 'White',
                                        'blush' => 'Blush',
                                        'dusty_blue' => 'Dusty blue',
                                        'taupe' => 'Taupe',
                                        'transparent' => 'Transparent',
                                    ])
                                    ->default('white'),
                                Forms\Components\Select::make('header_mode')
                                    ->label('Header behavior')
                                    ->options([
                                        'sticky' => 'Sticky (follows scroll)',
                                        'static' => 'Static (scrolls away)',
                                    ])
                                    ->default('sticky')
                                    ->helperText('Sticky keeps the header visible while scrolling.'),
                                Forms\Components\Select::make('header_style')
                                    ->label('Header background')
                                    ->options([
                                        'solid_cream' => 'Solid cream (client mock)',
                                        'solid' => 'Solid white',
                                        'transparent_blur' => 'Transparent with blur (over hero)',
                                        'transparent' => 'Fully transparent (over hero)',
                                    ])
                                    ->default('solid_cream')
                                    ->helperText('Transparent styles work best with sticky on hero banners.'),
                                Forms\Components\Toggle::make('header_show_border')
                                    ->label('Show header border')
                                    ->default(true),
                                Forms\Components\Fieldset::make('Navigation hover & active')
                                    ->schema([
                                        Forms\Components\Select::make('nav_hover_effect')
                                            ->label('Hover effect')
                                            ->options([
                                                'color' => 'Text color change',
                                                'underline' => 'Underline on hover',
                                                'background' => 'Background highlight',
                                                'pill' => 'Pill background',
                                            ])
                                            ->default('color'),
                                        Forms\Components\ColorPicker::make('nav_hover_color')
                                            ->label('Hover color')
                                            ->default('#e8967a'),
                                        Forms\Components\Select::make('nav_active_style')
                                            ->label('Active page style')
                                            ->options([
                                                'underline' => 'Underline (client mock)',
                                                'bold' => 'Bold text',
                                                'pill' => 'Pill background',
                                                'border_bottom' => 'Bottom border',
                                            ])
                                            ->default('underline'),
                                        Forms\Components\ColorPicker::make('nav_active_color')
                                            ->label('Active color')
                                            ->default('#e8967a'),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                                Forms\Components\Fieldset::make('Brand colors')
                                    ->schema(collect(static::themeColorFields())->map(
                                        fn ($label, $key) => Forms\Components\ColorPicker::make('theme_color_'.$key)->label($label)
                                    )->values()->all())
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                        Forms\Components\Tabs\Tab::make('Footer & Legal')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\TextInput::make('footer_email')->label('Contact email')->email(),
                                Forms\Components\TextInput::make('footer_phone')->label('Contact phone')->tel(),
                                Forms\Components\Textarea::make('footer_address')->label('Address')->rows(2),
                                Forms\Components\Repeater::make('social_links')
                                    ->label('Social media links')
                                    ->schema([
                                        Forms\Components\Select::make('platform')
                                            ->options([
                                                'instagram' => 'Instagram',
                                                'facebook' => 'Facebook',
                                                'linkedin' => 'LinkedIn',
                                                'youtube' => 'YouTube',
                                            ])
                                            ->required(),
                                        Forms\Components\TextInput::make('url')->label('URL')->url()->required(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                                Forms\Components\Textarea::make('footer_note')->label('Footer compliance note')->rows(3)->columnSpanFull(),
                                Forms\Components\Textarea::make('contact_disclaimer')->label('Contact form disclaimer')->rows(3)->columnSpanFull(),
                                Forms\Components\Textarea::make('privacy_summary')->label('Privacy summary')->rows(3)->columnSpanFull(),
                                Forms\Components\Textarea::make('clinical_portal_note')->label('Clinical portal note')->rows(3)->columnSpanFull(),
                                Forms\Components\Textarea::make('group_intake_note')->label('Group gathering intake note')->rows(3)->columnSpanFull(),
                            ]),
                        Forms\Components\Tabs\Tab::make('Search & Google')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\TextInput::make('ga4_measurement_id')
                                    ->label('Google Analytics 4 ID')
                                    ->placeholder('G-XXXXXXXXXX')
                                    ->helperText('Find this in Google Analytics → Admin → Data streams.'),
                                Forms\Components\TextInput::make('default_meta_title')
                                    ->label('Default page title suffix')
                                    ->helperText('Appended to page titles when a page has no custom title.'),
                                Forms\Components\Textarea::make('default_meta_description')
                                    ->label('Default search description')
                                    ->rows(3)
                                    ->columnSpanFull(),
                                Forms\Components\FileUpload::make('default_og_image')
                                    ->label('Default social share image')
                                    ->image()
                                    ->imageEditor()
                                    ->imageEditorAspectRatios(['1200:630', '16:9'])
                                    ->maxSize(2048)
                                    ->disk('public')
                                    ->directory('cms/seo')
                                    ->helperText(\App\Filament\Concerns\ConfiguresHeartWellAdminUx::ogImageUploadHelper())
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('robots_index')
                                    ->label('Allow search engines to index site (HTML meta)')
                                    ->default(true),
                                Forms\Components\Textarea::make('robots_txt_content')
                                    ->label('robots.txt content')
                                    ->rows(8)
                                    ->helperText('Controls crawler access rules. Separate from the HTML meta robots setting above.')
                                    ->columnSpanFull(),
                                Forms\Components\Toggle::make('sitemap_enabled')
                                    ->label('Enable dynamic sitemap')
                                    ->default(true),
                                Forms\Components\Repeater::make('sitemap_extra_urls')
                                    ->label('Extra sitemap URLs')
                                    ->schema([
                                        Forms\Components\TextInput::make('path')->label('Path')->required()->placeholder('/clinical-intake'),
                                        Forms\Components\TextInput::make('priority')->label('Priority')->numeric()->default(0.5),
                                        Forms\Components\Select::make('changefreq')
                                            ->label('Change frequency')
                                            ->options([
                                                'always' => 'Always',
                                                'hourly' => 'Hourly',
                                                'daily' => 'Daily',
                                                'weekly' => 'Weekly',
                                                'monthly' => 'Monthly',
                                            ])
                                            ->default('monthly'),
                                    ])
                                    ->columns(3)
                                    ->columnSpanFull(),
                                Forms\Components\Placeholder::make('sitemap_preview')
                                    ->label('Sitemap preview')
                                    ->content(fn () => url('/sitemap.xml'))
                                    ->columnSpanFull(),
                            ])
                            ->columns(2),
                    ])
                    ->columnSpanFull(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $resolver = app(SettingsResolver::class);

        SiteSetting::query()->updateOrCreate(['key' => 'branding'], ['value' => [
            'logo_mode' => $data['logo_mode'],
            'logo_text' => $data['logo_text'],
            'logo_tagline' => $data['logo_tagline'],
            'logo_image_path' => $this->firstUpload($data['logo_image'] ?? null),
            'logo_white_path' => $this->firstUpload($data['logo_white'] ?? null),
            'favicon_path' => $this->firstUpload($data['favicon'] ?? null),
        ]]);

        SiteSetting::query()->updateOrCreate(['key' => 'brand'], ['value' => [
            'name' => $data['brand_name'],
            'tagline' => $data['brand_tagline'],
            'promise' => $data['brand_promise'],
        ]]);

        SiteSetting::query()->updateOrCreate(['key' => 'navigation'], ['value' => $data['navigation'] ?? []]);

        SiteSetting::query()->updateOrCreate(['key' => 'ctas'], ['value' => [
            'primary' => [
                'label' => $data['cta_primary_label'],
                'route' => $data['cta_primary_route'],
                'anchor' => $data['cta_primary_anchor'],
            ],
            'secondary' => [
                'waitlist' => [
                    'label' => $data['cta_waitlist_label'],
                    'route' => 'contact',
                    'anchor' => $data['cta_waitlist_anchor'],
                ],
                'consultation' => [
                    'label' => $data['cta_consultation_label'],
                    'route' => 'contact',
                    'anchor' => $data['cta_consultation_anchor'],
                ],
            ],
        ]]);

        SiteSetting::query()->updateOrCreate(['key' => 'footer'], ['value' => [
            'email' => $data['footer_email'],
            'phone' => $data['footer_phone'],
            'address' => $data['footer_address'],
        ]]);

        SiteSetting::query()->updateOrCreate(['key' => 'social'], ['value' => $data['social_links'] ?? []]);

        SiteSetting::query()->updateOrCreate(['key' => 'compliance'], ['value' => [
            'footer_note' => $data['footer_note'],
            'contact_disclaimer' => $data['contact_disclaimer'],
            'privacy_summary' => $data['privacy_summary'],
            'clinical_portal_note' => $data['clinical_portal_note'] ?? '',
            'group_intake_note' => $data['group_intake_note'] ?? '',
        ]]);

        SiteSetting::query()->updateOrCreate(['key' => 'seo'], ['value' => [
            'ga4_measurement_id' => $data['ga4_measurement_id'],
            'default_meta_title' => $data['default_meta_title'],
            'default_meta_description' => $data['default_meta_description'],
            'default_og_image' => $this->firstUpload($data['default_og_image'] ?? null),
            'robots_index' => (bool) ($data['robots_index'] ?? true),
            'robots_txt_content' => $data['robots_txt_content'] ?? SectionLayout::defaultRobotsTxt(),
            'sitemap_enabled' => (bool) ($data['sitemap_enabled'] ?? true),
            'sitemap_extra_urls' => $data['sitemap_extra_urls'] ?? [],
        ]]);

        $colors = [];
        foreach (static::defaultThemeColors() as $key => $default) {
            $colors[$key] = $data['theme_color_'.$key] ?? $default;
        }

        SiteSetting::query()->updateOrCreate(['key' => 'theme'], ['value' => [
            'site_width' => $data['site_width'] ?? 'standard',
            'default_container_width' => $data['default_container_width'] ?? 'default',
            'default_section_padding' => $data['default_section_padding'] ?? 'normal',
            'default_section_background' => $data['default_section_background'] ?? 'white',
            'header_mode' => $data['header_mode'] ?? 'sticky',
            'header_style' => $data['header_style'] ?? 'solid_cream',
            'header_show_border' => (bool) ($data['header_show_border'] ?? true),
            'colors' => $colors,
            'navigation_style' => [
                'hover_effect' => $data['nav_hover_effect'] ?? 'color',
                'hover_color' => $data['nav_hover_color'] ?? '#e8967a',
                'active_style' => $data['nav_active_style'] ?? 'underline',
                'active_color' => $data['nav_active_color'] ?? '#e8967a',
                'header_cta_count' => 2,
            ],
        ]]);

        GenerateSitemapAction::forgetCache();

        if (! empty($data['ga4_measurement_id'])) {
            $resolver->set('ga4_measurement_id', $data['ga4_measurement_id'], 'seo', auth()->id());
        }

        Notification::make()->title('Site settings saved')->success()->send();
    }

    /**
     * @param  array<int, string>|string|null  $value
     */
    private function firstUpload(array|string|null $value): ?string
    {
        if (is_array($value)) {
            return $value[0] ?? null;
        }

        return $value;
    }
}
