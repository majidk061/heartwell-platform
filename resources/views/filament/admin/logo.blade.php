@php
    use App\Domains\Content\Support\CmsImage;

    $logoPath = ($siteSettings['branding']['logo_image_path'] ?? null);
    $logoUrl = CmsImage::url($logoPath);
    $logoAlt = $siteSettings['branding']['logo_text'] ?? ($siteSettings['brand']['name'] ?? 'HeartWell');
@endphp

<div class="flex items-center gap-2">
    @if($logoUrl)
        <span class="inline-flex shrink-0 rounded-md bg-white px-2 py-1">
            <img src="{{ $logoUrl }}" alt="{{ $logoAlt }}" class="h-10 w-auto max-w-[140px] object-contain object-left">
        </span>
    @else
        <span class="block truncate text-sm font-semibold tracking-wide text-white">HeartWell</span>
    @endif
    <span class="block truncate text-[0.65rem] font-normal text-white/60">Admin</span>
</div>
