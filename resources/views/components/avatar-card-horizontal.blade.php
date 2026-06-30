@props(['card'])

@php
    $imageUrl = is_object($card) ? $card->imageUrl() : ($card['image_url'] ?? null);
    if (! $imageUrl && is_array($card) && ! empty($card['image_path'])) {
        $imageUrl = \App\Domains\Content\Support\CmsImage::url($card['image_path']);
    }
    $headline = is_object($card) ? $card->headline : ($card['headline'] ?? '');
    $subtext = is_object($card) ? $card->subtext : ($card['subtext'] ?? '');
    $ctaLabel = is_object($card) ? $card->cta_label : ($card['cta_label'] ?? 'Learn more');
    $pathwaySlug = is_object($card) ? $card->pathway_slug : ($card['pathway_slug'] ?? '');
@endphp

<article class="hw-avatar-card-horizontal h-full bg-[#faf8f5] border border-hw-border/50 rounded-xl overflow-hidden flex flex-row items-stretch min-h-[11.5rem]">
    <div class="w-[42%] max-w-[9.5rem] xl:max-w-none xl:w-[44%] shrink-0 bg-hw-taupe-light overflow-hidden">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="" class="w-full h-full object-cover min-h-[11.5rem]" loading="lazy" decoding="async">
        @else
            <div class="w-full h-full min-h-[11.5rem] flex items-center justify-center p-3">
                <span class="text-hw-muted text-xs text-center leading-snug">{{ $headline }}</span>
            </div>
        @endif
    </div>
    <div class="p-4 lg:p-5 flex flex-col flex-1 justify-center min-w-0">
        <h3 class="font-heading text-base lg:text-lg text-hw-heading leading-snug">{{ $headline }}</h3>
        <span class="block w-10 h-0.5 bg-hw-blush mt-2 mb-2 shrink-0" aria-hidden="true"></span>
        @if($subtext)
            <p class="text-hw-text text-xs lg:text-sm leading-relaxed line-clamp-4">{{ $subtext }}</p>
        @endif
        <a href="{{ route('support-pathways') }}#{{ $pathwaySlug }}"
           class="mt-3 inline-flex items-center text-hw-blush font-semibold hover:text-hw-heading transition-colors text-xs lg:text-sm min-h-[44px]">
            {{ $ctaLabel }} &rarr;
        </a>
    </div>
</article>
