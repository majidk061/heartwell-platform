@props([
    'testimonials',
    'heading' => null,
    'subtitle' => null,
    'displayMode' => 'grid',
    'carouselVisible' => 1,
    'carouselAutoplay' => false,
    'carouselInterval' => 6,
    'section' => null,
    'themeDefaults' => null,
])

@php
    use App\Domains\Content\Support\CmsImage;
    use App\Domains\Content\Support\SectionLayout;

    $carouselVisible = max(1, min(3, (int) $carouselVisible));

    $layout = $section
        ? SectionLayout::resolve($section->content ?? [], $themeDefaults, 'testimonials', ['background' => 'taupe'])
        : ['container_width' => 'default', 'section_padding' => 'normal', 'background' => 'taupe', 'text_align' => 'center'];

    $sectionClass = SectionLayout::sectionClasses($layout);
@endphp

@if($testimonials->isNotEmpty())
    <section class="{{ $sectionClass }}">
        <x-layout.page-container :width="$layout['container_width']">
            @if($heading)
                <x-layout.section-heading :title="$heading" :subtitle="$subtitle" centered />
            @endif

            @if($displayMode === 'carousel')
                <div
                    class="{{ $heading ? 'mt-8 md:mt-10' : '' }}"
                    x-data="testimonialCarousel({ total: {{ $testimonials->count() }}, visible: {{ $carouselVisible }}, autoplay: @js($carouselAutoplay), interval: {{ max(3, (int) $carouselInterval) }} })"
                >
                    <div class="overflow-hidden">
                        <div
                            class="flex transition-transform duration-300 ease-out"
                            :style="`transform: translateX(-${index * (100 / visible)}%)`"
                        >
                            @foreach($testimonials as $testimonial)
                                <div class="shrink-0 px-3" :style="`width: ${100 / visible}%`">
                                    <x-testimonial-card :testimonial="$testimonial" />
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @if($testimonials->count() > $carouselVisible)
                        <div class="flex items-center justify-center gap-4 mt-6">
                            <button type="button" @click="prev()" :disabled="index === 0" class="min-h-[44px] min-w-[44px] rounded-full border border-hw-border bg-hw-white text-hw-heading hover:border-hw-dusty-blue disabled:opacity-40" aria-label="Previous testimonial">‹</button>
                            <button type="button" @click="next()" :disabled="index >= maxIndex" class="min-h-[44px] min-w-[44px] rounded-full border border-hw-border bg-hw-white text-hw-heading hover:border-hw-dusty-blue disabled:opacity-40" aria-label="Next testimonial">›</button>
                        </div>
                    @endif
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 {{ $heading ? 'mt-8 md:mt-10' : '' }}">
                    @foreach($testimonials as $testimonial)
                        <x-testimonial-card :testimonial="$testimonial" />
                    @endforeach
                </div>
            @endif
        </x-layout.page-container>
    </section>
@endif
