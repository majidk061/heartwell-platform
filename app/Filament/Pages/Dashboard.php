<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ContentQuickStartWidget;
use App\Filament\Widgets\HeartWellStatsOverview;
use App\Filament\Widgets\RecentLeadsWidget;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?string $title = 'Dashboard';

    public function getSubheading(): ?string
    {
        return 'Manage your website content, leads, and automations from here.';
    }

    public function getWidgets(): array
    {
        return [
            ContentQuickStartWidget::class,
            HeartWellStatsOverview::class,
            RecentLeadsWidget::class,
            AccountWidget::class,
        ];
    }

    public function getColumns(): int|string|array
    {
        return [
            'default' => 1,
            'sm' => 2,
            'xl' => 2,
        ];
    }
}
