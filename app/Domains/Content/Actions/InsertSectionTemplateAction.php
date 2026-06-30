<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;

class InsertSectionTemplateAction
{
    public function execute(SectionTemplate $template, int $pageId, ?int $sortOrder = null): PageSection
    {
        if ($sortOrder === null) {
            $sortOrder = (int) PageSection::query()
                ->where('page_id', $pageId)
                ->max('sort_order') + 1;
        }

        return PageSection::query()->create([
            'page_id' => $pageId,
            'section_template_id' => $template->id,
            'section_type' => $template->section_type,
            'heading' => null,
            'content' => null,
            'sort_order' => $sortOrder,
            'is_published' => true,
        ]);
    }
}
