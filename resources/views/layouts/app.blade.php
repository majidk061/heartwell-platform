<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        use App\Domains\Content\Support\CmsImage;
        use App\Domains\Content\Support\SectionLayout;

        $page = $page ?? null;
        $brandName = $siteSettings['brand']['name'] ?? config('heartwell.brand.name');
        $seo = $siteSettings['seo'] ?? [];
        $theme = $siteSettings['theme'] ?? [];
        $themeColors = array_merge(SectionLayout::defaultThemeColors(), $theme['colors'] ?? []);
        $pageTitle = $metaTitle ?? ($page?->meta_title ?? null);
        if (! $pageTitle && ! empty($page?->title)) {
            $pageTitle = ! empty($seo['default_meta_title'])
                ? $page->title.' | '.$seo['default_meta_title']
                : $page->title;
        }
        $documentTitle = $pageTitle ?? ($seo['default_meta_title'] ?? $brandName);
        $metaDescription = $page?->meta_description ?? ($seo['default_meta_description'] ?? null);
        $ogImage = CmsImage::url($page?->og_image ?? ($seo['default_og_image'] ?? null));
        $canonicalUrl = filled($page?->canonical_url ?? null) ? $page->canonical_url : url()->current();
        $favicon = CmsImage::url($siteSettings['branding']['favicon_path'] ?? null);
        $ga4Id = $seo['ga4_measurement_id'] ?? null;
        $robotsIndex = $page && $page->robots_index !== null
            ? (bool) $page->robots_index
            : ($seo['robots_index'] ?? true);
        $ogType = $page?->og_type ?? 'website';
        $twitterCard = $page?->twitter_card ?? ($ogImage ? 'summary_large_image' : 'summary');
        $schemaType = $page?->schema_type ?? 'none';
        $siteWidth = $theme['site_width'] ?? 'standard';
        $headerMode = $theme['header_mode'] ?? 'sticky';
        $headerStyle = $theme['header_style'] ?? 'transparent_blur';
        $headerBorder = $theme['header_show_border'] ?? true;
        $headerClasses = trim(implode(' ', array_filter([
            $headerMode === 'sticky' ? 'hw-header--sticky' : 'hw-header--static',
            $headerStyle === 'transparent_blur' ? 'hw-header--transparent' : 'hw-header--solid',
            ! $headerBorder ? 'hw-header--no-border' : 'border-b border-hw-border',
            'z-40',
        ])));
    @endphp

    <title>{{ $documentTitle }}</title>
    @if($metaDescription)
        <meta name="description" content="{{ $metaDescription }}">
    @endif
    @if($page?->focus_keyword)
        <meta name="keywords" content="{{ $page->focus_keyword }}">
    @endif
    @if(! $robotsIndex)
        <meta name="robots" content="noindex, nofollow">
    @else
        <meta name="robots" content="index, follow">
    @endif

    <link rel="canonical" href="{{ $canonicalUrl }}">

    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:title" content="{{ $documentTitle }}">
    @if($metaDescription)
        <meta property="og:description" content="{{ $metaDescription }}">
    @endif
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    <meta property="og:url" content="{{ $canonicalUrl }}">
    <meta property="og:site_name" content="{{ $brandName }}">

    <meta name="twitter:card" content="{{ $twitterCard }}">
    <meta name="twitter:title" content="{{ $documentTitle }}">
    @if($metaDescription)
        <meta name="twitter:description" content="{{ $metaDescription }}">
    @endif
    @if($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    @if($favicon)
        <link rel="icon" href="{{ $favicon }}" type="image/png">
    @endif

    <style>:root {
        --color-navy: {{ $themeColors['navy'] }};
        --color-heading: {{ $themeColors['heading'] }};
        --color-dusty-blue: {{ $themeColors['dusty_blue'] }};
        --color-blush: {{ $themeColors['blush'] }};
        --color-taupe: {{ $themeColors['taupe'] }};
        --color-text: {{ $themeColors['text'] }};
        --color-muted: {{ $themeColors['muted'] }};
        --color-border: {{ $themeColors['border'] }};
        --color-blush-light: {{ $themeColors['blush_light'] }};
        --color-dusty-blue-light: {{ $themeColors['dusty_blue_light'] }};
        --color-taupe-light: {{ $themeColors['taupe_light'] }};
        --color-white: {{ $themeColors['white'] }};
    }</style>

    @if($schemaType !== 'none')
        <script type="application/ld+json">
            {!! json_encode([
                '@context' => 'https://schema.org',
                '@type' => $schemaType,
                'name' => $documentTitle,
                'description' => $metaDescription,
                'url' => $canonicalUrl,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
        </script>
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if($ga4Id)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $ga4Id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $ga4Id }}');
        </script>
    @endif
</head>
<body class="bg-hw-white font-body text-hw-text antialiased min-h-screen flex flex-col" data-site-width="{{ $siteWidth }}" x-data="{ mobileOpen: false }">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 btn-primary">Skip to content</a>

    <header class="{{ $headerClasses }}">
        <div class="hw-container">
            <div class="grid grid-cols-[1fr_auto] xl:grid-cols-[auto_1fr_auto] items-center gap-4 min-h-[var(--header-height)]">
                <x-site-logo variant="light" context="header" />

                <nav class="hidden xl:flex items-center justify-center gap-x-5" aria-label="Main">
                    @foreach(($siteSettings['navigation'] ?? config('heartwell.navigation')) as $item)
                        <a href="{{ route($item['route']) }}"
                           class="text-sm font-medium whitespace-nowrap transition-colors {{ request()->routeIs($item['route']) ? 'text-hw-heading' : 'text-hw-text hover:text-hw-heading' }}"
                           @click="mobileOpen = false">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                <div class="hidden xl:flex items-center justify-end gap-2 shrink-0">
                    <a href="{{ route('contact') }}#book" class="btn-primary btn-sm hw-header-cta">{{ $siteSettings['ctas']['primary']['label'] ?? config('heartwell.ctas.primary.label') }}</a>
                    <a href="{{ route('contact') }}#waitlist" class="btn-secondary btn-sm hw-header-cta">{{ $siteSettings['ctas']['secondary']['waitlist']['label'] ?? config('heartwell.ctas.secondary.waitlist.label') }}</a>
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
            <nav class="hw-container py-4 flex flex-col gap-1" aria-label="Mobile">
                @foreach(($siteSettings['navigation'] ?? config('heartwell.navigation')) as $item)
                    <a href="{{ route($item['route']) }}"
                       class="py-3 px-2 text-base font-medium text-hw-text hover:text-hw-heading min-h-[44px] flex items-center"
                       @click="mobileOpen = false">
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <div class="pt-4 mt-2 border-t border-hw-border flex flex-col gap-3">
                    <a href="{{ route('contact') }}#book" class="btn-primary w-full text-center" @click="mobileOpen = false">{{ $siteSettings['ctas']['primary']['label'] ?? config('heartwell.ctas.primary.label') }}</a>
                    <a href="{{ route('contact') }}#waitlist" class="btn-secondary w-full text-center" @click="mobileOpen = false">{{ $siteSettings['ctas']['secondary']['waitlist']['label'] ?? config('heartwell.ctas.secondary.waitlist.label') }}</a>
                </div>
            </nav>
        </div>
    </header>

    @if(session('success'))
        <div class="bg-hw-dusty-blue-light text-hw-heading py-3 text-center px-4" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-hw-blush-light text-hw-heading py-3 px-4" role="alert">
            <ul class="hw-container-narrow list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <main id="main" class="flex-1">
        @yield('content')
    </main>

    @php
        $footer = $siteSettings['footer'] ?? [];
        $socialLinks = $siteSettings['social'] ?? [];
    @endphp

    <footer class="bg-hw-navy text-hw-white mt-auto">
        <div class="hw-container py-10 md:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div>
                    <x-site-logo variant="dark" context="footer" :show-tagline="false" class="justify-self-start" />
                    <p class="text-sm text-hw-taupe mt-4">{{ $siteSettings['brand']['promise'] ?? config('heartwell.brand.promise') }}</p>
                    @if(! empty($footer['email']) || ! empty($footer['phone']))
                        <div class="mt-4 space-y-1 text-sm text-hw-taupe-light">
                            @if(! empty($footer['email']))
                                <p>
                                    <a href="mailto:{{ $footer['email'] }}" class="hover:text-hw-white transition-colors">{{ $footer['email'] }}</a>
                                </p>
                            @endif
                            @if(! empty($footer['phone']))
                                <p>
                                    <a href="tel:{{ preg_replace('/[^\d+]/', '', $footer['phone']) }}" class="hover:text-hw-white transition-colors">{{ $footer['phone'] }}</a>
                                </p>
                            @endif
                        </div>
                    @endif
                    @if(! empty($socialLinks))
                        <div class="flex items-center gap-3 mt-4">
                            @foreach($socialLinks as $link)
                                @if(! empty($link['url']))
                                    <a
                                        href="{{ $link['url'] }}"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center justify-center min-h-[44px] min-w-[44px] text-hw-taupe-light hover:text-hw-white transition-colors"
                                        aria-label="{{ ucfirst($link['platform'] ?? 'Social') }}"
                                    >
                                        @switch($link['platform'] ?? '')
                                            @case('instagram')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                                @break
                                            @case('facebook')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                                @break
                                            @case('linkedin')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                                @break
                                            @case('youtube')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                                                @break
                                            @default
                                                <span class="text-xs uppercase">{{ $link['platform'] ?? 'Link' }}</span>
                                        @endswitch
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                <div>
                    <p class="font-semibold mb-3">Explore</p>
                    <ul class="space-y-2 text-sm text-hw-taupe-light">
                        @foreach(($siteSettings['navigation'] ?? config('heartwell.navigation')) as $item)
                            <li><a href="{{ route($item['route']) }}" class="hover:text-hw-white transition-colors inline-block py-1">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="sm:col-span-2 md:col-span-1">
                    <p class="font-semibold mb-3">Get Started</p>
                    <div class="flex flex-col sm:flex-row md:flex-col gap-3">
                        <a href="{{ route('contact') }}#book" class="btn-primary btn-sm text-center">{{ $siteSettings['ctas']['primary']['label'] ?? config('heartwell.ctas.primary.label') }}</a>
                        <a href="{{ route('contact') }}#waitlist" class="btn-secondary border-hw-white text-hw-white hover:bg-hw-white hover:text-hw-navy btn-sm text-center">{{ $siteSettings['ctas']['secondary']['waitlist']['label'] ?? config('heartwell.ctas.secondary.waitlist.label') }}</a>
                        <a href="{{ route('contact') }}#consultation" class="text-sm text-hw-taupe-light hover:text-hw-white transition-colors py-2 text-center md:text-left">{{ $siteSettings['ctas']['secondary']['consultation']['label'] ?? config('heartwell.ctas.secondary.consultation.label') }} →</a>
                    </div>
                </div>
            </div>
            <p class="text-xs text-hw-taupe mt-8 pt-8 border-t border-hw-taupe/30">
                {{ $siteSettings['compliance']['footer_note'] ?? config('heartwell.compliance.footer_note') }}
            </p>
            <p class="text-xs text-hw-taupe mt-4">&copy; {{ date('Y') }} {{ $siteSettings['brand']['name'] ?? config('heartwell.brand.name') }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
