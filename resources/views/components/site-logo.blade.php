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
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => 'flex items-center gap-3 shrink-0 justify-self-start']) }}>
    @if(in_array($mode, ['image', 'both'], true) && $logoUrl)
        <img
            src="{{ $logoUrl }}"
            alt="{{ $logoText }}"
            class="h-8 sm:h-10 w-auto object-contain shrink-0"
        >
    @endif
    @if(in_array($mode, ['text', 'both'], true))
        <div class="flex flex-col min-w-0">
            <span class="font-heading text-lg sm:text-xl font-semibold truncate {{ $isDark ? 'text-hw-white' : 'text-hw-heading' }}">
                {{ $logoText }}
            </span>
            @if($showTagline && $tagline)
                <span class="text-xs truncate hidden sm:block {{ $isDark ? 'text-hw-taupe' : 'text-hw-muted' }}">
                    {{ $tagline }}
                </span>
            @endif
        </div>
    @endif
</a>
