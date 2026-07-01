@props([
    'heading' => null,
    'body' => null,
    'variant' => 'dual',
    'primaryLabel' => null,
    'primaryUrl' => null,
    'waitlistLabel' => null,
    'waitlistUrl' => null,
    'showConsultationLink' => false,
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
        ['background' => 'white', 'text_align' => 'center'],
    );

    $isCentered = ($layout['text_align'] ?? 'center') === 'center';

    $sectionClass = trim(implode(' ', array_filter([
        'hw-section hw-cta-inline-bar',
        $isCentered ? 'hw-cta-inline-bar--center' : 'hw-cta-inline-bar--left',
        match ($layout['section_padding']) {
            'none' => 'hw-section--padding-none',
            'compact' => 'hw-section--padding-compact',
            'spacious' => 'hw-section--padding-spacious',
            default => '',
        },
    ])));
@endphp

<section class="{{ $sectionClass }}">
    <x-layout.page-container :width="$layout['container_width']">
        <div class="hw-cta-inline-bar__inner">
            @if($heading)
                <h2 class="hw-cta-inline-bar__title">{{ $heading }}</h2>
            @endif
            @if($body)
                <p class="hw-cta-inline-bar__body">{{ $body }}</p>
            @endif
            <div class="hw-cta-inline-bar__actions">
                @if($variant === 'dual' || $variant === 'primary' || $variant === 'band_full_width')
                    <a href="{{ $primaryHref }}" class="btn-primary sm:w-auto min-h-[44px]">{{ $primaryLabel }}</a>
                @endif
                @if($variant === 'dual' || $variant === 'waitlist' || $variant === 'band_full_width')
                    <a href="{{ $waitlistHref }}" class="btn-secondary sm:w-auto min-h-[44px]">{{ $waitlistLabel }}</a>
                @endif
            </div>
            @if($showConsultationLink)
                <p class="hw-cta-inline-bar__consult">
                    {{ $consultationPrefix }}
                    <a href="{{ $consultationHref }}" class="hw-cta-inline-bar__consult-link">{{ $consultationLabel }} →</a>
                </p>
            @endif
        </div>
    </x-layout.page-container>
</section>
