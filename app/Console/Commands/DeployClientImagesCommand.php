<?php

namespace App\Console\Commands;

use App\Domains\Content\Models\AvatarCard;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Models\SupportPathway;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class DeployClientImagesCommand extends Command
{
    protected $signature = 'heartwell:deploy-client-images
                            {--source= : Directory containing client image files}
                            {--dry-run : Show planned changes without writing}';

    protected $description = 'Copy client-supplied images into CMS storage and wire database paths (no layout changes)';

    /**
     * @return array<string, array{file: string, destination: string, type: string}>
     */
    private function assetMap(): array
    {
        return [
            'hero_home' => [
                'file' => 'worried_expression_-_hero-8ac28785-d939-42fe-955a-920b82606e1e.png',
                'destination' => 'cms/sections/hero-home.png',
                'type' => 'hero_home',
            ],
            'founder' => [
                'file' => 'Founder-bd27b0d9-2d18-4347-bc9f-c78df324f0fc.png',
                'destination' => 'cms/sections/founder-jacquie.png',
                'type' => 'founder',
            ],
            'depleted' => [
                'file' => 'depleted_woman-c29b9fe0-ebad-4f3a-85db-432be8c27adf.png',
                'destination' => 'cms/avatar-cards/depleted.png',
                'type' => 'avatar',
            ],
            'frustrated' => [
                'file' => 'weight_gain_frustrations-e652b766-1876-4e13-8598-74b065bf8cee.png',
                'destination' => 'cms/avatar-cards/frustrated.png',
                'type' => 'avatar',
            ],
            'confidence' => [
                'file' => 'aging_woman_in_mirror-af449e70-d1c9-4062-b6e7-2f63a1a762c7.png',
                'destination' => 'cms/avatar-cards/confidence.png',
                'type' => 'avatar',
            ],
            'recovery_hydration' => [
                'file' => 'replenish and restore.png',
                'destination' => 'cms/pathways/recovery-hydration.png',
                'type' => 'pathway',
                'slug' => 'recovery-hydration',
            ],
            'energy_wellness' => [
                'file' => 'Renewed Engergy.png',
                'destination' => 'cms/pathways/energy-wellness.png',
                'type' => 'pathway',
                'slug' => 'energy-wellness',
            ],
            'metabolic_weight' => [
                'file' => 'metabolic and  weight support image.png',
                'destination' => 'cms/pathways/metabolic-weight.png',
                'type' => 'pathway',
                'slug' => 'metabolic-weight',
            ],
            'individualized' => [
                'file' => 'individual and collaborative support.png',
                'destination' => 'cms/pathways/individualized-collaborative-care.png',
                'type' => 'pathway',
                'slug' => 'individualized-collaborative-care',
            ],
            'precision_glow' => [
                'file' => 'precision and glow final image.png',
                'destination' => 'cms/pathways/precision-glow-therapy.png',
                'type' => 'pathway',
                'slug' => 'precision-glow-therapy',
            ],
            'group_gathering' => [
                'file' => 'wellness group gathering.png',
                'destination' => 'cms/sections/group-gathering.png',
                'type' => 'experience_group',
            ],
            'private_visit' => [
                'file' => 'private wellness visit.png',
                'destination' => 'cms/sections/private-wellness-visit.png',
                'type' => 'experience_individual',
            ],
            'why_heartwell_hero' => [
                'file' => 'hero image why  heartwell.png',
                'destination' => 'cms/sections/why-heartwell-hero.png',
                'type' => 'why_heartwell_hero',
            ],
            'wj_hero_desktop' => [
                'file' => 'HeartWell Wellness Journey Desktop.png',
                'destination' => 'cms/sections/wellness-journey-hero-desktop.png',
                'type' => 'wj_hero_desktop',
            ],
            'wj_hero_mobile' => [
                'file' => 'HeartWell_Wellness_Journey_Hero_Mobile_Approved.png',
                'destination' => 'cms/sections/wellness-journey-hero-mobile.png',
                'type' => 'wj_hero_mobile',
            ],
            'wj_hero_clean' => [
                'file' => 'Women\'s Wellness Hero Image no text.png',
                'destination' => 'cms/sections/wellness-journey-hero-clean.png',
                'type' => 'wj_hero_clean',
            ],
            'wj_care_visit' => [
                'file' => 'private wellness visit.png',
                'destination' => 'cms/sections/wellness-journey-care-comes-to-you.png',
                'type' => 'wj_step5',
            ],
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

        foreach ($this->assetMap() as $key => $config) {
            $sourcePath = $this->resolveSourceFile($sourceDir, $config['file']);

            if ($sourcePath === null) {
                $this->warn("Missing source file: {$config['file']}");

                continue;
            }

            $destination = $config['destination'];

            if ($dryRun) {
                $this->line("Would copy {$config['file']} → storage/app/public/{$destination}");

                continue;
            }

            Storage::disk('public')->makeDirectory(dirname($destination));
            Storage::disk('public')->put($destination, File::get($sourcePath));
            $storedPaths[$key] = $destination;
            $this->line("Copied {$config['file']} → {$destination}");
        }

        if ($dryRun) {
            return self::SUCCESS;
        }

        $this->wireStoredPaths($storedPaths);
        $this->info('Client images deployed.');

        return self::SUCCESS;
    }

    /**
     * @param  array<string, string>  $storedPaths
     */
    private function wireStoredPaths(array $storedPaths): void
    {
        if (isset($storedPaths['hero_home'])) {
            $this->updateHeroTemplates($storedPaths['hero_home'], [
                'Hero — home banner',
                'Hero — full bleed overlay',
                'Hero — client split (home)',
            ]);
        }

        if (isset($storedPaths['founder'])) {
            $this->updateSectionTemplateImage('Founder teaser', $storedPaths['founder']);
            $this->updateSectionTemplateImage('Founder teaser — full page', $storedPaths['founder']);
        }

        if (isset($storedPaths['why_heartwell_hero'])) {
            $this->updateSectionTemplateImage('Hero — why heartwell', $storedPaths['why_heartwell_hero']);
        }

        if (isset($storedPaths['wj_hero_desktop'])) {
            $this->updateSectionTemplateImages('Hero — wellness journey artwork', [
                'image_url' => $storedPaths['wj_hero_desktop'],
                'image_url_mobile' => $storedPaths['wj_hero_mobile'] ?? null,
                'image_url_clean' => $storedPaths['wj_hero_clean'] ?? null,
            ]);
        }

        if (isset($storedPaths['wj_care_visit'])) {
            $this->updateSectionTemplateImage('Rich text — step 5 care comes to you', $storedPaths['wj_care_visit']);
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

        foreach ($this->assetMap() as $key => $config) {
            if (($config['type'] ?? '') !== 'pathway' || ! isset($storedPaths[$key], $config['slug'])) {
                continue;
            }

            SupportPathway::query()->where('slug', $config['slug'])->update([
                'image_path' => $storedPaths[$key],
            ]);
            $this->line("Updated pathway image: {$config['slug']}");
        }

        if (isset($storedPaths['private_visit'], $storedPaths['group_gathering'])) {
            $this->updateGroupIndividualComparison(
                $storedPaths['private_visit'],
                $storedPaths['group_gathering'],
            );
        }
    }

    private function resolveSourceDirectory(): ?string
    {
        $candidates = array_filter([
            $this->option('source'),
            '/home/majid/Downloads/heartwell',
            resource_path('images/client'),
        ]);

        foreach ($candidates as $candidate) {
            if (is_string($candidate) && is_dir($candidate)) {
                return realpath($candidate) ?: $candidate;
            }
        }

        return null;
    }

    private function resolveSourceFile(string $directory, string $filename): ?string
    {
        $direct = $directory.DIRECTORY_SEPARATOR.$filename;
        if (is_file($direct)) {
            return $direct;
        }

        $glob = glob($directory.DIRECTORY_SEPARATOR.'*'.basename($filename));
        if (is_array($glob) && isset($glob[0]) && is_file($glob[0])) {
            return $glob[0];
        }

        return null;
    }

    /**
     * @param  list<string>  $names
     */
    private function updateHeroTemplates(string $path, array $names): void
    {
        foreach ($names as $name) {
            $this->updateSectionTemplateImage($name, $path);
        }
    }

    private function updateSectionTemplateImage(string $name, string $path): void
    {
        $template = SectionTemplate::query()->where('name', $name)->first();
        if (! $template) {
            return;
        }

        $content = is_array($template->content) ? $template->content : [];
        $content['image_url'] = $path;
        $template->update(['content' => $content]);
        $this->line("Updated section template image: {$name}");
    }

    /**
     * @param  array<string, string|null>  $images
     */
    private function updateSectionTemplateImages(string $name, array $images): void
    {
        $template = SectionTemplate::query()->where('name', $name)->first();
        if (! $template) {
            return;
        }

        $content = is_array($template->content) ? $template->content : [];
        foreach ($images as $key => $path) {
            if (filled($path)) {
                $content[$key] = $path;
            }
        }
        $template->update(['content' => $content]);
        $this->line("Updated section template images: {$name}");
    }

    private function updateGroupIndividualComparison(string $individualPath, string $groupPath): void
    {
        $template = SectionTemplate::query()->where('name', 'Group vs individual comparison')->first();
        if (! $template) {
            return;
        }

        $content = is_array($template->content) ? $template->content : [];
        $columns = is_array($content['columns'] ?? null) ? $content['columns'] : [];

        foreach ($columns as $index => $column) {
            if (! is_array($column)) {
                continue;
            }

            $title = strtolower((string) ($column['title'] ?? ''));
            if (str_contains($title, 'individual')) {
                $columns[$index]['image_url'] = $individualPath;
            }
            if (str_contains($title, 'group')) {
                $columns[$index]['image_url'] = $groupPath;
            }
        }

        $content['columns'] = $columns;
        $template->update(['content' => $content]);
        $this->line('Updated Group vs individual comparison column images');
    }
}
