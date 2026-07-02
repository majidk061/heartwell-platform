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

<section class="{{ $sectionClass }}">
    <x-layout.page-container :width="$layout['container_width']">
        @if($title)
            <h2 class="hw-section-title text-center mb-8 md:mb-10">{{ $title }}</h2>
        @endif
        <div class="space-y-4" x-data="pathwayAccordion(null)">
            @foreach($pathways as $pathway)
                @php
                    $panels = $pathway->accordion_content ?? [];
                    $hasAccordionPanels = ! empty($panels);
                    if (empty($panels) && $pathway->intro) {
                        $panels = [['heading' => 'Overview', 'body' => $pathway->intro]];
                    }
                    $imageUrl = CmsImage::url($pathway->image_path);
                    $ctaUrl = $pathway->cta_url ?? route('contact').'#book';
                    if (! str_starts_with($ctaUrl, 'http')) {
                        $ctaUrl = url($ctaUrl);
                    }
                    $ctaLabel = $pathway->cta_label ?? ($ctas['primary']['label'] ?? 'Book a Visit');
                    $pathwayKey = 'pathway-'.$pathway->slug;
                @endphp
                <div class="border border-hw-border rounded-lg overflow-hidden" id="{{ $pathway->slug }}">
                    <button
                        type="button"
                        class="w-full flex items-center justify-between gap-4 p-4 md:p-5 text-left min-h-[44px] bg-hw-white hover:bg-hw-dusty-blue-light/20 transition-colors"
                        @click="toggle('{{ $pathwayKey }}')"
                        :aria-expanded="isOpen('{{ $pathwayKey }}').toString()"
                    >
                        <span class="font-heading text-base md:text-lg text-hw-heading">{{ $pathway->title }}</span>
                        <svg class="w-5 h-5 shrink-0 text-hw-heading transition-transform" :class="isOpen('{{ $pathwayKey }}') && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="isOpen('{{ $pathwayKey }}')" x-cloak class="px-4 md:px-5 pb-4 md:pb-5 border-t border-hw-border">
                        @if($imageUrl)
                            <img
                                src="{{ $imageUrl }}"
                                alt=""
                                class="mt-4 w-full max-h-56 object-cover rounded-lg"
                                loading="lazy"
                                decoding="async"
                            >
                        @endif
                        @if($pathway->intro && $hasAccordionPanels)
                            <p class="text-hw-text text-base mt-4">{{ $pathway->intro }}</p>
                        @endif
                        @if(count($panels) > 0)
                            <div class="mt-4 space-y-2" x-data="pathwayAccordion(null)">
                                @foreach($panels as $panelIndex => $panel)
                                    @php $panelKey = $pathwayKey.'-panel-'.$panelIndex; @endphp
                                    <div class="rounded-lg border border-hw-border overflow-hidden">
                                        <button
                                            type="button"
                                            class="w-full flex items-center justify-between gap-3 px-4 py-3 text-left min-h-[44px] bg-hw-taupe-light/30 hover:bg-hw-dusty-blue-light/20 transition-colors"
                                            @click="toggle('{{ $panelKey }}')"
                                            :aria-expanded="isOpen('{{ $panelKey }}').toString()"
                                        >
                                            <span class="font-semibold text-sm text-hw-heading">{{ $panel['heading'] ?? 'Details' }}</span>
                                            <svg class="w-4 h-4 shrink-0 text-hw-heading transition-transform" :class="isOpen('{{ $panelKey }}') && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <div x-show="isOpen('{{ $panelKey }}')" x-cloak class="px-4 pb-4 pt-2">
                                            <div class="text-hw-text text-sm leading-relaxed">{!! nl2br(e($panel['body'] ?? '')) !!}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        <x-pathway-bridge-modal
                            :pathway-title="$pathway->title"
                            :pathway-intro="$pathway->intro ?? 'Learn how HeartWell supports you on this pathway.'"
                            :cta-url="$ctaUrl"
                            :cta-label="$ctaLabel"
                            class="mt-6 md:mt-8"
                        />
                    </div>
                </div>
            @endforeach
        </div>
    </x-layout.page-container>
</section>
