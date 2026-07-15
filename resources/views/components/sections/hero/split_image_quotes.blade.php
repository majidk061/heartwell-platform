@props([
    'headline' => null,
    'body' => null,
    'imageUrl' => null,
    'section' => null,
    'themeDefaults' => null,
])

@php
    use App\Domains\Content\Support\CmsImage;
    use App\Domains\Content\Support\SectionLayout;

    $sectionContent = $section->content ?? [];
    $layout = SectionLayout::resolve($sectionContent, $themeDefaults, 'hero', [
        'background' => 'white',
        'section_padding' => 'none',
    ]);
    $sectionClass = SectionLayout::sectionClasses($layout).' hw-hero hw-hero--split-quotes hw-hero--why-banner relative overflow-hidden';

    $desktopPath = $imageUrl ?? ($section->image_url ?? ($sectionContent['image_url'] ?? null));
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

    $titleLead = $sectionContent['title_lead'] ?? 'Thoughtful Wellness Care';
    $titleEmphasis = $sectionContent['title_emphasis'] ?? 'for the Moments';
    $titleTail = $sectionContent['title_tail'] ?? 'When Something Just Feels Off';
    $lowerHeading = $sectionContent['lower_heading'] ?? null;
    $lowerBody = $sectionContent['lower_body'] ?? null;

    $primaryLabel = $sectionContent['primary_label'] ?? 'Explore Support Pathways';
    $primaryUrl = $sectionContent['primary_url'] ?? '/support-pathways';
    $secondaryLabel = $sectionContent['secondary_label'] ?? ($sectionContent['waitlist_label'] ?? 'Begin with a Private Wellness Conversation');
    $secondaryUrl = $sectionContent['secondary_url'] ?? ($sectionContent['waitlist_url'] ?? '/contact#consultation');

    if (! str_starts_with($primaryUrl, 'http')) {
        $primaryUrl = url($primaryUrl);
    }
    if (! str_starts_with($secondaryUrl, 'http')) {
        $secondaryUrl = url($secondaryUrl);
    }

    $bodyParagraphs = array_values(array_filter(array_map(
        static fn (string $paragraph): string => trim($paragraph),
        preg_split('/\n\s*\n/', trim((string) ($body ?? ($section->body ?? ($sectionContent['body'] ?? ''))))) ?: []
    )));
@endphp

<section class="{{ $sectionClass }}">
    @if($desktop || $mobile)
        <div class="absolute inset-0">
            <x-cms.responsive-hero-image
                :desktop-url="$desktop"
                :mobile-url="$mobile"
                class="hw-hero--why-banner__photo absolute inset-0 w-full h-full"
            />
        </div>
        <div class="hw-hero--why-banner__scrim absolute inset-0" aria-hidden="true"></div>
    @else
        <div class="absolute inset-0 bg-hw-white" aria-hidden="true"></div>
    @endif

    <x-layout.page-container :width="$layout['container_width']" class="relative z-10 w-full py-10 md:py-12 lg:py-14">
        <div class="hw-hero-split-quotes__copy">
            @if(filled($sectionContent['eyebrow'] ?? null))
                <p class="hw-hero-eyebrow">{{ $sectionContent['eyebrow'] }}</p>
            @endif
            <h1 class="hw-hero-split-quotes__title">
                <span class="hw-hero-split-quotes__title-line">{{ $titleLead }}</span>
                <em class="hw-hero-split-quotes__title-emphasis">{{ $titleEmphasis }}</em>
                <span class="hw-hero-split-quotes__title-line">{{ $titleTail }}</span>
            </h1>
            @if($bodyParagraphs !== [])
                <div class="hw-hero-body space-y-4 mt-6">
                    @foreach($bodyParagraphs as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            @endif
            <div class="hw-hero-split-quotes__actions mt-8 flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                <a href="{{ $primaryUrl }}" class="btn-primary sm:w-auto">{{ $primaryLabel }}</a>
                <a href="{{ $secondaryUrl }}" class="btn-secondary sm:w-auto">{{ $secondaryLabel }}</a>
            </div>
            @if(filled($lowerHeading) || filled($lowerBody))
                <div class="hw-hero-split-quotes__lower">
                    @if(filled($lowerHeading))
                        <h2 class="hw-hero-split-quotes__lower-title">{{ $lowerHeading }}</h2>
                    @endif
                    @if(filled($lowerBody))
                        <div class="prose prose-hw max-w-none text-hw-text hw-prose-narrow mt-4">
                            {!! $lowerBody !!}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </x-layout.page-container>
</section>
