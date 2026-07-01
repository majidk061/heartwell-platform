@php
    $theme = $siteSettings['theme'] ?? [];
    $navStyle = $theme['navigation_style'] ?? [];
    $hoverEffect = $navStyle['hover_effect'] ?? 'color';
    $activeStyle = $navStyle['active_style'] ?? 'underline';
    $headerMode = $theme['header_mode'] ?? 'sticky';
    $headerStyle = $theme['header_style'] ?? 'solid_cream';
    $headerBorder = $theme['header_show_border'] ?? true;
    $primaryCta = $siteSettings['ctas']['primary']['label'] ?? config('heartwell.ctas.primary.label');
    $waitlistCta = $siteSettings['ctas']['secondary']['waitlist']['label'] ?? config('heartwell.ctas.secondary.waitlist.label');

    $headerStyleClass = match ($headerStyle) {
        'transparent_blur' => 'hw-header--transparent',
        'transparent' => 'hw-header--transparent-clear',
        'solid' => 'hw-header--solid',
        default => 'hw-header--solid-cream',
    };

    $headerClasses = trim(implode(' ', array_filter([
        $headerMode === 'sticky' ? 'hw-header--sticky' : 'hw-header--static',
        $headerStyleClass,
        ! $headerBorder ? 'hw-header--no-border' : 'border-b border-hw-border',
        'hw-header w-full',
    ])));

    $navLinkClass = function (string $route) use ($activeStyle, $hoverEffect): string {
        $active = request()->routeIs($route);
        $base = 'hw-header__nav-link font-medium whitespace-nowrap transition-colors min-h-[44px] inline-flex items-center px-0.5';

        if ($active) {
            return trim($base.' hw-nav-link hw-nav-link--active hw-nav-link--active-'.$activeStyle);
        }

        return trim($base.' hw-nav-link text-hw-text hover:text-hw-heading hw-nav-link--hover-'.$hoverEffect);
    };
@endphp

<header class="{{ $headerClasses }}" data-nav-hover="{{ $hoverEffect }}" data-nav-active="{{ $activeStyle }}" data-header-mode="{{ $headerMode }}" data-header-style="{{ $headerStyle }}">
    <div class="hw-container-wide">
        <div class="hw-header__inner">
            <x-site-logo variant="light" context="header" class="hw-header__logo" />

            <nav class="hw-header__nav" aria-label="Main">
                @foreach(($siteSettings['navigation'] ?? config('heartwell.navigation')) as $item)
                    <a href="{{ route($item['route']) }}"
                       class="{{ $navLinkClass($item['route']) }}"
                       @click="mobileOpen = false">
                        {{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <div class="hw-header__actions">
                <a href="{{ route('contact') }}#book" class="btn-primary btn-sm hw-header-cta">{{ $primaryCta }}</a>
                <a href="{{ route('contact') }}#waitlist" class="btn-secondary btn-sm hw-header-cta">{{ $waitlistCta }}</a>
            </div>

            <button type="button"
                    class="xl:hidden min-h-[44px] min-w-[44px] flex items-center justify-center text-hw-heading justify-self-end"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen"
                    aria-controls="mobile-nav">
                <span class="sr-only">Menu</span>
                <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <div id="mobile-nav" x-show="mobileOpen" x-transition x-cloak class="xl:hidden border-t border-hw-border bg-hw-white">
        <nav class="hw-container-wide py-4 flex flex-col gap-1" aria-label="Mobile">
            @foreach(($siteSettings['navigation'] ?? config('heartwell.navigation')) as $item)
                <a href="{{ route($item['route']) }}"
                   class="py-3 px-2 text-base font-medium text-hw-text hover:text-hw-heading min-h-[44px] flex items-center"
                   @click="mobileOpen = false">
                    {{ $item['label'] }}
                </a>
            @endforeach
            <div class="pt-4 mt-2 border-t border-hw-border flex flex-col gap-3">
                <a href="{{ route('contact') }}#book" class="btn-primary w-full text-center" @click="mobileOpen = false">{{ $primaryCta }}</a>
                <a href="{{ route('contact') }}#waitlist" class="btn-secondary w-full text-center" @click="mobileOpen = false">{{ $waitlistCta }}</a>
            </div>
        </nav>
    </div>
</header>
