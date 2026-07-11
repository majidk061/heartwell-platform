@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $extraClass = trim(($sectionContent['section_class'] ?? '').' hw-wj-step');
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-align="center" :class="$extraClass">
    @if(! empty($sectionContent['body']))
        <div class="hw-wj-step__prose prose prose-hw max-w-none">
            {!! $sectionContent['body'] !!}
        </div>
    @endif
</x-section-shell>
