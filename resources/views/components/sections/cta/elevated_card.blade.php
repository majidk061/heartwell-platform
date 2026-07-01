@props([
    'heading' => null,
    'body' => null,
    'variant' => 'dual',
    'primaryLabel' => null,
    'primaryUrl' => null,
    'waitlistLabel' => null,
    'waitlistUrl' => null,
    'showConsultationLink' => true,
    'consultationPrefix' => 'Prefer to talk first?',
    'consultationLabel' => null,
    'consultationUrl' => null,
    'ctas' => null,
    'section' => null,
    'themeDefaults' => null,
])

@php
    use App\Domains\Content\Support\SectionLayout;

    $ctas = $ctas ?? ($siteSettings['ctas'] ?? config('heartwell.ctas'));
    $primaryLabel = $primaryLabel ?? ($ctas['primary']['label'] ?? config('heartwell.ctas.primary.label'));
    $waitlistLabel = $waitlistLabel ?? ($ctas['secondary']['waitlist']['label'] ?? config('heartwell.ctas.secondary.waitlist.label'));
    $consultationLabel = $consultationLabel ?? ($ctas['secondary']['consultation']['label'] ?? config('heartwell.ctas.secondary.consultation.label'));

    $resolveUrl = function (?string $url, string $route, string $anchor): string {
        if (filled($url)) {
            return str_starts_with($url, 'http') ? $url : url($url);
        }

        return route($route).$anchor;
    };

    $primaryHref = $resolveUrl($primaryUrl, $ctas['primary']['route'] ?? 'contact', $ctas['primary']['anchor'] ?? '#book');
    $waitlistHref = $resolveUrl($waitlistUrl, $ctas['secondary']['waitlist']['route'] ?? 'contact', $ctas['secondary']['waitlist']['anchor'] ?? '#waitlist');
    $consultationHref = $resolveUrl($consultationUrl, $ctas['secondary']['consultation']['route'] ?? 'contact', $ctas['secondary']['consultation']['anchor'] ?? '#consultation');

    $layout = SectionLayout::resolve(
        is_array($section->content ?? null) ? $section->content : [],
        $themeDefaults,
        'cta',
        ['background' => 'dusty_blue'],
    );

    $sectionClass = trim(implode(' ', array_filter([
        'hw-section hw-cta-section',
        'hw-cta-section--'.$layout['background'],
        match ($layout['section_padding']) {
            'none' => 'hw-section--padding-none',
            'compact' => 'hw-section--padding-compact',
            'spacious' => 'hw-section--padding-spacious',
            default => '',
        },
        $layout['text_align'] === 'center' ? 'text-center' : 'text-left',
    ])));

    $panelClass = match ($layout['container_width']) {
        'narrow', 'form', 'prose' => 'hw-cta-panel--sm',
        'default' => 'hw-cta-panel--md',
        'comfortable' => 'hw-cta-panel--lg',
        'wide', 'expanded' => 'hw-cta-panel--xl',
        'extra_wide', 'near_full', 'full' => 'hw-cta-panel--2xl',
        default => 'hw-cta-panel--md',
    };
@endphp

<section class="{{ $sectionClass }}">
    <x-layout.page-container :width="$layout['container_width']">
        <div @class(['hw-cta-panel', $panelClass])>
            <div class="hw-cta-panel__content">
                @if($heading)
                    <h2 class="hw-cta-panel__title">{{ $heading }}</h2>
                @endif
                @if($body)
                    <p class="hw-cta-panel__body">{{ $body }}</p>
                @endif
                <div class="hw-cta-panel__actions">
                    @if($variant === 'dual' || $variant === 'primary' || $variant === 'band_full_width')
                        <a href="{{ $primaryHref }}" class="btn-primary sm:w-auto min-h-[44px]">{{ $primaryLabel }}</a>
                    @endif
                    @if($variant === 'dual' || $variant === 'waitlist' || $variant === 'band_full_width')
                        <a href="{{ $waitlistHref }}" class="btn-secondary sm:w-auto min-h-[44px]">{{ $waitlistLabel }}</a>
                    @endif
                </div>
                @if($showConsultationLink)
                    <p class="hw-cta-panel__footnote">
                        {{ $consultationPrefix }}
                        <a href="{{ $consultationHref }}" class="hw-cta-panel__link">{{ $consultationLabel }} →</a>
                    </p>
                @endif
            </div>
        </div>
    </x-layout.page-container>
</section>
