<?php

namespace App\Filament\Widgets;

use App\Domains\Content\Models\Page;
use App\Filament\Pages\AdminGuide;
use App\Filament\Pages\ManageSiteSettings;
use App\Filament\Resources\Content\PageResource;
use App\Filament\Resources\Content\SupportPathwayResource;
use Filament\Widgets\Widget;

class ContentQuickStartWidget extends Widget
{
    protected static string $view = 'filament.widgets.content-quick-start';

    protected static ?int $sort = 0;

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<int, array{label: string, description: string, url: string, icon: string}>
     */
    public function getSteps(): array
    {
        return [
            [
                'label' => 'Site Settings',
                'description' => 'Logo, menu, buttons, footer text, and Google settings',
                'url' => ManageSiteSettings::getUrl(),
                'icon' => 'heroicon-o-cog-6-tooth',
            ],
            [
                'label' => 'Edit Home Page',
                'description' => 'Hero, intro, founder teaser, and page sections',
                'url' => PageResource::getUrl('edit', ['record' => Page::query()->where('slug', 'home')->value('id') ?? 1]),
                'icon' => 'heroicon-o-home',
            ],
            [
                'label' => 'How to edit page sections',
                'description' => 'Step-by-step guide for adding and reordering page sections',
                'url' => AdminGuide::getUrl().'#sections',
                'icon' => 'heroicon-o-book-open',
            ],
            [
                'label' => 'Support Pathways',
                'description' => 'Five pathway accordions shown across the site',
                'url' => SupportPathwayResource::getUrl('index'),
                'icon' => 'heroicon-o-map',
            ],
            [
                'label' => 'Preview website',
                'description' => 'Open the public homepage in a new tab',
                'url' => url('/'),
                'icon' => 'heroicon-o-arrow-top-right-on-square',
            ],
        ];
    }
}
