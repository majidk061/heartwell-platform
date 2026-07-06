<?php

namespace App\Console\Commands;

use App\Domains\Content\Support\ClientCopyCatalog;
use Illuminate\Console\Command;

class RestoreHomeLayoutCommand extends Command
{
    protected $signature = 'heartwell:restore-home-layout
                            {--dry-run : Show what would change without writing}
                            {--stack=design : design = client mock layout, launch = copy-only launch stack}';

    protected $description = 'Restore the home page section stack (does not change Section Library design_variant settings)';

    public function handle(): int
    {
        $stack = strtolower((string) $this->option('stack'));

        $templateNames = match ($stack) {
            'launch' => ClientCopyCatalog::homeLaunchStack(),
            default => ClientCopyCatalog::homeDesignStack(),
        };

        $this->line('Restoring home page stack ('.$stack.'):');
        foreach ($templateNames as $name) {
            $this->line("  - {$name}");
        }

        return $this->call('heartwell:apply-launch-layout', [
            '--dry-run' => $this->option('dry-run'),
            '--force' => true,
            '--home-stack' => $stack,
            '--skip-privacy' => true,
        ]);
    }
}
