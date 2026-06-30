@props(['section', 'sectionContent', 'avatarCards', 'themeDefaults'])

@php
    $avatarColumns = (int) ($sectionContent['card_columns'] ?? $sectionContent['columns'] ?? 3);
    $avatarColumns = in_array($avatarColumns, [2, 3], true) ? $avatarColumns : 3;
    $maxCards = max(1, min(6, (int) ($sectionContent['max_cards'] ?? 6)));
    $displayCards = $avatarCards->take($maxCards);
    $gridClass = $avatarColumns === 2 ? 'md:grid-cols-2' : 'md:grid-cols-3';
    $showUnifying = $sectionContent['show_unifying_message'] ?? true;
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults">
    @if($section->heading)
        <h2 class="hw-section-title font-heading text-hw-heading">{{ $section->heading }}</h2>
    @endif
    @if($section->subheading ?? ($sectionContent['subheading'] ?? null))
        <p class="text-hw-muted mt-3 text-base md:text-lg">{{ $section->subheading ?? $sectionContent['subheading'] }}</p>
    @endif
    @if($showUnifying && ! empty($sectionContent['unifying_message']))
        <p class="font-heading text-lg md:text-xl text-hw-heading italic mt-4 max-w-2xl mx-auto">{{ $sectionContent['unifying_message'] }}</p>
    @endif
    @if($displayCards->isNotEmpty())
        <div class="grid grid-cols-1 {{ $gridClass }} gap-6 mt-8 md:mt-10">
            @foreach($displayCards as $card)
                <x-avatar-card :card="$card" />
            @endforeach
        </div>
    @endif
</x-section-shell>
