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
        return [Actions\CreateAction::make()];
    }

    public function getTabs(): array
    {
        $tab = fn (string $label, LeadStatus $status, string $icon, string $color) => Tab::make($label)
            ->icon($icon)
            ->modifyQueryUsing(fn (Builder $query) => $query->where('status', $status))
            ->badge(Lead::query()->where('status', $status)->count())
            ->badgeColor($color);

        return [
            'all' => Tab::make('All')->badge(Lead::query()->count()),
            'new' => $tab('New', LeadStatus::NewLead, 'heroicon-m-sparkles', 'gray'),
            'contacted' => $tab('Contacted', LeadStatus::Contacted, 'heroicon-m-phone', 'info'),
            'consultation' => $tab('Consultation', LeadStatus::ConsultationScheduled, 'heroicon-m-calendar-days', 'warning'),
            'booked' => $tab('Booked', LeadStatus::Booked, 'heroicon-m-check-circle', 'success'),
            'completed' => $tab('Completed', LeadStatus::Completed, 'heroicon-m-check-badge', 'success'),
            'follow_up' => $tab('Follow up', LeadStatus::FollowUp, 'heroicon-m-arrow-path', 'danger'),
        ];
    }
}
