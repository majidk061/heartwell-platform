@props(['pathways', 'barHeading' => null])

@include('components.sections.pathway_bar.divided_bar', [
    'pathways' => $pathways,
    'barHeading' => $barHeading,
])
