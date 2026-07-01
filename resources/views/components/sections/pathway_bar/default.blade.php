@props(['pathways', 'barHeading' => null])

@include('components.sections.pathway_bar.labeled_inline_dividers', [
    'pathways' => $pathways,
    'barHeading' => $barHeading,
])
