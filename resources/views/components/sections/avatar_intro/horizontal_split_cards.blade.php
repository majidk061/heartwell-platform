@props(['section', 'sectionContent', 'avatarCards', 'themeDefaults'])

@php
    $maxCards = max(1, min(6, (int) ($sectionContent['max_cards'] ?? 3)));
    $displayCards = $avatarCards->take($maxCards);
    $cardCount = $displayCards->count();
    $gridClass = match (true) {
        $cardCount === 2 => 'sm:grid-cols-2',
        $cardCount >= 3 => 'sm:grid-cols-2 lg:grid-cols-3',
        default => '',
    };
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults">
    <div class="hw-avatar-intro--horizontal">
        @if($section->heading)
            <h2 class="hw-avatar-intro--horizontal__title">{{ $section->heading }}</h2>
        @endif
        @if($section->subheading ?? ($sectionContent['subheading'] ?? null))
            <p class="hw-avatar-intro--horizontal__subtitle">{{ $section->subheading ?? $sectionContent['subheading'] }}</p>
        @endif
        @if($displayCards->isNotEmpty())
            <div class="hw-avatar-intro--horizontal__grid grid grid-cols-1 {{ $gridClass }}">
                @foreach($displayCards as $card)
                    <x-avatar-card-horizontal :card="$card" />
                @endforeach
            </div>
        @endif
    </div>
</x-section-shell>
