@props(['section', 'sectionContent', 'avatarCards', 'themeDefaults'])

@php
    $maxCards = max(1, min(6, (int) ($sectionContent['max_cards'] ?? 3)));
    $displayCards = $avatarCards->take($maxCards);
    $cardCount = $displayCards->count();
    $gridClass = match (true) {
        $cardCount === 2 => 'md:grid-cols-2',
        $cardCount >= 3 => 'md:grid-cols-2 xl:grid-cols-3',
        default => 'md:grid-cols-1',
    };
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults">
    @if($section->heading)
        <h2 class="hw-section-title font-heading text-hw-heading text-center">{{ $section->heading }}</h2>
    @endif
    @if($section->subheading ?? ($sectionContent['subheading'] ?? null))
        <p class="text-hw-muted mt-3 text-base md:text-lg text-center">{{ $section->subheading ?? $sectionContent['subheading'] }}</p>
    @endif
    @if($displayCards->isNotEmpty())
        <div class="grid grid-cols-1 {{ $gridClass }} gap-4 lg:gap-5 mt-8 md:mt-10 items-stretch">
            @foreach($displayCards as $card)
                <x-avatar-card-horizontal :card="$card" />
            @endforeach
        </div>
    @endif
</x-section-shell>
