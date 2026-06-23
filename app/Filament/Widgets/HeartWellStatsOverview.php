<?php

namespace App\Filament\Widgets;

use App\Domains\CRM\Models\ConsultationRequest;
use App\Domains\CRM\Models\Lead;
use App\Domains\CRM\Models\WaitlistEntry;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class HeartWellStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $newLeadsCount = Lead::query()
            ->where('created_at', '>=', now()->subDays(7))
            ->count();

        $openConsultations = ConsultationRequest::query()
            ->whereIn('status', ['pending', 'contacted'])
            ->count();

        return [
            Stat::make('Total Leads', Lead::query()->count())
                ->description('All CRM leads')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart([3, 5, 4, 6, 8, 7, $newLeadsCount ?: 1]),

            Stat::make('New Leads (7 days)', $newLeadsCount)
                ->description('Recent activity')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Waitlist', WaitlistEntry::query()->where('status', 'active')->count())
                ->description('Active waitlist entries')
                ->descriptionIcon('heroicon-m-queue-list')
                ->color('info'),

            Stat::make('Open Consultations', $openConsultations)
                ->description('Pending or contacted')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('warning'),
        ];
    }
}
