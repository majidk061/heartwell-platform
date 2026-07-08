<?php

namespace App\Console\Commands;

use App\Domains\Content\Models\AvatarCard;
use App\Domains\Content\Models\Faq;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Models\SiteSetting;
use App\Domains\Content\Models\SupportPathway;
use App\Domains\Content\Support\ClientCopyCatalog;
use Illuminate\Console\Command;

class SyncClientCopyCommand extends Command
{
    protected $signature = 'heartwell:sync-client-copy
                            {--attach-missing-sections= : Append missing section templates to a page slug (e.g. support-pathways)}
                            {--dry-run : Show planned changes without writing}';

    protected $description = 'Apply finalized client copy to pathways, section templates, FAQs, and avatar cards without resetting page layouts';

    public function handle(): int
    {
        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Dry run — no database writes.');
        }

        $this->syncSectionTemplates($dryRun);
        $this->syncSupportPathways($dryRun);
        $this->syncAvatarCards($dryRun);
        $this->syncFaqs($dryRun);
        $this->syncSiteSettings($dryRun);
        $this->publishHomeTrustSection($dryRun);

        $attachPage = $this->option('attach-missing-sections');
        if (is_string($attachPage) && $attachPage !== '') {
            $this->attachMissingSections($attachPage, $dryRun);
        }

        $this->info('Client copy sync complete.');

