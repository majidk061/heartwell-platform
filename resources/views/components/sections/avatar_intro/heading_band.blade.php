@props(['section', 'sectionContent', 'avatarCards', 'themeDefaults'])

@php
    $maxCards = max(1, min(6, (int) ($sectionContent['max_cards'] ?? 6)));
    $displayCards = $avatarCards->take($maxCards);
    $avatarColumns = (int) ($sectionContent['card_columns'] ?? 3);
    $gridClass = $avatarColumns === 2 ? 'md:grid-cols-2' : 'md:grid-cols-3';
@endphp

<section class="hw-section bg-hw-blush-light/40">
    <x-layout.page-container>
        @if($section->heading)
            <h2 class="hw-section-title font-heading text-hw-heading text-center">{{ $section->heading }}</h2>
        @endif
        @if($section->subheading ?? ($sectionContent['subheading'] ?? null))
            <p class="text-hw-muted mt-3 text-base text-center">{{ $section->subheading ?? $sectionContent['subheading'] }}</p>
        @endif
        @if($displayCards->isNotEmpty())
            <div class="grid grid-cols-1 {{ $gridClass }} gap-6 mt-8">
                @foreach($displayCards as $card)
                    <x-avatar-card :card="$card" />
                @endforeach
            </div>
        @endif
    </x-layout.page-container>
</section>
