<?php

namespace App\Filament\Widgets;

use App\Domains\CRM\Enums\LeadStatus;
use App\Domains\CRM\Models\Lead;
use App\Filament\Resources\CRM\LeadResource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentLeadsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Recent Leads';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Lead::query()->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Name')
                    ->getStateUsing(fn (Lead $record) => $record->fullName()),
                Tables\Columns\TextColumn::make('email')
                    ->copyable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (LeadStatus $state) => $state->label())
                    ->color(fn (LeadStatus $state) => match ($state) {
                        LeadStatus::NewLead => 'gray',
                        LeadStatus::Contacted => 'info',
                        LeadStatus::ConsultationScheduled => 'warning',
                        LeadStatus::Booked => 'success',
                        LeadStatus::Completed => 'success',
                        LeadStatus::FollowUp => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip(),
            ])
            ->paginated([5])
            ->defaultPaginationPageOption(5)
            ->actions([
                Tables\Actions\Action::make('view')
                    ->label('View')
                    ->icon('heroicon-m-eye')
                    ->url(fn (Lead $record) => LeadResource::getUrl('edit', ['record' => $record])),
            ]);
    }
}
