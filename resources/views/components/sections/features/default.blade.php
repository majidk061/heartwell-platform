@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $features = $sectionContent['features'] ?? [];
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults">
    @if($section->heading)
        <x-layout.section-heading :title="$section->heading" centered />
    @endif
    @if(! empty($features))
        @php
            $featureCount = count($features);
            $gridClass = $featureCount === 4
                ? 'hw-features-grid hw-features-grid--balanced-four grid grid-cols-1 sm:grid-cols-2 gap-6 md:gap-8 mt-8 md:mt-10'
                : 'hw-features-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8 md:mt-10';
        @endphp
        <div class="{{ $gridClass }}">
            @foreach($features as $feature)
                @php
                    $anchor = $feature['anchor'] ?? null;
                    $title = $feature['title'] ?? '';
                @endphp
                <div @class(['p-6 rounded-xl border border-hw-border bg-hw-taupe-light/30', 'scroll-mt-header' => filled($anchor)]) @if(filled($anchor)) id="{{ $anchor }}" @endif>
                    @if(filled($title))
                        <h3 class="font-heading text-lg text-hw-heading">{{ $title }}</h3>
                    @endif
                    @if(! empty($feature['body']))
                        <p @class(['text-hw-text text-base leading-loose', 'mt-3' => filled($title)])>{{ $feature['body'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-section-shell>
