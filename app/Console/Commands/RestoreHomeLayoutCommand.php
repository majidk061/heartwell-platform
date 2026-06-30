<?php

namespace App\Console\Commands;

use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use Illuminate\Console\Command;

class RestoreHomeLayoutCommand extends Command
{
    protected $signature = 'heartwell:restore-home-layout
                            {--dry-run : Show what would change without writing}';

    protected $description = 'Restore the home page to the classic section stack (undoes client-mock placements)';

    /** @var list<string> */
    private const CLASSIC_STACK = [
        'Hero — home banner',
        'Avatar intro block',
        'Intro — home nurse-led care',
        'Pathways teaser',
        'Testimonials — grid',
        'Founder teaser',
        'Standard CTA band',
    ];

    public function handle(): int
    {
        $page = Page::query()->where('slug', 'home')->first();

        if (! $page) {
            $this->error('Home page not found.');

            return self::FAILURE;
        }

        $current = PageSection::query()
            ->where('page_id', $page->id)
            ->with('template')
            ->orderBy('sort_order')
            ->get()
            ->map(fn (PageSection $section) => $section->template?->name ?? $section->section_type)
            ->all();

        $this->line('Current home sections:');
        foreach ($current as $name) {
            $this->line("  - {$name}");
        }

        $this->newLine();
        $this->line('Classic stack:');
        foreach (self::CLASSIC_STACK as $name) {
            $this->line("  - {$name}");
        }

        if ($this->option('dry-run')) {
            $this->info('Dry run — no changes written.');

            return self::SUCCESS;
        }

        if (! $this->confirm('Replace home page sections with the classic stack?', true)) {
            $this->warn('Cancelled.');

            return self::SUCCESS;
        }

        PageSection::query()->where('page_id', $page->id)->delete();

        foreach (self::CLASSIC_STACK as $index => $templateName) {
            $template = SectionTemplate::query()->where('name', $templateName)->first();

            if (! $template) {
                $this->error("Missing section template: {$templateName}");

                return self::FAILURE;
            }

            PageSection::query()->create([
                'page_id' => $page->id,
                'section_template_id' => $template->id,
                'section_type' => $template->section_type,
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);
        }

        $this->info('Home page restored to classic layout.');

        return self::SUCCESS;
    }
}
