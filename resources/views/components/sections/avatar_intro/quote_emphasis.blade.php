@props(['section', 'sectionContent', 'avatarCards', 'themeDefaults'])

@php
    $maxCards = max(1, min(6, (int) ($sectionContent['max_cards'] ?? 6)));
    $displayCards = $avatarCards->take($maxCards);
    $avatarColumns = (int) ($sectionContent['card_columns'] ?? 3);
    $gridClass = $avatarColumns === 2 ? 'md:grid-cols-2' : 'md:grid-cols-3';
    $quote = $sectionContent['unifying_message'] ?? null;
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults">
    @if($quote)
        <p class="font-heading text-2xl md:text-3xl text-hw-heading italic text-center max-w-3xl mx-auto">{{ $quote }}</p>
    @endif
    @if($section->heading)
        <h2 class="hw-section-title font-heading text-hw-heading text-center mt-6">{{ $section->heading }}</h2>
    @endif
    @if($section->subheading ?? ($sectionContent['subheading'] ?? null))
        <p class="text-hw-muted mt-3 text-base text-center">{{ $section->subheading ?? $sectionContent['subheading'] }}</p>
    @endif
    @if($displayCards->isNotEmpty())
        <div class="grid grid-cols-1 {{ $gridClass }} gap-6 mt-8 md:mt-10">
            @foreach($displayCards as $card)
                <x-avatar-card :card="$card" />
            @endforeach
        </div>
    @endif
</x-section-shell>
