@props(['section' => null, 'themeDefaults' => null])

@php
    $sectionContent = $section?->content ?? [];
    $panels = $sectionContent['panels'] ?? [];
    $introHtml = $sectionContent['intro_html'] ?? null;
    if (blank($introHtml) && filled($sectionContent['intro'] ?? null)) {
        $introHtml = '<p>'.e((string) $sectionContent['intro']).'</p>';
    }
    $closingHtml = $sectionContent['closing_html'] ?? ($sectionContent['closing'] ?? null);
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-align="center" class="hw-wj-pathways">
    @if($section?->heading)
        <h2 class="hw-wj-pathways__heading">{{ $section->heading }}</h2>
    @endif
    @if(filled($introHtml))
        <div class="hw-wj-pathways__intro prose prose-hw max-w-none">
            {!! $introHtml !!}
        </div>
    @endif
    @if(! empty($panels))
        <div class="hw-wj-pathways__grid">
            @foreach($panels as $panel)
                <article @class(['hw-wj-pathways__card', 'scroll-mt-header' => filled($panel['slug'] ?? null)]) @if(filled($panel['slug'] ?? null)) id="{{ $panel['slug'] }}" @endif>
                    @if(! empty($panel['title']))
                        <h3 class="hw-wj-pathways__card-title">{{ $panel['title'] }}</h3>
                    @endif
                    @if(! empty($panel['body']))
                        <p class="hw-wj-pathways__card-body">{{ $panel['body'] }}</p>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
    @if(filled($closingHtml))
        <div class="hw-wj-pathways__closing prose prose-hw max-w-none">
            {!! $closingHtml !!}
        </div>
    @endif
</x-section-shell>
