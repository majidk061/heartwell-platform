@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $columns = $sectionContent['columns'] ?? [];
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-align="center" class="hw-wj-dual-start">
    @if($section->heading)
        <h2 class="hw-wj-dual-start__heading">{{ $section->heading }}</h2>
    @endif
    @if(! empty($sectionContent['body']))
        <div class="hw-wj-dual-start__intro prose prose-hw max-w-none">
            {!! $sectionContent['body'] !!}
        </div>
    @endif
    @if(! empty($columns))
        <div class="hw-wj-dual-start__grid">
            @foreach($columns as $index => $column)
                @php
                    $bodyParagraphs = array_values(array_filter(array_map(
                        static fn (string $paragraph): string => trim($paragraph),
                        preg_split('/\n\s*\n/', trim((string) ($column['body'] ?? ''))) ?: []
                    )));
                @endphp
                <div class="hw-wj-dual-start__card">
                    <p class="hw-wj-dual-start__option">Option {{ $index + 1 }}</p>
                    @if(! empty($column['title']))
                        <h3 class="hw-wj-dual-start__card-title">{{ $column['title'] }}</h3>
                    @endif
                    @if($bodyParagraphs !== [])
                        <div class="hw-wj-dual-start__card-body">
                            @foreach($bodyParagraphs as $paragraph)
                                <p>{{ $paragraph }}</p>
                            @endforeach
                        </div>
                    @endif
                    @if(! empty($column['cta_label']) && ! empty($column['cta_url']))
                        @php
                            $ctaUrl = $column['cta_url'];
                            if (! str_starts_with($ctaUrl, 'http')) {
                                $ctaUrl = url($ctaUrl);
                            }
                        @endphp
                        <a href="{{ $ctaUrl }}" class="btn-primary hw-wj-dual-start__cta">{{ $column['cta_label'] }}</a>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</x-section-shell>
