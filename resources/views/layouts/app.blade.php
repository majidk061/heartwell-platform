<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $metaTitle ?? ($page->meta_title ?? $page->title ?? config('heartwell.brand.name')) }}</title>
    @if(!empty($page?->meta_description))
        <meta name="description" content="{{ $page->meta_description }}">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @if(config('heartwell.ga4_measurement_id'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('heartwell.ga4_measurement_id') }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ config('heartwell.ga4_measurement_id') }}');
        </script>
    @endif
</head>
<body class="bg-hw-white font-body text-hw-text antialiased min-h-screen flex flex-col" x-data="{ mobileOpen: false }">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 btn-primary">Skip to content</a>

    <header class="sticky top-0 z-40 bg-hw-white/95 backdrop-blur border-b border-hw-border">
        <div class="hw-container">
            <div class="grid grid-cols-[1fr_auto] xl:grid-cols-[auto_1fr_auto] items-center gap-4 min-h-[var(--header-height)]">
                {{-- Logo --}}
                <a href="{{ route('home') }}" class="flex flex-col shrink-0 justify-self-start">
                    <span class="font-heading text-lg sm:text-xl text-hw-heading font-semibold">HeartWell</span>
                    <span class="text-xs text-hw-muted hidden sm:block">{{ config('heartwell.brand.tagline') }}</span>
                </a>

                {{-- Desktop nav (center) --}}
                <nav class="hidden xl:flex items-center justify-center gap-x-5" aria-label="Main">
                    @foreach(config('heartwell.navigation') as $item)
                        <a href="{{ route($item['route']) }}"
                           class="text-sm font-medium whitespace-nowrap transition-colors {{ request()->routeIs($item['route']) ? 'text-hw-heading' : 'text-hw-text hover:text-hw-heading' }}"
                           @click="mobileOpen = false">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </nav>

                {{-- Desktop CTAs (right) --}}
                <div class="hidden xl:flex items-center justify-end gap-3 shrink-0">
                    <a href="{{ route('contact') }}#book" class="btn-primary btn-sm">{{ config('heartwell.ctas.primary.label') }}</a>
                    <a href="{{ route('contact') }}#waitlist" class="btn-secondary btn-sm">{{ config('heartwell.ctas.secondary.waitlist.label') }}</a>
                </div>

                {{-- Mobile menu toggle --}}
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

        {{-- Mobile / tablet drawer --}}
        <div id="mobile-nav" x-show="mobileOpen" x-transition x-cloak class="xl:hidden border-t border-hw-border bg-hw-white">
            <nav class="hw-container py-4 flex flex-col gap-1" aria-label="Mobile">
                @foreach(config('heartwell.navigation') as $item)
                    <a href="{{ route($item['route']) }}"
                       class="py-3 px-2 text-base font-medium text-hw-text hover:text-hw-heading min-h-[44px] flex items-center"
                       @click="mobileOpen = false">
                        {{ $item['label'] }}
                    </a>
                @endforeach
                <div class="pt-4 mt-2 border-t border-hw-border flex flex-col gap-3">
                    <a href="{{ route('contact') }}#book" class="btn-primary w-full text-center" @click="mobileOpen = false">{{ config('heartwell.ctas.primary.label') }}</a>
                    <a href="{{ route('contact') }}#waitlist" class="btn-secondary w-full text-center" @click="mobileOpen = false">{{ config('heartwell.ctas.secondary.waitlist.label') }}</a>
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

    <footer class="bg-hw-navy text-hw-white mt-auto">
        <div class="hw-container py-10 md:py-12">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                <div>
                    <p class="font-heading text-xl font-semibold">HeartWell</p>
                    <p class="text-sm text-hw-taupe mt-2">{{ config('heartwell.brand.promise') }}</p>
                    <p class="text-sm text-hw-taupe">{{ config('heartwell.brand.tagline') }}</p>
                </div>
                <div>
                    <p class="font-semibold mb-3">Explore</p>
                    <ul class="space-y-2 text-sm text-hw-taupe-light">
                        @foreach(config('heartwell.navigation') as $item)
                            <li><a href="{{ route($item['route']) }}" class="hover:text-hw-white transition-colors inline-block py-1">{{ $item['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="sm:col-span-2 md:col-span-1">
                    <p class="font-semibold mb-3">Get Started</p>
                    <div class="flex flex-col sm:flex-row md:flex-col gap-3">
                        <a href="{{ route('contact') }}#book" class="btn-primary btn-sm text-center">{{ config('heartwell.ctas.primary.label') }}</a>
                        <a href="{{ route('contact') }}#waitlist" class="btn-secondary border-hw-white text-hw-white hover:bg-hw-white hover:text-hw-navy btn-sm text-center">{{ config('heartwell.ctas.secondary.waitlist.label') }}</a>
                    </div>
                </div>
            </div>
            <p class="text-xs text-hw-taupe mt-8 pt-8 border-t border-hw-taupe/30">
                {{ config('heartwell.compliance.footer_note') }}
            </p>
            <p class="text-xs text-hw-taupe mt-4">&copy; {{ date('Y') }} {{ config('heartwell.brand.name') }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
