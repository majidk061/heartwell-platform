<?php

namespace App\Console\Commands;

use App\Domains\Content\Actions\GenerateSitemapAction;
use Illuminate\Console\Command;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'heartwell:sitemap';

    protected $description = 'Warm the sitemap cache for all published pages';

    public function handle(GenerateSitemapAction $action): int
    {
        GenerateSitemapAction::forgetCache();
        $xml = $action->cached();

        file_put_contents(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap cache warmed and written to public/sitemap.xml');

        return self::SUCCESS;
    }
}
