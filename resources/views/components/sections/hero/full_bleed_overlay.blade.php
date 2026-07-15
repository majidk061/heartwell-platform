@props(['headline', 'tagline' => null, 'body' => null, 'introQuestion' => null, 'imageUrl' => null, 'section' => null, 'themeDefaults' => null, 'showConsultation' => true])

@php
    use App\Domains\Content\Support\CmsImage;
    use App\Domains\Content\Support\SectionLayout;
    use Illuminate\Support\Str;

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
        : ['container_width' => 'full', 'section_padding' => 'none', 'background' => 'white', 'text_align' => 'left'];

    $sectionClass = SectionLayout::sectionClasses($layout);
    $bodyParagraphs = array_values(array_filter(array_map(
        static fn (string $paragraph): string => trim($paragraph),
        preg_split('/\n\s*\n/', trim((string) ($body ?? ''))) ?: []
    )));
@endphp

<section class="{{ $sectionClass }} hw-hero hw-hero--overlay relative overflow-hidden min-h-[32rem] md:min-h-[36rem] lg:min-h-[34rem] flex items-center">
    @if($desktop || $mobile)
        <div class="absolute inset-0">
            <x-cms.responsive-hero-image
                :desktop-url="$desktop"
                :mobile-url="$mobile"
                class="hw-hero--overlay__photo absolute inset-0 w-full h-full object-cover object-[72%_center] md:object-[78%_center]"
            />
        </div>
        <div class="absolute inset-0 hw-hero--overlay__scrim" aria-hidden="true"></div>
    @else
        <div class="absolute inset-0 bg-hw-taupe-light" aria-hidden="true"></div>
    @endif

    <x-layout.page-container :width="'default'" class="relative z-10 w-full py-12 md:py-16 lg:py-20">
        <div class="max-w-xl lg:max-w-[42rem] text-left">
            <h1 class="hw-page-title text-hw-heading">{{ $headline }}</h1>
            @if($tagline)
                <p class="hw-hero-tagline font-heading text-xl md:text-2xl lg:text-[1.65rem] italic mt-3 leading-snug">{{ $tagline }}</p>
            @endif
            @if($introQuestion)
                <p class="hw-hero-intro-question">
                    @if(preg_match('/^(Feeling\b)/i', $introQuestion, $introMatch))
                        <span class="hw-hero-intro-question__lead">{{ $introMatch[1] }}</span>{{ Str::after($introQuestion, $introMatch[1]) }}
                    @else
                        {{ $introQuestion }}
                    @endif
                </p>
            @endif
            @if($bodyParagraphs !== [])
                <div class="hw-hero-body">
                    @foreach($bodyParagraphs as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            @endif
            <div class="mt-7 md:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4">
                <a href="{{ route('contact') }}#book" class="btn-primary sm:w-auto">{{ ($siteSettings['ctas']['primary']['label'] ?? null) ?: config('heartwell.ctas.primary.label') }}</a>
                <a href="{{ route('contact') }}#waitlist" class="btn-secondary sm:w-auto">{{ ($siteSettings['ctas']['secondary']['waitlist']['label'] ?? null) ?: config('heartwell.ctas.secondary.waitlist.label') }}</a>
            </div>
            @if($showConsultation)
                <p class="mt-4 text-sm text-hw-muted">
                    @php
                        $ctas = $siteSettings['ctas'] ?? config('heartwell.ctas');
                        $tertiaryPrefix = $ctas['tertiary_prefix'] ?? 'Prefer to talk first?';
                        $tertiaryLabel = $ctas['tertiary_label'] ?? ($ctas['secondary']['consultation']['label'] ?? 'Begin with a Private Wellness Conversation');
                    @endphp
                    {{ $tertiaryPrefix }}
                    <a href="{{ route('contact') }}#consultation" class="text-hw-dusty-blue font-medium hover:text-hw-heading transition-colors">{{ $tertiaryLabel }} →</a>
                </p>
            @endif
        </div>
    </x-layout.page-container>
</section>
