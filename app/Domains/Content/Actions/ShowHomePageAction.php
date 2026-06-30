<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\AvatarCard;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\SupportPathway;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowHomePageAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        $page = Page::query()
            ->where('slug', 'home')
            ->published()
            ->with(['publishedSections.template'])
            ->first();

        if (! $page) {
            throw new ModelNotFoundException('Home page not found or not published.');
        }

        $siteSettings = app(GetSiteSettingsAction::class)->execute();
        $sections = app(ResolvePageSectionsAction::class)->execute($page->publishedSections);

        $avatarCards = AvatarCard::query()
            ->published()
            ->orderBy('sort_order')
            ->get();

        if ($avatarCards->isEmpty()) {
            $avatarCards = collect(array_values(config('heartwell.avatar_cards')));
        }

        $testimonialSettings = $sections->firstWhere('section_type', 'testimonials')?->content ?? [];
        $homeTestimonialSettings = array_merge($siteSettings['home'] ?? [], [
            'testimonials_enabled' => $testimonialSettings['enabled'] ?? ($siteSettings['home']['testimonials_enabled'] ?? true),
            'testimonials_count' => $testimonialSettings['count'] ?? ($siteSettings['home']['testimonials_count'] ?? 6),
            'testimonials_display_mode' => $testimonialSettings['display_mode'] ?? ($siteSettings['home']['testimonials_display_mode'] ?? 'grid'),
            'testimonials_carousel_visible' => $testimonialSettings['carousel_visible'] ?? ($siteSettings['home']['testimonials_carousel_visible'] ?? 1),
            'testimonials_carousel_autoplay' => $testimonialSettings['carousel_autoplay'] ?? ($siteSettings['home']['testimonials_carousel_autoplay'] ?? false),
            'testimonials_carousel_interval' => $testimonialSettings['carousel_interval'] ?? ($siteSettings['home']['testimonials_carousel_interval'] ?? 6),
        ]);

        return [
            'page' => $page,
            'sections' => $sections,
            'pathways' => SupportPathway::query()
                ->published()
                ->orderBy('sort_order')
                ->get(),
            'testimonials' => app(GetPublishedTestimonialsAction::class)->execute(['home' => $homeTestimonialSettings]),
            'testimonialSettings' => $homeTestimonialSettings,
            'avatarCards' => $avatarCards,
            'siteSettings' => $siteSettings,
            'ctas' => $siteSettings['ctas'],
        ];
    }
}
