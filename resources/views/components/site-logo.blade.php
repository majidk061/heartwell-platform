@props([
    'variant' => 'light',
    'showTagline' => true,
    'href' => null,
    'context' => 'default', // header | footer | default
])

@php
    use App\Domains\Content\Support\CmsImage;
    use Illuminate\Support\Facades\Storage;

    $branding = $siteSettings['branding'] ?? [];
    $mode = $branding['logo_mode'] ?? 'text';
    $isDark = $variant === 'dark';
    $logoText = $branding['logo_text'] ?? ($siteSettings['brand']['name'] ?? config('heartwell.brand.name', 'HeartWell'));
    $tagline = $branding['logo_tagline'] ?? ($siteSettings['brand']['tagline'] ?? config('heartwell.brand.tagline'));
    $basePath = $isDark
        ? ($branding['logo_white_path'] ?? $branding['logo_image_path'] ?? null)
        : ($branding['logo_image_path'] ?? null);
    $trimmedPath = $branding['logo_trimmed_path'] ?? null;
    $displayPath = ($context !== 'default' && filled($trimmedPath) && Storage::disk('public')->exists($trimmedPath))
        ? $trimmedPath
        : $basePath;
    $logoUrl = CmsImage::url($displayPath);
    $href = $href ?? route('home');
    $imageOnly = $mode === 'image';
    $isHeader = $context === 'header';
    $isFooter = $context === 'footer';
    $hasLogoImage = in_array($mode, ['image', 'both'], true) && filled($logoUrl);
    $showFooterWordmark = $isFooter && ! $hasLogoImage;
    $showText = (! $isFooter && in_array($mode, ['text', 'both'], true)) || $showFooterWordmark;
    $showTaglineText = $showTagline && $showText && ! $imageOnly && ! $isFooter;
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => trim('inline-flex shrink-0 justify-self-start '.($isHeader ? 'items-center py-1' : '').($isFooter ? ' flex-col items-start' : ' items-center'))]) }}
>
    @if($hasLogoImage)
        <img
            src="{{ $logoUrl }}"
            alt="{{ $logoText }}"
            @class([
                'w-auto object-contain object-left',
                'h-11 sm:h-12 max-w-[10rem] sm:max-w-[11.5rem]' => $isHeader,
                'h-[4.5rem] sm:h-20 max-w-[13rem]' => $isFooter,
                'h-14 sm:h-16 md:h-[4.5rem] max-w-[240px]' => ! $isHeader && ! $isFooter && $imageOnly,
                'h-8 sm:h-10' => ! $isHeader && ! $isFooter && ! $imageOnly,
            ])
            @if($isHeader)
                width="184"
                height="48"
            @elseif($isFooter)
                width="208"
                height="80"
            @endif
            decoding="async"
        >
    @endif

    @if($showText)
        <div @class([
            'flex flex-col min-w-0',
            'ml-3' => ! $isFooter && $hasLogoImage,
        ])>
            @if($showFooterWordmark)
                <span class="hw-site-footer__brand-name">HeartWell</span>
                <span class="hw-site-footer__brand-sub">Aesthetics &amp; Wellness</span>
            @else
                <span class="font-heading text-lg sm:text-xl font-semibold truncate {{ $isDark ? 'text-hw-white' : 'text-hw-heading' }}">
                    {{ $logoText }}
                </span>
                @if($showTaglineText && $tagline)
                    <span class="text-xs truncate hidden sm:block {{ $isDark ? 'text-hw-taupe' : 'text-hw-muted' }}">
                        {{ $tagline }}
                    </span>
                @endif
            @endif
        </div>
    @endif
</a>
