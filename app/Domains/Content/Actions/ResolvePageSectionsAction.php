<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Support\ResolvedPageSection;
use Illuminate\Support\Collection;

class ResolvePageSectionsAction
{
    /**
     * @param  Collection<int, PageSection>  $sections
     * @return Collection<int, PageSection>
     */
    public function execute(Collection $sections, bool $includeDraftTemplates = false): Collection
    {
        $sections->loadMissing('template');

        $resolved = ResolvedPageSection::resolveCollection($sections);

        if ($includeDraftTemplates) {
            return $resolved;
        }

        return $resolved->filter(function (PageSection $section): bool {
            if (! $section->section_template_id) {
                return true;
            }

            return $section->template?->isPublishedStatus() ?? false;
        })->values();
    }
}
