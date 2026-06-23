<?php

namespace App\Filament\Concerns;

use Filament\Tables\Table;

trait ConfiguresHeartWellTables
{
    protected static function configureHeartWellTable(Table $table, bool $poll = false): Table
    {
        $table = $table
            ->striped()
            ->paginated([10, 25, 50])
            ->defaultPaginationPageOption(25)
            ->emptyStateHeading('No records yet')
            ->emptyStateDescription('New entries will appear here.')
            ->emptyStateIcon('heroicon-o-inbox');

        if ($poll) {
            $table->poll('60s');
        }

        return $table;
    }
}
