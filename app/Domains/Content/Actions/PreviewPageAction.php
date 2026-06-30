<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\Page;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PreviewPageAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(string $slug): array
    {
        $page = Page::query()
            ->where('slug', $slug)
            ->with(['sections.template'])
            ->first();

        if (! $page) {
            throw new ModelNotFoundException("Page [{$slug}] not found.");
        }

        $sections = app(ResolvePageSectionsAction::class)->execute(
            $page->sections()->where('is_published', true)->get(),
            includeDraftTemplates: true,
        );

        $data = app(ShowPageAction::class)->buildViewData($page, $sections, $slug);
        $data['isPreview'] = true;

        return $data;
    }
}
