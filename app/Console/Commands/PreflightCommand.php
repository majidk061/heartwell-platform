<?php

namespace App\Console\Commands;

use App\Domains\Content\Models\SiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PreflightCommand extends Command
{
    protected $signature = 'heartwell:preflight';

    protected $description = 'Run production readiness checks';

    public function handle(): int
    {
        $checks = [
            'Storage linked' => is_link(public_path('storage')),
            'Sitemap exists' => File::exists(public_path('sitemap.xml')),
            'Site settings seeded' => SiteSetting::query()->exists(),
            'APP_KEY set' => filled(config('app.key')),
        ];

        $failed = false;

        foreach ($checks as $label => $ok) {
            $this->line($ok ? "✓ {$label}" : "✗ {$label}");
            $failed = $failed || ! $ok;
        }

        return $failed ? self::FAILURE : self::SUCCESS;
    }
}