        return self::SUCCESS;
    }

    private function syncSectionTemplates(bool $dryRun): void
    {
        foreach (ClientCopyCatalog::sectionTemplates() as $name => $template) {
            $content = $template['content'];
            $layout = $content['layout'] ?? [];
            unset($content['layout']);

            $existing = SectionTemplate::query()->where('name', $name)->first();
            $existingImage = is_array($existing?->content ?? null)
                ? ($existing->content['image_url'] ?? null)
                : null;

            if (blank($content['image_url'] ?? null)
                && filled($existingImage)
                && ! str_starts_with((string) $existingImage, 'http')) {
                $content['image_url'] = $existingImage;
            }

            $existingVariant = is_array($existing?->content ?? null)
                ? ($existing->content['design_variant'] ?? null)
                : null;

            if (blank($content['design_variant'] ?? null) && filled($existingVariant)) {
                $content['design_variant'] = $existingVariant;
            }

            $payload = [
                'section_type' => $template['section_type'],
                'heading' => $template['heading'],
                'description' => $template['description'],
                'content' => $content,
                'layout' => $layout,
                'is_published' => true,
            ];

            if ($dryRun) {
                $this->line("Template: {$name}");

                continue;
            }

            SectionTemplate::query()->updateOrCreate(['name' => $name], $payload);
            $this->line("Updated template: {$name}");
        }
    }

    private function syncSupportPathways(bool $dryRun): void
    {
        $imageMap = [
            'recovery-hydration' => 'https://images.unsplash.com/photo-1506126613408-eca07ce68773?w=800&q=80',
            'energy-wellness' => 'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?w=800&q=80',
            'metabolic-weight' => null,
            'specialized-support' => null,
            'precision-glow-therapy' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&q=80',
        ];

        foreach (ClientCopyCatalog::supportPathways() as $index => $pathway) {
            $migrateFrom = $pathway['migrate_from_slug'] ?? null;
            unset($pathway['migrate_from_slug']);

            if ($migrateFrom && ! $dryRun) {
                $existing = SupportPathway::query()->where('slug', $migrateFrom)->first();
                if ($existing && $existing->slug !== $pathway['slug']) {
                    $existing->update(['slug' => $pathway['slug']]);
                    $this->line("Migrated pathway slug: {$migrateFrom} → {$pathway['slug']}");
                }
            }

            $slug = $pathway['slug'];
            $attributes = array_merge($pathway, [
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);

            if (array_key_exists('common_support', $pathway) && $pathway['common_support'] === null) {
                $attributes['common_support'] = null;
            }

            if (($imageMap[$slug] ?? null) && blank($attributes['image_path'] ?? null)) {
                $attributes['image_path'] = $imageMap[$slug];
            }

            if ($dryRun) {
                $this->line("Pathway: {$slug}");

                continue;
            }

            SupportPathway::query()->updateOrCreate(['slug' => $slug], $attributes);
            $this->line("Updated pathway: {$slug}");
        }

        if (! $dryRun) {
            SupportPathway::query()
                ->whereIn('slug', ['advanced-cellular', 'confidence-aesthetic'])
                ->delete();
        }
    }

    private function syncAvatarCards(bool $dryRun): void
    {
        $imageMap = [
            'depleted' => 'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?w=800&q=80',
            'frustrated' => 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?w=800&q=80',
            'confidence' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=800&q=80',
        ];

        foreach (ClientCopyCatalog::avatarCards() as $index => $card) {
            $slug = $card['slug'];
            $existing = AvatarCard::query()->where('slug', $slug)->first();
            $existingPath = $existing?->image_path;

            $attributes = array_merge($card, [
                'sort_order' => $index + 1,
                'is_published' => true,
            ]);

            if (filled($existingPath) && ! str_starts_with($existingPath, 'http')) {
                unset($attributes['image_path']);
            } else {
                $attributes['image_path'] = $imageMap[$slug] ?? null;
            }

            if ($dryRun) {
                $this->line("Avatar card: {$slug}");

                continue;
            }

            AvatarCard::query()->updateOrCreate(['slug' => $slug], $attributes);
            $this->line("Updated avatar card: {$slug}");
        }
    }

    private function syncFaqs(bool $dryRun): void
    {
        $catalogKeys = collect(ClientCopyCatalog::faqs())->pluck('key')->all();
        $catalogQuestions = collect(ClientCopyCatalog::faqs())->pluck('question')->all();

        $legacyQuestions = [
            'How do I get started with HeartWell?',
            'Is clinical clearance required?',
            'What if I am not sure which pathway fits me?',
            'Do you come to my location?',
            'Can I host a group wellness gathering?',
            'Are specific health or aesthetic results guaranteed?',
        ];

        if (! $dryRun) {
            Faq::query()->whereIn('question', $legacyQuestions)->delete();
            Faq::query()
                ->where(function ($query) {
                    $query->where('question', 'like', '%Mollitia%')
                        ->orWhere('question', 'like', '%lorem%');
                })
                ->delete();

            Faq::query()
                ->where('page_slug', 'wellness-journey')
                ->whereNotIn('question', $catalogQuestions)
                ->delete();
        }

        foreach (ClientCopyCatalog::faqs() as $index => $faq) {
            if ($dryRun) {
                $this->line("FAQ: {$faq['question']}");

                continue;
            }

            Faq::query()->updateOrCreate(
                ['question' => $faq['question']],
                [
                    'answer' => $faq['answer'],
                    'page_slug' => $faq['page_slug'],
                    'sort_order' => $index + 1,
                    'is_published' => true,
                ],
            );
            $this->line("Updated FAQ: {$faq['key']}");
        }

        if ($dryRun) {
            $this->line('FAQ keys: '.implode(', ', $catalogKeys));
        }
    }

    private function syncSiteSettings(bool $dryRun): void
    {
        $payloads = [
            'navigation' => ClientCopyCatalog::navigation(),
            'footer_columns' => ClientCopyCatalog::footerColumns(),
            'ctas' => ClientCopyCatalog::siteCtas(),
            'compliance' => ClientCopyCatalog::complianceDefaults(),
            'contact_forms' => ClientCopyCatalog::contactFormsDefaults(),
        ];

        foreach ($payloads as $key => $value) {
            if ($dryRun) {
                $this->line("Site setting: {$key}");

                continue;
            }

            SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
            $this->line("Updated site setting: {$key}");
        }
    }

    private function attachMissingSections(string $pageSlug, bool $dryRun): void
    {
        $stack = ClientCopyCatalog::pageSectionStacks()[$pageSlug] ?? null;

        if ($stack === null) {
            $this->error("No section stack defined for page: {$pageSlug}");

            return;
        }

        $page = Page::query()->where('slug', $pageSlug)->first();

        if (! $page) {
            $this->error("Page not found: {$pageSlug}");

            return;
        }

        $existingTemplateIds = PageSection::query()
            ->where('page_id', $page->id)
            ->pluck('section_template_id')
            ->all();

        $maxSort = (int) PageSection::query()->where('page_id', $page->id)->max('sort_order');

        foreach ($stack as $templateName) {
            $template = SectionTemplate::query()->where('name', $templateName)->first();

            if (! $template) {
                $this->warn("Missing template (run sync first): {$templateName}");

                continue;
            }

            if (in_array($template->id, $existingTemplateIds, true)) {
                continue;
            }

            $maxSort++;

            if ($dryRun) {
                $this->line("Would attach: {$templateName} to {$pageSlug}");

                continue;
            }

            PageSection::query()->create([
                'page_id' => $page->id,
                'section_template_id' => $template->id,
                'section_type' => $template->section_type,
                'sort_order' => $maxSort,
                'is_published' => true,
            ]);

            $this->line("Attached section: {$templateName} → {$pageSlug}");
        }
    }

    private function publishHomeTrustSection(bool $dryRun): void
    {
        $template = SectionTemplate::query()->where('name', 'Testimonials — grid')->first();

        if (! $template || empty($template->content['trust_features'])) {
            return;
        }

        $page = Page::query()->where('slug', 'home')->first();

        if (! $page) {
            return;
        }

        $section = PageSection::query()
            ->where('page_id', $page->id)
            ->where('section_template_id', $template->id)
            ->first();

        if (! $section) {
            return;
        }

        if ($dryRun) {
            $this->line('Would publish home trust section (Testimonials — grid slot).');

            return;
        }

        if (! $section->is_published) {
            $section->update(['is_published' => true]);
            $this->line('Published home trust section: What You Can Expect.');
        }
    }
}
