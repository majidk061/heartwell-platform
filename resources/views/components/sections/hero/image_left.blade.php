@include('components.sections.hero.default', [
    'headline' => $headline,
    'tagline' => $tagline,
    'body' => $body,
    'introQuestion' => $introQuestion ?? null,
    'imageUrl' => $imageUrl,
    'section' => $section,
    'themeDefaults' => $themeDefaults,
    'showConsultation' => $showConsultation ?? true,
    'imageFirst' => true,
])
