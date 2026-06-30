<?php

namespace App\Filament\Concerns;

use App\Domains\Content\Enums\ContentStatus;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

trait ConfiguresContentTableFilters
{
    /**
     * @return array<int, \Filament\Tables\Filters\SelectFilter>
     */
    protected static function contentStatusFilters(): array
    {
        return [
            \Filament\Tables\Filters\SelectFilter::make('status')
                ->label('Status')
                ->options([
                    ContentStatus::Published->value => ContentStatus::Published->label(),
                    ContentStatus::Draft->value => ContentStatus::Draft->label(),
                ]),
        ];
    }

    /**
     * @return array<string, Tab>
     */
    protected static function contentStatusTabs(): array
    {
        return [
            'all' => Tab::make('All'),
            'published' => Tab::make('Published')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ContentStatus::Published->value)),
            'draft' => Tab::make('Draft')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ContentStatus::Draft->value)),
        ];
    }

    /**
     * @return array<string, Tab>
     */
    protected static function sectionTemplateUsageTabs(): array
    {
        return array_merge(static::contentStatusTabs(), [
            'used' => Tab::make('Used on pages')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereHas('pageSections')),
            'unused' => Tab::make('Unused')
                ->modifyQueryUsing(fn (Builder $query) => $query->whereDoesntHave('pageSections')),
        ]);
    }
}
