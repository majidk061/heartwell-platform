@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $columns = $sectionContent['columns'] ?? [];
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-background="white">
    @if($section->heading)
        <x-layout.section-heading :title="$section->heading" centered />
    @endif
    @if(! empty($sectionContent['body']))
        <div class="prose prose-hw max-w-none text-hw-text hw-prose-narrow mt-4 text-center">
            {!! $sectionContent['body'] !!}
        </div>
    @endif
    @if(! empty($columns))
        <div class="hw-dual-start-options grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mt-8 md:mt-10">
            @foreach($columns as $column)
                <div class="hw-dual-start-options__panel p-6 md:p-8 rounded-xl border border-hw-border bg-hw-white">
                    @if(! empty($column['subtitle']))
                        <p class="text-sm font-semibold uppercase tracking-wide text-hw-dusty-blue">{{ $column['subtitle'] }}</p>
                    @endif
                    @if(! empty($column['title']))
                        <h3 class="font-heading text-xl text-hw-heading mt-2">{{ $column['title'] }}</h3>
                    @endif
                    @if(! empty($column['body']))
                        <p class="text-hw-text mt-4 leading-relaxed whitespace-pre-line">{{ $column['body'] }}</p>
                    @endif
                    @if(! empty($column['cta_label']) && ! empty($column['cta_url']))
                        @php
                            $ctaUrl = $column['cta_url'];
                            if (! str_starts_with($ctaUrl, 'http')) {
                                $ctaUrl = url($ctaUrl);
                            }
                        @endphp
                        <a href="{{ $ctaUrl }}" class="btn-primary mt-6 w-full sm:w-auto">{{ $column['cta_label'] }}</a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-section-shell>
