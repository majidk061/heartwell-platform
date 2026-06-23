<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\Faq;
use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\SupportPathway;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ShowPageAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(string $slug): array
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->where('is_published', true)
            ->with('publishedSections')
            ->first();

        if (! $page) {
            throw new ModelNotFoundException("Page [{$slug}] not found or not published.");
        }

        $siteSettings = app(GetSiteSettingsAction::class)->execute();

        return [
            'page' => $page,
            'sections' => $page->publishedSections,
            'pathways' => $slug === 'support-pathways'
                ? SupportPathway::query()->where('is_published', true)->orderBy('sort_order')->get()
                : collect(),
            'faqs' => Faq::query()
                ->where('is_published', true)
                ->where(function ($query) use ($slug) {
                    $query->where('page_slug', $slug)
                        ->orWhereNull('page_slug');
                })
                ->orderBy('sort_order')
                ->get(),
            'siteSettings' => $siteSettings,
            'ctas' => $siteSettings['ctas'],
            'compliance' => $siteSettings['compliance'],
        ];
    }
}
