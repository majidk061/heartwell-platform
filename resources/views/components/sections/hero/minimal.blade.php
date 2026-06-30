@props(['headline', 'tagline' => null, 'body' => null, 'introQuestion' => null, 'section' => null, 'themeDefaults' => null, 'showConsultation' => true])

@php
    use App\Domains\Content\Support\SectionLayout;

    $layout = $section
        ? SectionLayout::resolve($section->content ?? [], $themeDefaults, 'hero', ['background' => 'blush'])
        : ['container_width' => 'narrow', 'section_padding' => 'normal', 'background' => 'blush', 'text_align' => 'center'];

    $sectionClass = SectionLayout::sectionClasses($layout);
@endphp

<section class="{{ $sectionClass }} hw-hero hw-hero--minimal">
    <x-layout.page-container :width="$layout['container_width']">
        <div class="max-w-2xl mx-auto text-center">
            <h1 class="hw-page-title">{{ $headline }}</h1>
            @if($tagline)
                <p class="hw-hero-tagline font-heading text-xl md:text-2xl text-hw-blush italic mt-3">{{ $tagline }}</p>
            @endif
            @if($body)
                <p class="text-base md:text-lg text-hw-text mt-6 leading-relaxed">{{ $body }}</p>
            @endif
            <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                <a href="{{ route('contact') }}#book" class="btn-primary sm:w-auto">{{ ($siteSettings['ctas']['primary']['label'] ?? null) ?: config('heartwell.ctas.primary.label') }}</a>
                <a href="{{ route('contact') }}#waitlist" class="btn-secondary sm:w-auto">{{ ($siteSettings['ctas']['secondary']['waitlist']['label'] ?? null) ?: config('heartwell.ctas.secondary.waitlist.label') }}</a>
            </div>
        </div>
    </x-layout.page-container>
</section>
