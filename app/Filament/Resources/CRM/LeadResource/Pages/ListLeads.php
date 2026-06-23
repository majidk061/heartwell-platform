<?php

namespace App\Filament\Resources\CRM\LeadResource\Pages;

use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\Lead;
use App\Filament\Resources\CRM\LeadResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListLeads extends ListRecords
{
    protected static string $resource = LeadResource::class;

    public function getSubheading(): ?string
    {
        $count = Lead::query()->count();

        return "{$count} ".str('lead')->plural($count).' in pipeline';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(Lead::query()->count()),

            'new' => Tab::make('New')
                ->icon('heroicon-m-sparkles')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', LeadStatus::NewLead))
                ->badge(Lead::query()->where('status', LeadStatus::NewLead)->count())
                ->badgeColor('gray'),

            'contacted' => Tab::make('Contacted')
                ->icon('heroicon-m-phone')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', LeadStatus::Contacted))
                ->badge(Lead::query()->where('status', LeadStatus::Contacted)->count())
                ->badgeColor('info'),

            'booked' => Tab::make('Booked')
                ->icon('heroicon-m-calendar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', LeadStatus::Booked))
                ->badge(Lead::query()->where('status', LeadStatus::Booked)->count())
                ->badgeColor('success'),
        ];
    }
}
