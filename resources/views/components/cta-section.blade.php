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

    $layout = $section
        ? SectionLayout::resolve($section->content ?? [], $themeDefaults, 'cta', ['background' => 'dusty_blue'])
        : ['container_width' => 'default', 'section_padding' => 'normal', 'background' => 'dusty_blue', 'text_align' => 'center'];

    $sectionClass = SectionLayout::sectionClasses($layout);
@endphp

<section class="{{ $sectionClass }}">
    <x-layout.page-container :width="$layout['container_width']">
        <div class="max-w-3xl mx-auto text-center rounded-2xl border border-hw-border/60 bg-hw-white px-8 py-10 md:px-12 md:py-14 shadow-sm">
            @if($heading)
                <h2 class="hw-section-title">{{ $heading }}</h2>
            @endif
            @if($body)
                <p class="text-base md:text-lg text-hw-text mt-4 max-w-xl mx-auto leading-relaxed">{{ $body }}</p>
            @endif
            <div class="mt-6 md:mt-8 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center items-stretch sm:items-center">
                @if($variant === 'dual' || $variant === 'primary')
                    <a href="{{ $primaryHref }}" class="btn-primary sm:w-auto min-h-[44px] inline-flex items-center justify-center">{{ $primaryLabel }}</a>
                @endif
                @if($variant === 'dual' || $variant === 'waitlist')
                    <a href="{{ $waitlistHref }}" class="btn-secondary sm:w-auto min-h-[44px] inline-flex items-center justify-center">{{ $waitlistLabel }}</a>
                @endif
            </div>
            @if($showConsultationLink)
                <p class="mt-6 text-sm text-hw-muted">
                    {{ $consultationPrefix }}
                    <a href="{{ $consultationHref }}" class="text-hw-dusty-blue font-medium hover:text-hw-heading transition-colors">{{ $consultationLabel }} →</a>
                </p>
            @endif
        </div>
    </x-layout.page-container>
</section>
