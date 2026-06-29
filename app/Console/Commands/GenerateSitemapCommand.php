<?php

namespace App\Console\Commands;

use App\Domains\Content\Models\Page;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'heartwell:sitemap';

    protected $description = 'Generate public/sitemap.xml for all published pages';

    public function handle(): int
    {
        $sitemap = Sitemap::create();

        $sitemap->add(Url::create('/')->setPriority(1.0));
        $sitemap->add(Url::create('/clinical-intake')->setPriority(0.5));

        Page::query()->where('is_published', true)->each(function (Page $page) use ($sitemap) {
            if ($page->slug === 'home') {
                return;
            }
            $sitemap->add(Url::create('/'.$page->slug)->setLastModificationDate($page->updated_at));
        });

        $path = public_path('sitemap.xml');
        $sitemap->writeToFile($path);

        $this->info("Sitemap written to {$path}");

        return self::SUCCESS;
    }
}
