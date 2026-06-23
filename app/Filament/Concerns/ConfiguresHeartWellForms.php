<?php

namespace App\Filament\Concerns;

use Filament\Forms\Components\Section;

trait ConfiguresHeartWellForms
{
    /**
     * @param  array<int, \Filament\Forms\Components\Component>  $schema
     */
    protected static function formSection(string $label, string $icon, array $schema, int $columns = 2): Section
    {
        return Section::make($label)
            ->icon($icon)
            ->schema($schema)
            ->columns($columns)
            ->columnSpanFull();
    }
}
