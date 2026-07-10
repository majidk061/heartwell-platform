@props([
    'section',
    'themeDefaults' => null,
    'defaultWidth' => null,
    'defaultBackground' => null,
    'defaultAlign' => null,
    'wrapContainer' => true,
])

@php
    use App\Domains\Content\Support\SectionLayout;

    $typeDefaults = array_filter([
        'container_width' => $defaultWidth,
        'background' => $defaultBackground,
        'text_align' => $defaultAlign,
    ]);

    $layout = SectionLayout::resolve(
        $section->content ?? [],
        $themeDefaults,
        $section->section_type ?? null,
        $typeDefaults,
    );

    $sectionClass = SectionLayout::sectionClasses($layout);
    $containerWidth = $layout['container_width'];

    if (($section->section_type ?? null) === 'rich_text') {
        $sectionClass .= ' hw-rich-text-section';
    }

    $sectionAnchor = ($section->content ?? [])['section_anchor'] ?? null;
@endphp

<section
    {{ $attributes->merge(['class' => trim($sectionClass)]) }}
    @if(filled($sectionAnchor)) id="{{ $sectionAnchor }}" @endif
>
    @if($wrapContainer)
        <x-layout.page-container :width="$containerWidth">
            {{ $slot }}
        </x-layout.page-container>
    @else
        {{ $slot }}
    @endif
</section>
