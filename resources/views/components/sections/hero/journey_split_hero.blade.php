@props(['section' => null, 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;
    use App\Domains\Content\Support\SectionLayout;

    $sectionContent = $section->content ?? [];
    $layout = SectionLayout::resolve($sectionContent, $themeDefaults, 'hero', [
        'background' => 'white',
        'section_padding' => 'none',
        'text_align' => 'left',
    ]);
    $sectionClass = SectionLayout::sectionClasses($layout).' hw-hero hw-wj-hero';
    $containerInset = SectionLayout::containerWidthToken($layout['container_width']);

    $eyebrow = $sectionContent['eyebrow'] ?? null;
    $heroTitle = $sectionContent['hero_title'] ?? ($section->heading ?? null);
    $leadQuestion = $sectionContent['lead_question'] ?? null;

    $showFloatingQuotes = (bool) ($sectionContent['show_floating_quotes'] ?? false);
    $photoPath = $showFloatingQuotes
        ? ($sectionContent['image_url_clean'] ?? ($sectionContent['image_url'] ?? null))
        : ($section->image_url ?? ($sectionContent['image_url'] ?? null));

    $desktop = CmsImage::url($photoPath);
    $mobile = CmsImage::url($sectionContent['image_url_mobile'] ?? null);
    $fallback = $desktop ?: $mobile;
    $quotes = is_array($sectionContent['quotes'] ?? null) ? $sectionContent['quotes'] : [];

    $bodyParagraphs = array_values(array_filter(array_map(
        static fn (string $paragraph): string => trim($paragraph),
        preg_split('/\n\s*\n/', trim((string) ($sectionContent['body'] ?? ''))) ?: []
    )));
@endphp

<section class="{{ $sectionClass }}" style="--hw-wj-hero-container: {{ $containerInset }};">
    <div class="hw-wj-hero__shell">
        <div class="hw-wj-hero__copy">
            <div class="hw-wj-hero__copy-inner">
                @if(filled($eyebrow))
                    <p class="hw-wj-hero__eyebrow">{{ $eyebrow }}</p>
                @endif
                @if(filled($heroTitle))
                    <h1 class="hw-wj-hero__title">{{ $heroTitle }}</h1>
                @endif
                @if(filled($leadQuestion))
                    <p class="hw-wj-hero__lead">{{ $leadQuestion }}</p>
                @endif
                @if($bodyParagraphs !== [])
                    <div class="hw-wj-hero__body">
                        @foreach($bodyParagraphs as $paragraph)
                            <p>{{ $paragraph }}</p>
                        @endforeach
                    </div>
                @elseif(filled($sectionContent['body'] ?? null))
                    <div class="hw-wj-hero__body">
                        <p>{{ $sectionContent['body'] }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($fallback)
            <div class="hw-wj-hero__media">
                <picture class="hw-wj-hero__picture">
                    @if($desktop)
                        <source media="(min-width: 1024px)" srcset="{{ $desktop }}">
                    @endif
                    @if($mobile)
                        <source media="(max-width: 1023px)" srcset="{{ $mobile }}">
                    @endif
                    <img
                        src="{{ $fallback }}"
                        alt=""
                        class="hw-wj-hero__photo"
                        loading="eager"
                        decoding="async"
                    >
                </picture>
                @if($showFloatingQuotes)
                    @foreach($quotes as $index => $quote)
                        @if(filled($quote['text'] ?? null))
                            <p @class(['hw-wj-hero__quote', 'hw-wj-hero__quote--'.($index + 1)])>{{ $quote['text'] }}</p>
                        @endif
                    @endforeach
                @endif
            </div>
        @endif
    </div>
</section>
