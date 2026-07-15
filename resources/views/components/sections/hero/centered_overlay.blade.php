@props(['headline', 'tagline' => null, 'body' => null, 'introQuestion' => null, 'imageUrl' => null, 'section' => null, 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;
    use App\Domains\Content\Support\SectionLayout;

    $sectionContent = $section->content ?? [];
    $desktopPath = $imageUrl ?? ($sectionContent['image_url'] ?? null);
    $mobilePath = $sectionContent['image_url_mobile'] ?? null;
    if (blank($mobilePath)) {
        $mobilePath = $desktopPath;
    }

    $desktop = $desktopPath;
    if ($desktop && ! str_starts_with($desktop, 'http')) {
        $desktop = CmsImage::url($desktop);
    }
    $mobile = $mobilePath;
    if ($mobile && ! str_starts_with($mobile, 'http')) {
        $mobile = CmsImage::url($mobile);
    }

    $layout = $section
        ? SectionLayout::resolve($sectionContent, $themeDefaults, 'hero', ['section_padding' => 'none'])
        : ['container_width' => 'full', 'section_padding' => 'none', 'background' => 'white', 'text_align' => 'center'];
@endphp

<section class="relative overflow-hidden min-h-[32rem] flex items-center justify-center text-center hw-hero hw-hero--centered">
    @if($desktop || $mobile)
        <div class="absolute inset-0">
            <x-cms.responsive-hero-image
                :desktop-url="$desktop"
                :mobile-url="$mobile"
                class="absolute inset-0 w-full h-full object-cover"
            />
        </div>
        <div class="absolute inset-0 bg-hw-navy/55" aria-hidden="true"></div>
    @else
        <div class="absolute inset-0 bg-hw-dusty-blue-light" aria-hidden="true"></div>
    @endif
    <div class="relative z-10 hw-container py-16 md:py-20 max-w-3xl px-4">
        <h1 class="hw-page-title text-hw-white">{{ $headline }}</h1>
        @if($tagline)
            <p class="hw-hero-tagline font-heading text-xl md:text-2xl italic mt-3">{{ $tagline }}</p>
        @endif
        @if($body)
            <p class="text-base md:text-lg mt-6 leading-relaxed text-hw-white/90">{{ $body }}</p>
        @endif
        <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
            <a href="{{ route('contact') }}#book" class="btn-primary sm:w-auto">{{ ($siteSettings['ctas']['primary']['label'] ?? null) ?: config('heartwell.ctas.primary.label') }}</a>
            <a href="{{ route('contact') }}#waitlist" class="btn-secondary sm:w-auto border-hw-white text-hw-white hover:bg-hw-white hover:text-hw-navy">{{ ($siteSettings['ctas']['secondary']['waitlist']['label'] ?? null) ?: config('heartwell.ctas.secondary.waitlist.label') }}</a>
        </div>
    </div>
</section>
