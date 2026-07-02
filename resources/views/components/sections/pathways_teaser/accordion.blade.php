@props(['pathways', 'title' => null, 'section' => null, 'themeDefaults' => null])

<x-pathway-accordion
    :pathways="$pathways"
    :title="$title ?? $section?->heading"
    :section="$section"
    :theme-defaults="$themeDefaults"
/>
