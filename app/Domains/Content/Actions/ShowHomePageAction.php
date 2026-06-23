<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\Page;
use App\Domains\Content\Models\SupportPathway;
use App\Domains\Content\Models\Testimonial;
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
            ->where('is_published', true)
            ->with('publishedSections')
            ->first();

        if (! $page) {
            throw new ModelNotFoundException('Home page not found or not published.');
        }

        return [
            'page' => $page,
            'sections' => $page->publishedSections,
            'pathways' => SupportPathway::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->get(),
            'testimonials' => Testimonial::query()
                ->where('is_published', true)
                ->orderBy('sort_order')
                ->limit(3)
                ->get(),
            'avatar_cards' => config('heartwell.avatar_cards'),
            'ctas' => config('heartwell.ctas'),
        ];
    }
}
