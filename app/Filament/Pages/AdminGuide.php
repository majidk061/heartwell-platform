<?php

namespace App\Filament\Pages;

use App\Filament\Concerns\AuthorizesWithPermissions;
use Filament\Pages\Page;

class AdminGuide extends Page
{
    use AuthorizesWithPermissions;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Help & Guide';

    protected static ?int $navigationSort = 99;

    protected static string $view = 'filament.pages.admin-guide';

    protected static ?string $title = 'Help & Guide';

    public static function canAccess(): bool
    {
        return auth()->check();
    }

    protected static function permissionPrefix(): string
    {
        return '';
    }

    public function getSubheading(): ?string
    {
        return 'Step-by-step instructions for managing your HeartWell website.';
    }
}
