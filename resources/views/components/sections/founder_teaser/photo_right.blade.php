@include('components.sections.founder_teaser._layout', [
    'section' => $section,
    'imageUrl' => $imageUrl ?? null,
    'credentials' => $credentials ?? [],
    'imageFirst' => false,
])
