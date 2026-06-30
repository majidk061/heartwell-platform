<?php

use App\Domains\Content\Actions\GenerateSitemapAction;
use App\Domains\Content\Actions\GetSiteSettingsAction;
use App\Domains\Content\Support\SectionLayout;
use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', function () {
    $settings = app(GetSiteSettingsAction::class)->execute();
    $content = $settings['seo']['robots_txt_content'] ?? SectionLayout::defaultRobotsTxt();

    return response($content, 200)->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', function () {
    $xml = app(GenerateSitemapAction::class)->cached();

    return response($xml, 200)->header('Content-Type', 'application/xml');
});
