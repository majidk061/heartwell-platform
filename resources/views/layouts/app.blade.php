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
        $navStyle = $theme['navigation_style'] ?? [];
        $navHoverColor = $navStyle['hover_color'] ?? '#a69488';
        $navActiveColor = $navStyle['active_color'] ?? '#a69488';
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
        --nav-hover-color: {{ $navHoverColor }};
        --nav-active-color: {{ $navActiveColor }};
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

    @include('layouts.partials.site-header')

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

    @include('layouts.partials.site-footer')
</body>
</html>
