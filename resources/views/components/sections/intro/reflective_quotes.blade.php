@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $quotes = $sectionContent['quotes'] ?? [];
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-background="white">
    @if(! empty($quotes))
        <div class="hw-reflective-quotes" role="list">
            @foreach($quotes as $quote)
                <p class="hw-reflective-quotes__line" role="listitem">{{ $quote }}</p>
            @endforeach
        </div>
    @endif
</x-section-shell>
