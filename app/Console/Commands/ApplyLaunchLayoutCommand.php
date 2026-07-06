<?php

namespace App\Console\Commands;

use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Models\SiteSetting;
use App\Domains\Content\Support\ClientCopyCatalog;
use Illuminate\Console\Command;

class ApplyLaunchLayoutCommand extends Command
{
    protected $signature = 'heartwell:apply-launch-layout
                            {--dry-run : Show planned changes without writing}
                            {--force : Replace existing page sections (destructive — resets which sections are linked)}
                            {--home-stack= : Home stack: design (default) or launch — only used with --force}
                            {--skip-home : Do not touch the home page}
                            {--skip-privacy : Do not touch the privacy page}';

    protected $description = 'Ensure privacy CMS page exists and optionally reset page section links (use --force only when intended)';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');
        $force = (bool) $this->option('force');

        if ($dryRun) {
            $this->warn('Dry run — no database writes.');
        }

        if (! $this->option('skip-home') && $force) {
            $stack = $this->resolveHomeStack();
            $this->applyPageStack('home', $stack, $dryRun, force: true);
        } elseif (! $this->option('skip-home')) {
            $this->line('Home page sections unchanged (pass --force to replace).');
        }

        if (! $this->option('skip-privacy')) {
            $this->ensurePrivacyPage($dryRun, $force);
        }

        $this->info('Done.');

        return self::SUCCESS;
    }

    /**
     * @return list<string>
     */
    private function resolveHomeStack(): array
    {
        $choice = strtolower((string) ($this->option('home-stack') ?: 'design'));

        return match ($choice) {
            'launch' => ClientCopyCatalog::homeLaunchStack(),
            default => ClientCopyCatalog::homeDesignStack(),
        };
    }

    /**
     * @param  list<string>  $templateNames
     */
    private function applyPageStack(string $slug, array $templateNames, bool $dryRun, bool $force = false): void
    {
        $page = Page::query()->where('slug', $slug)->first();

        if (! $page) {
            if ($dryRun) {
                $this->line("Would create page: {$slug}");

                return;
            }

            $page = Page::query()->create([
                'slug' => $slug,
                'title' => $slug === 'privacy' ? 'Privacy Policy' : ucfirst(str_replace('-', ' ', $slug)),
                'meta_title' => ($slug === 'privacy' ? 'Privacy Policy' : ucfirst(str_replace('-', ' ', $slug))).' | HeartWell',
                'meta_description' => 'HeartWell Aesthetics & Wellness',
                'sort_order' => $slug === 'privacy' ? 8 : 1,
                'is_published' => true,
            ]);
            $this->line("Created page: {$slug}");
        }

        $existing = PageSection::query()
            ->where('page_id', $page->id)
            ->with('template')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (PageSection $section) => $section->template?->name ?? $section->section_type)
            ->all();

        if ($existing !== [] && ! $force) {
            $this->line("Page [{$slug}] already has sections — skipped (use --force to replace).");

            return;
        }

        $this->line("Page stack ({$slug}):");
        foreach ($templateNames as $name) {
            $this->line("  - {$name}");
        }

        if ($dryRun) {
            return;
        }

        PageSection::query()->where('page_id', $page->id)->delete();

        foreach ($templateNames as $index => $templateName) {
            $template = SectionTemplate::query()->where('name', $templateName)->first();

            if (! $template) {
                $this->error("Missing section template: {$templateName}. Run heartwell:sync-client-copy first.");

                return;
            }

            PageSection::query()->create([
                'page_id' => $page->id,
                'section_template_id' => $template->id,
                'section_type' => $template->section_type,
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);
        }

        $this->line("Updated page sections: {$slug}");
    }

    private function ensurePrivacyPage(bool $dryRun, bool $force): void
    {
        $page = Page::query()->where('slug', 'privacy')->first();
        $stack = ClientCopyCatalog::pageSectionStacks()['privacy'];

        if (! $page) {
            $this->applyPageStack('privacy', $stack, $dryRun, force: true);

            if (! $dryRun) {
                $this->migrateLegacyPrivacySettings($dryRun);
            }

            return;
        }

        $hasSections = PageSection::query()->where('page_id', $page->id)->exists();

        if (! $hasSections) {
            $this->applyPageStack('privacy', $stack, $dryRun, force: true);

            if (! $dryRun) {
                $this->migrateLegacyPrivacySettings($dryRun);
            }

            return;
        }

        if ($force) {
            $this->applyPageStack('privacy', $stack, $dryRun, force: true);

            return;
        }

        $this->line('Privacy page already configured — skipped (use --force to replace).');
    }

    private function migrateLegacyPrivacySettings(bool $dryRun): void
    {
        $compliance = SiteSetting::query()->where('key', 'compliance')->value('value');

        if (! is_array($compliance)) {
            return;
        }

        $legacyBody = $compliance['privacy_policy_body'] ?? null;
        $legacyTitle = $compliance['privacy_policy_title'] ?? null;

        if (blank($legacyBody) && blank($legacyTitle)) {
            return;
        }

        $template = SectionTemplate::query()->where('name', 'Rich text — privacy policy')->first();

        if (! $template) {
            return;
        }

        $content = is_array($template->content) ? $template->content : [];
        $currentBody = (string) ($content['body'] ?? '');
        $defaultBody = ClientCopyCatalog::defaultPrivacyPolicyHtml();

        if ($currentBody === $defaultBody && filled($legacyBody)) {
            $summary = $compliance['privacy_summary'] ?? config('heartwell.compliance.privacy_summary');
            $content['body'] = '<p>'.e((string) $summary).'</p>'.$legacyBody;

            if ($dryRun) {
                $this->line('Would migrate legacy privacy_policy_body into Rich text — privacy policy template.');

                return;
            }

            $template->update(['content' => $content]);
            $this->line('Migrated legacy privacy policy body into Section Library template.');
        }

        $heroTemplate = SectionTemplate::query()->where('name', 'Hero — privacy')->first();

        if ($heroTemplate && filled($legacyTitle) && $legacyTitle !== 'Privacy Policy') {
            if ($dryRun) {
                $this->line('Would update privacy hero heading from legacy site settings.');

                return;
            }

            $heroTemplate->update(['heading' => $legacyTitle]);
            $this->line('Updated privacy hero heading from legacy site settings.');
        }
    }
}
