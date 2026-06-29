@props(['heading' => null, 'body' => null, 'variant' => 'dual', 'ctas' => null])

@php
    $ctas = $ctas ?? ($siteSettings['ctas'] ?? config('heartwell.ctas'));
    $heading = $heading ?? ($siteSettings['home']['cta_section_heading'] ?? 'You Deserve to Feel Like Yourself Again');
    $body = $body ?? ($siteSettings['home']['cta_section_body'] ?? "Whether you're feeling depleted, stuck, or simply unlike yourself — support is available.");
    $primaryLabel = $ctas['primary']['label'] ?? config('heartwell.ctas.primary.label');
    $waitlistLabel = $ctas['secondary']['waitlist']['label'] ?? config('heartwell.ctas.secondary.waitlist.label');
    $primaryAnchor = $ctas['primary']['anchor'] ?? '#book';
    $waitlistAnchor = $ctas['secondary']['waitlist']['anchor'] ?? '#waitlist';
@endphp

<section class="bg-hw-dusty-blue-light/40 hw-section">
    <x-layout.page-container narrow class="text-center">
        <h2 class="hw-section-title">{{ $heading }}</h2>
        @if($body)
            <p class="text-base md:text-lg text-hw-text mt-4">{{ $body }}</p>
        @endif
        <div class="mt-6 md:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-stretch sm:items-center">
            @if($variant === 'dual' || $variant === 'primary')
                <a href="{{ route($ctas['primary']['route'] ?? 'contact') }}{{ $primaryAnchor }}" class="btn-primary sm:w-auto">{{ $primaryLabel }}</a>
            @endif
            @if($variant === 'dual' || $variant === 'waitlist')
                <a href="{{ route($ctas['secondary']['waitlist']['route'] ?? 'contact') }}{{ $waitlistAnchor }}" class="btn-secondary sm:w-auto">{{ $waitlistLabel }}</a>
            @endif
        </div>
    </x-layout.page-container>
</section>
