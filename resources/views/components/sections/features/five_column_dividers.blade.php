@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $features = $sectionContent['features'] ?? [];
    $closingLine = $sectionContent['closing_line'] ?? null;
    $closingEmphasis = $sectionContent['closing_emphasis'] ?? null;
@endphp

<x-section-shell
    :section="$section"
    :theme-defaults="$themeDefaults"
    class="hw-features-section--five-column hw-features-expect"
>
    <div class="hw-features-expect__inner">
        @if($section->heading)
            <div class="hw-features-expect__heading-row">
                <span class="hw-features-expect__heading-rule" aria-hidden="true"></span>
                <h2 class="hw-features-five-col__heading hw-features-expect__heading">{{ $section->heading }}</h2>
                <span class="hw-features-expect__heading-rule" aria-hidden="true"></span>
            </div>
        @endif

        @if(! empty($features))
            <div class="hw-features-expect__panel">
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
            </div>
        @endif

        @if(filled($closingLine) || filled($closingEmphasis))
            <div class="hw-features-five-col__closing hw-features-expect__closing">
                @if(filled($closingLine))
                    <p class="hw-features-five-col__closing-line">{{ $closingLine }}</p>
                @endif
                @if(filled($closingEmphasis))
                    <p class="hw-features-five-col__closing-emphasis">{{ $closingEmphasis }}</p>
                @endif
            </div>
        @endif
    </div>
</x-section-shell>
