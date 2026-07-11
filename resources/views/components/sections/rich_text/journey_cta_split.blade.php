@props(['section', 'themeDefaults' => null])

@php
    $sectionContent = $section->content ?? [];
    $extraClass = trim(($sectionContent['section_class'] ?? '').' hw-wj-cta-split');

    $primaryUrl = $sectionContent['primary_url'] ?? '/contact#book';
    $waitlistUrl = $sectionContent['waitlist_url'] ?? '/contact#waitlist';
    $consultUrl = $sectionContent['consultation_url'] ?? '/contact#consultation';

    foreach (['primaryUrl', 'waitlistUrl', 'consultUrl'] as $urlVar) {
        if (! str_starts_with($$urlVar, 'http')) {
            $$urlVar = url($$urlVar);
        }
    }
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-align="left" :class="$extraClass">
    <div class="hw-wj-cta-split__grid">
        <div class="hw-wj-cta-split__copy">
            @if(! empty($sectionContent['body']))
                <div class="hw-wj-step__prose prose prose-hw max-w-none">
                    {!! $sectionContent['body'] !!}
                </div>
            @endif
        </div>

        <aside class="hw-wj-cta-split__panel">
            @if(filled($sectionContent['cta_heading'] ?? null))
                <h3 class="hw-wj-cta-split__title">{{ $sectionContent['cta_heading'] }}</h3>
            @endif
            @if(filled($sectionContent['cta_body'] ?? null))
                <p class="hw-wj-cta-split__body">{{ $sectionContent['cta_body'] }}</p>
            @endif
            <div class="hw-wj-cta-split__actions">
                @if(filled($sectionContent['primary_label'] ?? null))
                    <a href="{{ $primaryUrl }}" class="btn-primary hw-wj-cta-split__btn">{{ $sectionContent['primary_label'] }}</a>
                @endif
                @if(filled($sectionContent['waitlist_label'] ?? null))
                    <a href="{{ $waitlistUrl }}" class="btn-secondary hw-wj-cta-split__btn">{{ $sectionContent['waitlist_label'] }}</a>
                @endif
            </div>
            @if(filled($sectionContent['consultation_label'] ?? null))
                <p class="hw-wj-cta-split__consult">
                    @if(filled($sectionContent['consultation_prefix'] ?? null))
                        <span>{{ $sectionContent['consultation_prefix'] }}</span>
                    @endif
                    <a href="{{ $consultUrl }}" class="hw-wj-cta-split__consult-link">{{ $sectionContent['consultation_label'] }} &rarr;</a>
                </p>
            @endif
        </aside>
    </div>
</x-section-shell>
