@props(['pathways', 'title' => null, 'section' => null, 'themeDefaults' => null])

<x-pathway-cards
    :pathways="$pathways"
    :title="$title ?? $section?->heading"
    :section="$section"
    :theme-defaults="$themeDefaults"
/>
