@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $steps = $sectionContent['steps'] ?? [];
    $stepCount = count($steps);
    $sectionAnchor = $sectionContent['section_anchor'] ?? null;
    $gridClass = match (true) {
        $stepCount <= 2 => 'sm:grid-cols-2',
        $stepCount === 3 => 'sm:grid-cols-2 lg:grid-cols-3',
        $stepCount === 4 => 'sm:grid-cols-2 lg:grid-cols-4',
        default => 'sm:grid-cols-2 lg:grid-cols-5',
    };
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults">
    <div @if(filled($sectionAnchor)) id="{{ $sectionAnchor }}" class="scroll-mt-header" @endif>
        @if($section->heading)
            <x-layout.section-heading :title="$section->heading" centered />
        @endif
        @if(! empty($steps))
            <ol class="hw-journey-horizontal grid grid-cols-1 {{ $gridClass }} gap-6 mt-8">
                @foreach($steps as $index => $step)
                    @php
                        $stepAnchor = is_array($step) ? ($step['anchor'] ?? null) : null;
                    @endphp
                    <li @class([
                        'flex flex-col items-center text-center p-6 rounded-xl bg-hw-taupe-light/50 border border-hw-border',
                        'scroll-mt-header' => filled($stepAnchor),
                    ]) @if(filled($stepAnchor)) id="{{ $stepAnchor }}" @endif>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-hw-heading text-hw-white text-sm font-semibold mb-4">{{ $index + 1 }}</span>
                        <span class="font-heading text-lg text-hw-heading">{{ is_array($step) ? ($step['title'] ?? '') : $step }}</span>
                        @if(is_array($step) && ! empty($step['description']))
                            <p class="text-sm text-hw-muted mt-2 leading-relaxed">{{ $step['description'] }}</p>
                        @endif
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</x-section-shell>
