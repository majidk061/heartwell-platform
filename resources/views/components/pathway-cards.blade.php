@props(['pathways', 'title' => null, 'section' => null, 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;
    use App\Domains\Content\Support\SectionLayout;

    $title = $title ?? ($siteSettings['home']['pathways_section_title'] ?? 'Support Pathways');
    $ctas = $siteSettings['ctas'] ?? config('heartwell.ctas');
    $sectionContent = $section?->content ?? [];
    $layout = $section
        ? SectionLayout::resolve($sectionContent, $themeDefaults ?? ($siteSettings['theme'] ?? []), 'pathways_teaser')
        : ['container_width' => 'default', 'section_padding' => 'normal', 'background' => 'white'];
    $sectionClass = SectionLayout::sectionClasses($layout);
@endphp

<section class="{{ $sectionClass }} hw-pathway-cards-section">
    <x-layout.page-container :width="$layout['container_width']">
        @if($title)
            <h2 class="hw-section-title text-center mb-8 md:mb-10">{{ $title }}</h2>
        @endif

        <div class="hw-pathway-cards-grid">
            @foreach($pathways as $pathway)
                @php
                    $options = $pathway->options_may_include ?? [];
                    if (! is_array($options)) {
                        $options = [];
                    }
                    $ctaUrl = $pathway->cta_url ?? route('contact').'#book';
                    if (! str_starts_with($ctaUrl, 'http')) {
                        $ctaUrl = url($ctaUrl);
                    }
                    $ctaLabel = $pathway->cta_label ?? ($ctas['primary']['label'] ?? 'Book a Visit');
                    $imageUrl = CmsImage::url($pathway->image_path);
                @endphp
                <article class="hw-pathway-card" id="{{ $pathway->slug }}">
                    @if($imageUrl)
                        <img
                            src="{{ $imageUrl }}"
                            alt=""
                            class="hw-pathway-card__image"
                            loading="lazy"
                            decoding="async"
                        >
                    @endif
                    <div class="hw-pathway-card__body">
                        <h3 class="hw-pathway-card__title">{{ $pathway->title }}</h3>
                        @if($pathway->tagline)
                            <p class="hw-pathway-card__tagline">{{ $pathway->tagline }}</p>
                        @endif
                        @if($pathway->intro)
                            <p class="hw-pathway-card__intro">{{ $pathway->intro }}</p>
                        @endif

                        @if(count($options) > 0)
                            <div class="hw-pathway-card__block">
                                <h4 class="hw-pathway-card__label">Options may include</h4>
                                <ul class="hw-pathway-card__list">
                                    @foreach($options as $option)
                                        <li>{{ is_array($option) ? ($option['option'] ?? '') : $option }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if($pathway->common_support)
                            <div class="hw-pathway-card__block">
                                <h4 class="hw-pathway-card__label">Common support options</h4>
                                <p class="hw-pathway-card__text">{!! nl2br(e($pathway->common_support)) !!}</p>
                            </div>
                        @endif

                        @if($pathway->selection_note)
                            <p class="hw-pathway-card__note"><strong>Helpful selection note:</strong> {{ $pathway->selection_note }}</p>
                        @endif

                        @if($pathway->portal_cue)
                            <div class="hw-portal-cue">
                                <p class="hw-portal-cue__label">What you may see in the secure medical intake portal</p>
                                <p class="hw-portal-cue__text">{!! nl2br(e($pathway->portal_cue)) !!}</p>
                            </div>
                        @endif

                        @if($pathway->coming_soon)
                            <p class="hw-pathway-card__coming-soon"><strong>Coming soon:</strong> {{ $pathway->coming_soon }}</p>
                        @endif

                        <x-pathway-bridge-modal
                            :pathway-title="$pathway->title"
                            :pathway-intro="$pathway->intro ?? 'Learn how HeartWell supports you on this pathway.'"
                            :cta-url="$ctaUrl"
                            :cta-label="$ctaLabel"
                            class="mt-6"
                        />
                    </div>
                </article>
            @endforeach
        </div>
    </x-layout.page-container>
</section>
