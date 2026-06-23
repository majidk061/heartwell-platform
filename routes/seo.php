<?php

use Illuminate\Support\Facades\Route;

Route::get('/robots.txt', function () {
    $content = "User-agent: *\nAllow: /\nDisallow: /admin\nDisallow: /webhooks/\n\nSitemap: ".url('/sitemap.xml');

    return response($content, 200)->header('Content-Type', 'text/plain');
});

Route::get('/sitemap.xml', function () {
    if (! file_exists(public_path('sitemap.xml'))) {
        abort(404);
    }

    return response()->file(public_path('sitemap.xml'));
});
