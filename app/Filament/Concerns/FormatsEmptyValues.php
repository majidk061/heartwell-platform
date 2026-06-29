<?php

namespace App\Filament\Concerns;

final class FormatsEmptyValues
{
    public const EMPTY_PLACEHOLDER = '—';

    public static function emptyPlaceholder(): string
    {
        return self::EMPTY_PLACEHOLDER;
    }
}
