@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $introText = $sectionContent['intro_text'] ?? null;
    $accentLine = $sectionContent['accent_line'] ?? null;
    $headline = $sectionContent['headline'] ?? null;
    $emphasisLine = $sectionContent['emphasis_line'] ?? null;
    $sectionClass = trim(
        ($sectionContent['section_class'] ?? '')
        .' hw-rich-text-section--editorial-bridge hw-bridge-permission'
    );
@endphp

<x-section-shell
    :section="$section"
    :theme-defaults="$themeDefaults"
    :wrap-container="false"
    :class="$sectionClass"
>
    <div class="hw-bridge-permission__wrap hw-editorial-bridge">
        @if(filled($introText))
            <p class="hw-bridge-permission__intro">{{ $introText }}</p>
        @endif
        @if(filled($accentLine))
            <p class="hw-bridge-permission__accent">{{ $accentLine }}</p>
        @endif
        <hr class="hw-bridge-permission__divider">
        @if(filled($headline))
            <p class="hw-bridge-permission__headline">{{ $headline }}</p>
        @endif
        @if(filled($emphasisLine))
            <p class="hw-bridge-permission__emphasis">{{ $emphasisLine }}</p>
        @endif
    </div>
</x-section-shell>
