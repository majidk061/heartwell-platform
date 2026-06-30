@props([
    'heading' => null,
    'body' => null,
    'variant' => 'dual',
    'primaryLabel' => null,
    'primaryUrl' => null,
    'waitlistLabel' => null,
    'waitlistUrl' => null,
    'showConsultationLink' => false,
    'ctas' => null,
    'section' => null,
])

@php
    $ctas = $ctas ?? ($siteSettings['ctas'] ?? config('heartwell.ctas'));
    $primaryLabel = $primaryLabel ?? ($ctas['primary']['label'] ?? config('heartwell.ctas.primary.label'));
    $waitlistLabel = $waitlistLabel ?? ($ctas['secondary']['waitlist']['label'] ?? config('heartwell.ctas.secondary.waitlist.label'));

    $resolveUrl = function (?string $url, string $route, string $anchor): string {
        if (filled($url)) {
            return str_starts_with($url, 'http') ? $url : url($url);
        }

        return route($route).$anchor;
    };

    $primaryHref = $resolveUrl($primaryUrl, $ctas['primary']['route'] ?? 'contact', $ctas['primary']['anchor'] ?? '#book');
    $waitlistHref = $resolveUrl($waitlistUrl, $ctas['secondary']['waitlist']['route'] ?? 'contact', $ctas['secondary']['waitlist']['anchor'] ?? '#waitlist');
@endphp

<section class="hw-section bg-[#f9f5f2] hw-cta-band">
    <x-layout.page-container>
        <div class="max-w-3xl mx-auto text-center py-4 md:py-6">
            @if($heading)
                <h2 class="hw-section-title">{{ $heading }}</h2>
            @endif
            @if($body)
                <p class="text-base md:text-lg text-hw-text mt-4 leading-relaxed">{{ $body }}</p>
            @endif
            <div class="mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                <a href="{{ $primaryHref }}" class="btn-primary sm:w-auto">{{ $primaryLabel }}</a>
                <a href="{{ $waitlistHref }}" class="btn-secondary sm:w-auto">{{ $waitlistLabel }}</a>
            </div>
        </div>
    </x-layout.page-container>
</section>
