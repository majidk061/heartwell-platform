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

<article class="hw-avatar-card-horizontal">
    <div class="hw-avatar-card-horizontal__media">
        @if($imageUrl)
            <img src="{{ $imageUrl }}" alt="" loading="lazy" decoding="async">
        @else
            <div class="hw-avatar-card-horizontal__placeholder">
                <span>{{ $headline }}</span>
            </div>
        @endif
    </div>
    <div class="hw-avatar-card-horizontal__content">
        <h3 class="hw-avatar-card-horizontal__headline">{{ $headline }}</h3>
        <span class="hw-avatar-card-horizontal__rule" aria-hidden="true"></span>
        @if($subtext)
            <p class="hw-avatar-card-horizontal__copy">{{ $subtext }}</p>
        @endif
        <a href="{{ route('support-pathways') }}#{{ $pathwaySlug }}" class="hw-avatar-card-horizontal__link">
            {{ $ctaLabel }} <span aria-hidden="true">&rarr;</span>
        </a>
    </div>
</article>
