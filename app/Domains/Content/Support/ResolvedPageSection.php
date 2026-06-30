<?php

namespace App\Domains\Content\Support;

use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use Illuminate\Support\Collection;

class ResolvedPageSection
{
    public static function fromModel(PageSection $section): PageSection
    {
        if (! $section->section_template_id || ! $section->template) {
            return $section;
        }

        $template = $section->template;
        $content = self::mergeTemplateContent($template);

        $section->setAttribute('section_type', $template->section_type);
        $section->setAttribute('heading', $template->heading);
        $section->setAttribute('content', $content);

        return $section;
    }

    /**
     * @param  Collection<int, PageSection>  $sections
     * @return Collection<int, PageSection>
     */
    public static function resolveCollection(Collection $sections): Collection
    {
        return $sections->map(fn (PageSection $section) => self::fromModel($section));
    }

    /**
     * @return array<string, mixed>
     */
    public static function mergeTemplateContent(SectionTemplate $template): array
    {
        $content = is_array($template->content) ? $template->content : [];
        $layout = is_array($template->layout) ? $template->layout : ($content['layout'] ?? []);

        if ($layout !== []) {
            $content['layout'] = array_merge($content['layout'] ?? [], $layout);
        }

        return $content;
    }
}
