@props([
    'heading' => null,
    'body' => null,
    'primaryLabel' => null,
    'primaryUrl' => null,
    'ctas' => null,
    'section' => null,
    'themeDefaults' => null,
])

@include('components.sections.cta.default', [
    'heading' => $heading,
    'body' => $body,
    'variant' => 'primary',
    'primaryLabel' => $primaryLabel,
    'primaryUrl' => $primaryUrl,
    'showConsultationLink' => false,
    'ctas' => $ctas,
    'section' => $section,
    'themeDefaults' => $themeDefaults,
])
