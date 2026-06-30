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
    $trimmedPath = $branding['logo_trimmed_path'] ?? 'cms/branding/heartwell-logo-trimmed.png';
    $displayPath = ($context !== 'default' && Storage::disk('public')->exists($trimmedPath))
        ? $trimmedPath
        : $basePath;
    $logoUrl = CmsImage::url($displayPath);
    $href = $href ?? route('home');
    $imageOnly = $mode === 'image';
    $showText = in_array($mode, ['text', 'both'], true);
    $showTaglineText = $showTagline && $showText && ! $imageOnly;
    $isHeader = $context === 'header';
    $isFooter = $context === 'footer';
@endphp

<a
    href="{{ $href }}"
    {{ $attributes->merge(['class' => 'inline-flex items-center shrink-0 justify-self-start '.($isHeader ? 'py-1' : '')]) }}
>
    @if(in_array($mode, ['image', 'both'], true) && $logoUrl)
        @if($isFooter)
            <span class="hw-footer-logo-card inline-flex">
                <img
                    src="{{ $logoUrl }}"
                    alt="{{ $logoText }}"
                    class="h-16 sm:h-[4.5rem] w-auto max-w-[220px] object-contain object-left"
                    width="220"
                    height="72"
                    decoding="async"
                >
            </span>
        @else
            <img
                src="{{ $logoUrl }}"
                alt="{{ $logoText }}"
                @class([
                    'w-auto object-contain object-left',
                    'h-11 sm:h-12 max-w-[10rem] sm:max-w-[11.5rem]' => $isHeader,
                    'h-14 sm:h-16 md:h-[4.5rem] max-w-[240px]' => ! $isHeader && $imageOnly,
                    'h-8 sm:h-10' => ! $isHeader && ! $imageOnly,
                ])
                @if($isHeader)
                    width="184"
                    height="48"
                @endif
                decoding="async"
            >
        @endif
    @endif
    @if($showText)
        <div @class(['flex flex-col min-w-0', 'ml-3' => ! $isFooter && in_array($mode, ['image', 'both'], true) && $logoUrl])>
            <span class="font-heading text-lg sm:text-xl font-semibold truncate {{ $isDark ? 'text-hw-white' : 'text-hw-heading' }}">
                {{ $logoText }}
            </span>
            @if($showTaglineText && $tagline)
                <span class="text-xs truncate hidden sm:block {{ $isDark ? 'text-hw-taupe' : 'text-hw-muted' }}">
                    {{ $tagline }}
                </span>
            @endif
        </div>
    @endif
</a>
