<?php

namespace App\Filament\Concerns;

use App\Filament\Pages\AdminGuide;
use Illuminate\Support\HtmlString;

trait ProvidesAdminGuidance
{
    protected static function guideLink(string $anchor = '', string $label = 'Help & Guide'): HtmlString
    {
        $url = AdminGuide::getUrl(['#'.ltrim($anchor, '#')]);

        return new HtmlString('<a href="'.$url.'" class="text-primary-600 hover:underline">'.$label.'</a>');
    }
}
