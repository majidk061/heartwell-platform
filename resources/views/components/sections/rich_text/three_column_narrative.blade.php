@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $columns = $sectionContent['columns'] ?? [];
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="default" default-align="left" class="hw-rich-text-section--three-column">
    @if(! empty($columns))
        <div class="hw-three-column-narrative">
            @foreach($columns as $column)
                <article
                    @class(['hw-three-column-narrative__col', 'scroll-mt-header' => filled($column['anchor'] ?? null)])
                    @if(filled($column['anchor'] ?? null)) id="{{ $column['anchor'] }}" @endif
                >
                    @if(! empty($column['title']))
                        <h2 class="hw-three-column-narrative__title">{{ $column['title'] }}</h2>
                    @endif
                    @if(! empty($column['body']))
                        <div class="hw-three-column-narrative__body prose prose-hw max-w-none text-hw-text">
                            {!! $column['body'] !!}
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</x-section-shell>
