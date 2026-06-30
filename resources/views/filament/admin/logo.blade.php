@php
    use App\Domains\Content\Support\CmsImage;
    use Illuminate\Support\Facades\Storage;

    $trimmed = 'cms/branding/heartwell-logo-trimmed.png';
    $logoPath = ($siteSettings['branding']['logo_image_path'] ?? null);
    if (Storage::disk('public')->exists($trimmed)) {
        $logoPath = $trimmed;
    }
    $logoUrl = CmsImage::url($logoPath);
    $logoAlt = $siteSettings['branding']['logo_text'] ?? ($siteSettings['brand']['name'] ?? 'HeartWell');
@endphp

<div class="flex items-center gap-2 min-w-0">
    @if($logoUrl)
        <span class="inline-flex shrink-0 rounded-lg bg-white px-2.5 py-1.5 shadow-sm">
            <img src="{{ $logoUrl }}" alt="{{ $logoAlt }}" class="h-9 w-auto max-w-[120px] object-contain object-left" decoding="async">
        </span>
    @else
        <span class="block truncate text-sm font-semibold tracking-wide text-white">HeartWell</span>
    @endif
    <span class="hidden sm:block truncate text-[0.65rem] font-normal text-white/60">Admin</span>
</div>
