<?php

namespace App\Domains\Content\Actions;

use App\Domains\Content\Models\AvatarCard;
use App\Domains\Content\Models\PageSection;
use App\Domains\Content\Models\SectionTemplate;
use App\Domains\Content\Models\SupportPathway;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PreviewSectionAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(int $templateId): array
    {
        $template = SectionTemplate::query()->find($templateId);

        if (! $template) {
            throw new ModelNotFoundException("Section template [{$templateId}] not found.");
        }

        $section = new PageSection([
            'section_template_id' => $template->id,
            'section_type' => $template->section_type,
            'heading' => $template->heading,
            'content' => $template->content,
            'is_published' => true,
            'sort_order' => 1,
        ]);
        $section->setRelation('template', $template);

        $resolved = app(ResolvePageSectionsAction::class)->execute(
            new EloquentCollection([$section]),
            includeDraftTemplates: true,
        );

        $siteSettings = app(GetSiteSettingsAction::class)->execute();

        $pathways = SupportPathway::query()->published()->orderBy('sort_order')->get();
        $avatarCards = AvatarCard::query()->published()->orderBy('sort_order')->get();
        if ($avatarCards->isEmpty()) {
            $avatarCards = collect(array_values(config('heartwell.avatar_cards')));
        }

        return [
            'template' => $template,
            'section' => $resolved->first(),
            'sections' => $resolved,
            'siteSettings' => $siteSettings,
            'pathways' => $pathways,
            'avatarCards' => $avatarCards,
            'testimonials' => collect(),
            'testimonialSettings' => [],
            'faqs' => collect(),
            'ctas' => $siteSettings['ctas'],
            'compliance' => $siteSettings['compliance'],
            'themeDefaults' => $siteSettings['theme'] ?? [],
            'isPreview' => true,
            'isHome' => in_array($template->section_type, ['hero', 'avatar_intro', 'pathway_bar'], true),
        ];
    }
}
