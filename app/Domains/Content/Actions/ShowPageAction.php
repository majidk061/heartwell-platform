<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\AvatarCard;
use App\Domains\Content\Models\Faq;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\SupportPathway;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

class ShowPageAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(string $slug): array
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->published()
            ->with(['publishedSections.template'])
            ->first();

        if (! $page) {
            throw new ModelNotFoundException("Page [{$slug}] not found or not published.");
        }

        $sections = app(ResolvePageSectionsAction::class)->execute($page->publishedSections);

        return $this->buildViewData($page, $sections, $slug);
    }

    /**
     * @param  Collection<int, \App\Domains\Content\Models\PageSection>  $sections
     * @return array<string, mixed>
     */
    public function buildViewData(Page $page, Collection $sections, string $slug): array
    {
        $siteSettings = app(GetSiteSettingsAction::class)->execute();

        $faqSection = $sections->firstWhere('section_type', 'faq');
        $faqs = collect();

        if ($faqSection) {
            $includeUnassigned = (bool) ($faqSection->content['include_unassigned'] ?? false);

            $faqs = Faq::query()
                ->published()
                ->where(function ($query) use ($slug, $includeUnassigned) {
                    $query->where('page_slug', $slug);

                    if ($includeUnassigned) {
                        $query->orWhereNull('page_slug');
                    }
                })
                ->orderBy('sort_order')
                ->get();
        }

        $testimonials = collect();
        $testimonialSettings = $siteSettings['home'] ?? [];
        if ($sections->contains('section_type', 'testimonials')) {
            $testimonialsSection = $sections->firstWhere('section_type', 'testimonials');
            $testimonialSettings = array_merge($testimonialSettings, $testimonialsSection?->content ?? []);
            $testimonials = app(GetPublishedTestimonialsAction::class)->execute(['home' => $testimonialSettings]);
        }

        $avatarCards = collect();
        if ($sections->contains('section_type', 'avatar_intro')) {
            $avatarCards = AvatarCard::query()
                ->published()
                ->orderBy('sort_order')
                ->get();

            if ($avatarCards->isEmpty()) {
                $avatarCards = collect(array_values(config('heartwell.avatar_cards')));
            }
        }

        return [
            'page' => $page,
            'sections' => $sections,
            'pathways' => $slug === 'support-pathways'
                ? SupportPathway::query()->published()->orderBy('sort_order')->get()
                : collect(),
            'faqs' => $faqs,
            'testimonials' => $testimonials,
            'testimonialSettings' => $testimonialSettings,
            'avatarCards' => $avatarCards,
            'siteSettings' => $siteSettings,
            'ctas' => $siteSettings['ctas'],
            'compliance' => $siteSettings['compliance'],
            'isPreview' => false,
        ];
    }
}
