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
        <div class="hw-features-two-col grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mt-8 md:mt-10">
            @foreach($features as $feature)
                <div class="p-8 rounded-xl border border-hw-border bg-hw-taupe-light/30">
                    <h3 class="font-heading text-xl text-hw-heading">{{ $feature['title'] ?? '' }}</h3>
                    @if(! empty($feature['body']))
                        <p class="text-hw-text mt-4 text-base leading-loose">{{ $feature['body'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-section-shell>
