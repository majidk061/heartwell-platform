@props(['headline', 'tagline' => null, 'body' => null, 'imageUrl' => null, 'section' => null, 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\SectionLayout;

    $src = $imageUrl;
    if ($src && ! str_starts_with($src, 'http')) {
        $src = \App\Domains\Content\Support\CmsImage::url($src);
    }

    $layout = $section
        ? SectionLayout::resolve($section->content ?? [], $themeDefaults, 'hero')
        : ['container_width' => 'default', 'section_padding' => 'normal', 'background' => 'white', 'text_align' => 'left'];

    $sectionClass = SectionLayout::sectionClasses($layout);
@endphp

<section class="{{ $sectionClass }}">
    <x-layout.page-container :width="$layout['container_width']">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 items-center">
            <div class="order-2 lg:order-1">
                <h1 class="hw-page-title">{{ $headline }}</h1>
                @if($tagline)
                    <p class="font-heading text-xl md:text-2xl lg:text-3xl text-hw-blush italic mt-3">{{ $tagline }}</p>
                @endif
                @if($body)
                    <p class="text-base md:text-lg text-hw-text mt-4 md:mt-6 leading-relaxed">{{ $body }}</p>
                @endif
                <div class="mt-6 md:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="{{ route('contact') }}#book" class="btn-primary sm:w-auto">{{ ($siteSettings['ctas']['primary']['label'] ?? null) ?: config('heartwell.ctas.primary.label') }}</a>
                    <a href="{{ route('contact') }}#waitlist" class="btn-secondary sm:w-auto">{{ ($siteSettings['ctas']['secondary']['waitlist']['label'] ?? null) ?: config('heartwell.ctas.secondary.waitlist.label') }}</a>
                </div>
                <p class="mt-4 text-sm text-hw-muted">
                    Prefer to talk first?
                    <a href="{{ route('contact') }}#consultation" class="text-hw-dusty-blue font-medium hover:text-hw-heading transition-colors">{{ ($siteSettings['ctas']['secondary']['consultation']['label'] ?? null) ?: config('heartwell.ctas.secondary.consultation.label') }} →</a>
                </p>
            </div>
            <div class="order-1 lg:order-2 w-full">
                @if($src)
                    <img src="{{ $src }}" alt="" class="w-full h-auto rounded-lg object-cover aspect-[4/3]" loading="eager">
                @else
                    <div class="w-full aspect-[4/3] rounded-lg bg-hw-dusty-blue-light flex items-center justify-center">
                        <span class="text-hw-muted text-sm px-4 text-center">Hero image placeholder</span>
                    </div>
                @endif
            </div>
        </div>
    </x-layout.page-container>
</section>
