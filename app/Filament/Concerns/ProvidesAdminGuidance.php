<?php

namespace App\Filament\Concerns;

use App\Filament\Pages\AdminGuide;
use Illuminate\Support\HtmlString;

trait ProvidesAdminGuidance
{
    protected static function guideLink(string $anchor = '', string $label = 'Help & Guide'): HtmlString
    {
        $url = AdminGuide::getUrl().'#'.ltrim($anchor, '#');

        return new HtmlString('<a href="'.$url.'" class="text-primary-600 hover:underline font-medium">'.$label.'</a>');
    }

    protected static function guideBanner(string $anchor, string $message): HtmlString
    {
        return new HtmlString(
            '<div class="rounded-lg border border-primary-200 bg-primary-50/60 px-4 py-3 text-sm text-gray-700 mb-2">'
            .$message.' '.static::guideLink($anchor, 'Open guide section').'</div>'
        );
    }
}
