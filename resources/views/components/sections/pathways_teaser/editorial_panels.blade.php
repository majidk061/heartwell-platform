@props(['section' => null, 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;

    $sectionContent = $section?->content ?? [];
    $panels = $sectionContent['panels'] ?? [];
    $introHtml = $sectionContent['intro_html'] ?? null;
    if (blank($introHtml) && filled($sectionContent['intro'] ?? null)) {
        $introHtml = '<p>'.e((string) $sectionContent['intro']).'</p>';
    }
    $closingHtml = $sectionContent['closing_html'] ?? ($sectionContent['closing'] ?? null);
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="default" default-align="left">
    @if($section?->heading)
        <x-layout.section-heading :title="$section->heading" />
    @endif
    @if(filled($introHtml))
        <div class="prose prose-hw max-w-none text-hw-text hw-prose-narrow mt-6">
            {!! $introHtml !!}
        </div>
    @endif
    @if(! empty($panels))
        <div class="hw-pathway-editorial mt-10 md:mt-12 space-y-10 md:space-y-12">
            @foreach($panels as $panel)
                @php
                    $imageUrl = CmsImage::url($panel['image_url'] ?? null);
                    $slug = $panel['slug'] ?? null;
                @endphp
                <article @class(['hw-pathway-editorial__panel', 'scroll-mt-header' => filled($slug)]) @if(filled($slug)) id="{{ $slug }}" @endif>
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="" class="hw-pathway-editorial__image hw-pathway-card__image--{{ $slug }}" loading="lazy" decoding="async">
                    @endif
                    <div class="hw-pathway-editorial__body">
                        @if(! empty($panel['title']))
                            <h3 class="hw-pathway-editorial__title">{{ $panel['title'] }}</h3>
                        @endif
                        @if(! empty($panel['body']))
                            <p class="hw-pathway-editorial__text">{{ $panel['body'] }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
    @if(filled($closingHtml))
        <div class="prose prose-hw max-w-none text-hw-text hw-prose-narrow mt-10 md:mt-12">
            {!! $closingHtml !!}
        </div>
    @endif
</x-section-shell>
