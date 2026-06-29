@props(['variant' => 'light', 'showTagline' => true, 'href' => null])

@php
    use App\Domains\Content\Support\CmsImage;

    $branding = $siteSettings['branding'] ?? [];
    $mode = $branding['logo_mode'] ?? 'text';
    $isDark = $variant === 'dark';
    $logoText = $branding['logo_text'] ?? ($siteSettings['brand']['name'] ?? config('heartwell.brand.name', 'HeartWell'));
    $tagline = $branding['logo_tagline'] ?? ($siteSettings['brand']['tagline'] ?? config('heartwell.brand.tagline'));
    $imagePath = $isDark
        ? ($branding['logo_white_path'] ?? $branding['logo_image_path'] ?? null)
        : ($branding['logo_image_path'] ?? null);
    $logoUrl = CmsImage::url($imagePath);
    $href = $href ?? route('home');
    $imageOnly = $mode === 'image';
    $showText = in_array($mode, ['text', 'both'], true);
    $showTaglineText = $showTagline && $showText && ! $imageOnly;
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 shrink-0 justify-self-start']) }}>
    @if(in_array($mode, ['image', 'both'], true) && $logoUrl)
        <span @class([
            'inline-flex shrink-0',
            'bg-white/95 rounded-md px-2 py-1' => $isDark,
        ])>
            <img
                src="{{ $logoUrl }}"
                alt="{{ $logoText }}"
                @class([
                    'w-auto object-contain object-left',
                    'h-14 sm:h-16 md:h-[4.5rem]' => $imageOnly,
                    'h-8 sm:h-10' => ! $imageOnly,
                ])
            >
        </span>
    @endif
    @if($showText)
        <div class="flex flex-col min-w-0">
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
