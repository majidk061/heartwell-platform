<?php

namespace App\Console\Commands;

use App\Domains\Content\Models\AvatarCard;
use App\Domains\Content\Models\SectionTemplate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DeployClientImagesCommand extends Command
{
    protected $signature = 'heartwell:deploy-client-images
                            {--source= : Directory containing client image files}
                            {--dry-run : Show planned changes without writing}';

    protected $description = 'Copy client-supplied hero, avatar, and founder images into CMS storage and wire paths only (no layout changes)';

    /**
     * @return array<string, string>
     */
    private function sourceFileMap(): array
    {
        return [
            'hero' => 'worried_expression_-_hero-8ac28785-d939-42fe-955a-920b82606e1e.png',
            'depleted' => 'depleted_woman-c29b9fe0-ebad-4f3a-85db-432be8c27adf.png',
            'frustrated' => 'weight_gain_frustrations-e652b766-1876-4e13-8598-74b065bf8cee.png',
            'confidence' => 'aging_woman_in_mirror-af449e70-d1c9-4062-b6e7-2f63a1a762c7.png',
            'founder' => 'Founder-bd27b0d9-2d18-4347-bc9f-c78df324f0fc.png',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function destinationMap(): array
    {
        return [
            'hero' => 'cms/sections/hero-home.png',
            'depleted' => 'cms/avatar-cards/depleted.png',
            'frustrated' => 'cms/avatar-cards/frustrated.png',
            'confidence' => 'cms/avatar-cards/confidence.png',
            'founder' => 'cms/sections/founder-jacquie.png',
        ];
    }

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $sourceDir = $this->resolveSourceDirectory();

        if ($sourceDir === null) {
            $this->error('Client image source directory not found. Use --source= or place files in resources/images/client/');

            return self::FAILURE;
        }

        $this->info('Source: '.$sourceDir);

        $storedPaths = [];

        foreach ($this->sourceFileMap() as $key => $filename) {
            $sourcePath = $sourceDir.DIRECTORY_SEPARATOR.$filename;
            $destination = $this->destinationMap()[$key];

            if (! is_file($sourcePath)) {
                $this->warn("Missing source file: {$filename}");

                continue;
            }

            if ($dryRun) {
                $this->line("Would copy {$filename} → storage/app/public/{$destination}");

                continue;
            }

            Storage::disk('public')->makeDirectory(dirname($destination));
            Storage::disk('public')->put($destination, File::get($sourcePath));
            $storedPaths[$key] = $destination;
            $this->line("Copied {$filename} → {$destination}");
        }

        if ($dryRun) {
            return self::SUCCESS;
        }

        if (isset($storedPaths['hero'])) {
            $this->updateHeroTemplates($storedPaths['hero']);
        }

        if (isset($storedPaths['founder'])) {
            $this->updateFounderTemplates($storedPaths['founder']);
        }

        foreach (['depleted', 'frustrated', 'confidence'] as $slug) {
            if (isset($storedPaths[$slug])) {
                AvatarCard::query()->updateOrCreate(
                    ['slug' => $slug],
                    ['image_path' => $storedPaths[$slug]]
                );
                $this->line("Updated avatar card: {$slug}");
            }
        }

        $this->info('Client images deployed.');

        return self::SUCCESS;
    }

    private function resolveSourceDirectory(): ?string
    {
        $candidates = array_filter([
            $this->option('source'),
            resource_path('images/client'),
            base_path('../.cursor/projects/home-majid-Project-Poc-heartwell-platform/assets'),
        ]);

        foreach ($candidates as $candidate) {
            if (is_string($candidate) && is_dir($candidate)) {
                return realpath($candidate) ?: $candidate;
            }
        }

        return null;
    }

    private function updateHeroTemplates(string $path): void
    {
        $names = [
            'Hero — home banner',
            'Hero — full bleed overlay',
            'Hero — client split (home)',
        ];

        foreach ($names as $name) {
            $template = SectionTemplate::query()->where('name', $name)->first();
            if (! $template) {
                continue;
            }

            $content = is_array($template->content) ? $template->content : [];
            $content['image_url'] = $path;
            $template->update(['content' => $content]);
            $this->line("Updated hero template: {$name}");
        }
    }

    private function updateFounderTemplates(string $path): void
    {
        $names = ['Founder teaser', 'Founder teaser — full page'];

        foreach ($names as $name) {
            $template = SectionTemplate::query()->where('name', $name)->first();
            if (! $template) {
                continue;
            }

            $content = is_array($template->content) ? $template->content : [];
            $content['image_url'] = $path;
            $template->update(['content' => $content]);
            $this->line("Updated founder template: {$name}");
        }
    }
}
