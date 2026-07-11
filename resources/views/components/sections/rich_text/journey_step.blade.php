@props(['section', 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\SectionLayout;

    $sectionContent = $section->content ?? [];
    $layout = SectionLayout::resolve($sectionContent, $themeDefaults, 'rich_text', [
        'background' => 'white',
        'text_align' => 'left',
        'section_padding' => 'normal',
    ]);
    $containerInset = SectionLayout::containerWidthToken($layout['container_width']);
    $extraClass = trim(($sectionContent['section_class'] ?? '').' hw-wj-step');
@endphp

<x-section-shell
    :section="$section"
    :theme-defaults="$themeDefaults"
    default-align="left"
    wrap-container="false"
    :class="$extraClass"
    style="--hw-wj-step-container: {{ $containerInset }};"
>
    <div class="hw-wj-step__inner">
        @if(! empty($sectionContent['body']))
            <div class="hw-wj-step__prose prose prose-hw max-w-none">
                {!! $sectionContent['body'] !!}
            </div>
        @endif
    </div>
</x-section-shell>
