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

<article class="bg-hw-white border border-hw-border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col">
    <div class="aspect-[4/5] bg-hw-taupe-light overflow-hidden">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="" class="w-full h-full object-cover" loading="lazy" decoding="async">
        @else
            <div class="w-full h-full flex items-center justify-center">
                <span class="text-hw-muted text-sm px-4 text-center">{{ $headline }}</span>
            </div>
        @endif
    </div>
    <div class="p-6 flex flex-col flex-1">
        <h3 class="font-heading text-xl text-hw-heading">{{ $headline }}</h3>
        <p class="text-hw-text mt-3 flex-1">{{ $subtext }}</p>
        <a href="{{ route('support-pathways') }}#{{ $pathwaySlug }}"
           class="mt-4 inline-flex items-center text-hw-dusty-blue font-semibold hover:text-hw-heading transition-colors min-h-[44px]">
            {{ $ctaLabel }} &rarr;
        </a>
    </div>
</article>
