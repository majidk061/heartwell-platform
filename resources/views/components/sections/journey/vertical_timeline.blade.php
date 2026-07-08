@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $steps = $sectionContent['steps'] ?? [];
    $sectionAnchor = $sectionContent['section_anchor'] ?? null;
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults">
    <div @if(filled($sectionAnchor)) id="{{ $sectionAnchor }}" class="scroll-mt-header" @endif>
        @if($section->heading)
            <x-layout.section-heading :title="$section->heading" centered />
        @endif
        @if(! empty($steps))
            <ol class="hw-journey-vertical relative mt-8 max-w-2xl mx-auto space-y-0">
                @foreach($steps as $index => $step)
                    @php
                        $stepAnchor = is_array($step) ? ($step['anchor'] ?? null) : null;
                    @endphp
                    <li @class(['relative flex gap-6 pb-10 last:pb-0', 'scroll-mt-header' => filled($stepAnchor)]) @if(filled($stepAnchor)) id="{{ $stepAnchor }}" @endif>
                        @if(! $loop->last)
                            <span class="absolute left-5 top-10 bottom-0 w-px bg-hw-border" aria-hidden="true"></span>
                        @endif
                        <span class="relative z-10 inline-flex shrink-0 items-center justify-center w-10 h-10 rounded-full bg-hw-heading text-hw-white text-sm font-semibold">{{ $index + 1 }}</span>
                        <div class="pt-1 text-left">
                            <span class="font-heading text-lg text-hw-heading">{{ is_array($step) ? ($step['title'] ?? '') : $step }}</span>
                            @if(is_array($step) && ! empty($step['description']))
                                <p class="text-sm text-hw-muted mt-2 leading-relaxed">{{ $step['description'] }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ol>
        @endif
    </div>
</x-section-shell>
