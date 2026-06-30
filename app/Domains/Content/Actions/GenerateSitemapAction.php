<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\Page;
use Illuminate\Support\Facades\Cache;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemapAction
{
    public function execute(): string
    {
        $settings = app(GetSiteSettingsAction::class)->execute();
        $seo = $settings['seo'] ?? [];

        if (($seo['sitemap_enabled'] ?? true) === false) {
            return '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"></urlset>';
        }

        $sitemap = Sitemap::create();

        $sitemap->add(
            Url::create('/')
                ->setPriority(1.0)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
        );

        foreach ($seo['sitemap_extra_urls'] ?? [] as $entry) {
            $path = $entry['path'] ?? null;
            if (blank($path)) {
                continue;
            }

            $sitemap->add(
                Url::create($path)
                    ->setPriority((float) ($entry['priority'] ?? 0.5))
                    ->setChangeFrequency($entry['changefreq'] ?? Url::CHANGE_FREQUENCY_MONTHLY)
            );
        }

        Page::query()
            ->published()
            ->where('include_in_sitemap', true)
            ->each(function (Page $page) use ($sitemap) {
                if ($page->slug === 'home') {
                    return;
                }

                $sitemap->add(
                    Url::create('/'.$page->slug)
                        ->setLastModificationDate($page->updated_at)
                        ->setPriority((float) ($page->sitemap_priority ?? 0.8))
                        ->setChangeFrequency($page->sitemap_changefreq ?? Url::CHANGE_FREQUENCY_WEEKLY)
                );
            });

        return $sitemap->render();
    }

    public function cached(): string
    {
        return Cache::remember('heartwell.sitemap.xml', 3600, fn () => $this->execute());
    }

    public static function forgetCache(): void
    {
        Cache::forget('heartwell.sitemap.xml');
    }
}
