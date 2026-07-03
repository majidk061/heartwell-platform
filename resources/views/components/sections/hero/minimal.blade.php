@props(['headline', 'tagline' => null, 'body' => null, 'introQuestion' => null, 'section' => null, 'themeDefaults' => null, 'showConsultation' => true])

@php
    use App\Domains\Content\Support\SectionLayout;

    $layout = $section
        ? SectionLayout::resolve($section->content ?? [], $themeDefaults, 'hero', ['background' => 'white', 'text_align' => 'left'])
        : ['container_width' => 'default', 'section_padding' => 'normal', 'background' => 'white', 'text_align' => 'left'];

    $sectionClass = SectionLayout::sectionClasses($layout);
    $isCentered = ($layout['text_align'] ?? 'left') === 'center';
    $contentAlign = $isCentered ? 'text-center' : 'text-left';
    $buttonRow = $isCentered ? 'justify-center' : 'justify-start';
    $bodyParagraphs = array_values(array_filter(array_map(
        static fn (string $paragraph): string => trim($paragraph),
        preg_split('/\n\s*\n/', trim((string) ($body ?? ''))) ?: []
    )));
@endphp

<section class="{{ $sectionClass }} hw-hero hw-hero--minimal">
    <x-layout.page-container :width="$layout['container_width']">
        <div class="w-full {{ $contentAlign }}">
            <h1 class="hw-page-title">{{ $headline }}</h1>
            @if($tagline)
                <p class="hw-hero-tagline font-heading text-xl md:text-2xl lg:text-3xl italic mt-3">{{ $tagline }}</p>
            @endif
            @if($introQuestion)
                <p class="font-heading text-lg md:text-xl text-hw-heading mt-4">{{ $introQuestion }}</p>
            @endif
            @if($bodyParagraphs !== [])
                <div class="hw-hero-body space-y-4">
                    @foreach($bodyParagraphs as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            @endif
            <div class="mt-6 md:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 {{ $buttonRow }}">
                <a href="{{ route('contact') }}#book" class="btn-primary sm:w-auto">{{ ($siteSettings['ctas']['primary']['label'] ?? null) ?: config('heartwell.ctas.primary.label') }}</a>
                <a href="{{ route('contact') }}#waitlist" class="btn-secondary sm:w-auto">{{ ($siteSettings['ctas']['secondary']['waitlist']['label'] ?? null) ?: config('heartwell.ctas.secondary.waitlist.label') }}</a>
            </div>
            @if($showConsultation)
                <p class="mt-4 text-sm text-hw-muted">
                    Prefer to talk first?
                    <a href="{{ route('contact') }}#consultation" class="text-hw-dusty-blue font-medium hover:text-hw-heading transition-colors">{{ ($siteSettings['ctas']['secondary']['consultation']['label'] ?? null) ?: config('heartwell.ctas.secondary.consultation.label') }} →</a>
                </p>
            @endif
        </div>
    </x-layout.page-container>
</section>
