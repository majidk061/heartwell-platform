@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $features = $sectionContent['features'] ?? [];
    $closingLine = $sectionContent['closing_line'] ?? null;
    $closingEmphasis = $sectionContent['closing_emphasis'] ?? null;
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="default" class="hw-features-section--five-column">
    @if($section->heading)
        <h2 class="hw-features-five-col__heading">{{ $section->heading }}</h2>
    @endif
    @if(! empty($features))
        <div class="hw-features-five-col">
            @foreach($features as $feature)
                <article
                    @class(['hw-features-five-col__item', 'scroll-mt-header' => filled($feature['anchor'] ?? null)])
                    @if(filled($feature['anchor'] ?? null)) id="{{ $feature['anchor'] }}" @endif
                >
                    @if(! empty($feature['title']))
                        <h3 class="hw-features-five-col__title">{{ $feature['title'] }}</h3>
                    @endif
                    @if(! empty($feature['body']))
                        <p class="hw-features-five-col__body">{{ $feature['body'] }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
    @if(filled($closingLine) || filled($closingEmphasis))
        <div class="hw-features-five-col__closing text-center">
            @if(filled($closingLine))
                <p class="hw-features-five-col__closing-line">{{ $closingLine }}</p>
            @endif
            @if(filled($closingEmphasis))
                <p class="hw-features-five-col__closing-emphasis">{{ $closingEmphasis }}</p>
            @endif
        </div>
    @endif
</x-section-shell>
