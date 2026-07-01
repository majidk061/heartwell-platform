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
        ['background' => 'white'],
    );

    $sectionClass = trim(implode(' ', array_filter([
        'hw-section hw-cta-split',
        match ($layout['section_padding']) {
            'none' => 'hw-section--padding-none',
            'compact' => 'hw-section--padding-compact',
            'spacious' => 'hw-section--padding-spacious',
            default => '',
        },
    ])));
@endphp

<section class="{{ $sectionClass }}" aria-labelledby="{{ $heading ? 'cta-split-heading' : 'cta-split-section' }}">
    <x-layout.page-container :width="$layout['container_width']">
        <div class="hw-cta-split__frame">
            <div class="hw-cta-split__grid">
                <div class="hw-cta-split__copy">
                    <p class="hw-cta-split__eyebrow">Your next step</p>
                    @if($heading)
                        <h2 id="cta-split-heading" class="hw-cta-split__title">{{ $heading }}</h2>
                    @endif
                    @if($body)
                        <p class="hw-cta-split__body">{{ $body }}</p>
                    @endif
                    @if($showConsultationLink)
                        <p class="hw-cta-split__consult">
                            {{ $consultationPrefix }}
                            <a href="{{ $consultationHref }}" class="hw-cta-split__consult-link">{{ $consultationLabel }} →</a>
                        </p>
                    @endif
                </div>

                <aside class="hw-cta-split__aside" aria-label="Get started">
                    <p class="hw-cta-split__aside-label">Ready when you are</p>
                    <div class="hw-cta-split__actions">
                        @if($variant === 'dual' || $variant === 'primary' || $variant === 'band_full_width')
                            <a href="{{ $primaryHref }}" class="hw-cta-split__btn hw-cta-split__btn--primary">{{ $primaryLabel }}</a>
                        @endif
                        @if($variant === 'dual' || $variant === 'waitlist' || $variant === 'band_full_width')
                            <a href="{{ $waitlistHref }}" class="hw-cta-split__btn hw-cta-split__btn--secondary">{{ $waitlistLabel }}</a>
                        @endif
                    </div>
                </aside>
            </div>
        </div>
    </x-layout.page-container>
</section>
