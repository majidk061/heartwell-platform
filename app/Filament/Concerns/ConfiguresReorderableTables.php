<?php

namespace App\Filament\Concerns;

use Filament\Tables\Table;

trait ConfiguresReorderableTables
{
    protected static function applyReorderableSort(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order');
    }
}
