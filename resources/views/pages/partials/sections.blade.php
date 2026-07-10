@props([
    'sections',
    'page' => null,
    'pathways' => null,
    'faqs' => null,
    'testimonials' => null,
    'testimonialSettings' => null,
    'avatarCards' => null,
    'ctas' => null,
    'compliance' => null,
    'isHome' => false,
    'themeDefaults' => null,
])

@php
    use App\Domains\Content\Support\CmsImage;

    $pathways = $pathways ?? collect();
    $faqs = $faqs ?? collect();
    $testimonials = $testimonials ?? collect();
    $avatarCards = $avatarCards ?? collect();
    $ctas = $ctas ?? ($siteSettings['ctas'] ?? config('heartwell.ctas'));
    $compliance = $compliance ?? ($siteSettings['compliance'] ?? config('heartwell.compliance'));
    $testimonialSettings = $testimonialSettings ?? ($siteSettings['home'] ?? []);
    $themeDefaults = $themeDefaults ?? ($siteSettings['theme'] ?? []);
@endphp

<div @class([
    'hw-page-sections',
    'hw-page-sections--contact' => ($page?->slug ?? null) === 'contact',
    'hw-page-sections--home' => $isHome,
    'hw-page-sections--privacy' => ($page?->slug ?? null) === 'privacy',
    'hw-page-sections--why-heartwell' => ($page?->slug ?? null) === 'why-heartwell',
    'hw-page-sections--wellness-journey' => ($page?->slug ?? null) === 'wellness-journey',
    'hw-meet-founder-page' => ($page?->slug ?? null) === 'meet-the-founder',
])>
@foreach($sections as $section)
    @php
        $sectionContent = $section->content ?? [];
        $sectionEnabled = ($sectionContent['enabled'] ?? true)
            || ! empty($sectionContent['trust_features']);
    @endphp

    @if(! $sectionEnabled)
        @continue
    @endif

    @switch($section->section_type ?? $section->type)
        @case('hero')
            @php
                $heroView = section_view('hero', $sectionContent);
                $showHeroConsultation = $sectionContent['show_consultation_link'] ?? true;
                $showHeroCtaButtons = $sectionContent['show_cta_buttons'] ?? true;
            @endphp
            @if($heroView)
                @include($heroView, [
                    'headline' => $section->heading,
                    'tagline' => $section->subheading ?? ($sectionContent['subheading'] ?? null),
                    'body' => $section->body ?? ($sectionContent['body'] ?? null),
                    'introQuestion' => $sectionContent['intro_question'] ?? null,
                    'eyebrow' => $sectionContent['eyebrow'] ?? ($sectionContent['content_eyebrow'] ?? null),
                    'imageUrl' => CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null)),
                    'section' => $section,
                    'themeDefaults' => $themeDefaults,
                    'showConsultation' => $showHeroConsultation,
                    'showCtaButtons' => $showHeroCtaButtons,
                ])
            @endif
            @php
                $showPathwayBar = $isHome
                    && $pathways->isNotEmpty()
                    && ($sectionContent['show_pathway_bar'] ?? true);
            @endphp
            @if($showPathwayBar)
                @php
                    $barContent = [
                        'design_variant' => $sectionContent['pathway_bar_variant'] ?? 'labeled_inline_dividers',
                    ];
                    $barView = section_view('pathway_bar', $barContent);
                @endphp
                @if($barView)
                    @include($barView, [
                        'pathways' => $pathways,
                        'barHeading' => $sectionContent['pathway_bar_heading'] ?? 'Support Pathways Include:',
                    ])
                @endif
            @endif
            @break

        @case('intro')
            @php $introView = section_view('intro', $sectionContent); @endphp
            @if($introView)
                @include($introView, [
                    'section' => $section,
                    'themeDefaults' => $themeDefaults,
                ])
            @endif
            @break

        @case('avatar_intro')
            @php $avatarView = section_view('avatar_intro', $sectionContent); @endphp
            @if($avatarView)
                @include($avatarView, [
                    'section' => $section,
                    'sectionContent' => $sectionContent,
                    'avatarCards' => $avatarCards,
                    'themeDefaults' => $themeDefaults,
                ])
            @endif
            @break

        @case('journey')
            @php $journeyView = section_view('journey', $sectionContent); @endphp
            @if($journeyView)
                @include($journeyView, [
                    'section' => $section,
                    'themeDefaults' => $themeDefaults,
                ])
            @endif
            @break

        @case('features')
            @php $featuresView = section_view('features', $sectionContent); @endphp
            @if($featuresView)
                @include($featuresView, [
                    'section' => $section,
                    'themeDefaults' => $themeDefaults,
                ])
            @endif
            @break

        @case('group_individual')
            @php $groupView = section_view('group_individual', $sectionContent); @endphp
            @if($groupView)
                @include($groupView, [
                    'section' => $section,
                    'themeDefaults' => $themeDefaults,
                ])
            @endif
            @break

        @case('rich_text')
            @php $richTextView = section_view('rich_text', $sectionContent); @endphp
            @if($richTextView)
                @include($richTextView, [
                    'section' => $section,
                    'themeDefaults' => $themeDefaults,
                ])
            @endif
            @break

        @case('founder_teaser')
            @php
                $founderView = section_view('founder_teaser', $sectionContent);
                $founderCreds = $sectionContent['credentials'] ?? [];
            @endphp
            @if($founderView)
                @include($founderView, [
                    'section' => $section,
                    'imageUrl' => CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null)),
                    'credentials' => $founderCreds,
                ])
            @endif
            @break

        @case('testimonials')
            @php
                $tSettings = array_merge($testimonialSettings, $sectionContent);
                $trustFeatures = $sectionContent['trust_features'] ?? [];
            @endphp
            @if(! empty($trustFeatures))
                @php
                    $trustSection = (object) [
                        'heading' => $section->heading ?: 'What You Can Expect',
                        'content' => array_merge($sectionContent, [
                            'features' => $trustFeatures,
                            'layout' => $sectionContent['layout'] ?? ['container_width' => 'default', 'background' => 'white'],
                        ]),
                        'section_type' => 'features',
                    ];
                @endphp
                @include('components.sections.features.default', [
                    'section' => $trustSection,
                    'themeDefaults' => $themeDefaults,
                ])
            @elseif(($tSettings['enabled'] ?? true) && $testimonials->isNotEmpty())
                <x-testimonials
                    :testimonials="$testimonials"
                    :heading="$section->heading"
                    :subtitle="$sectionContent['subtitle'] ?? null"
                    :display-mode="$tSettings['display_mode'] ?? 'grid'"
                    :carousel-visible="$tSettings['carousel_visible'] ?? 1"
                    :carousel-autoplay="$tSettings['carousel_autoplay'] ?? false"
                    :carousel-interval="$tSettings['carousel_interval'] ?? 6"
                    :section="$section"
                    :theme-defaults="$themeDefaults"
                />
            @endif
            @break

        @case('pathways_teaser')
            @php
                $pathwaysView = section_view('pathways_teaser', $sectionContent);
                $editorialPanels = ($sectionContent['design_variant'] ?? null) === 'editorial_panels'
                    || ! empty($sectionContent['panels']);
            @endphp
            @if($pathwaysView && ($editorialPanels || $pathways->isNotEmpty()))
                @include($pathwaysView, [
                    'pathways' => $pathways,
                    'title' => $section->heading,
                    'section' => $section,
                    'themeDefaults' => $themeDefaults,
                ])
            @elseif($pathways->isNotEmpty())
                <x-pathway-accordion :pathways="$pathways" :title="$section->heading" :section="$section" :theme-defaults="$themeDefaults" />
            @endif
            @break

        @case('faq')
            @if($faqs->isNotEmpty())
                <x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="narrow" default-background="taupe">
                    <x-layout.section-heading :title="$section->heading ?? 'Frequently Asked Questions'" centered />
                    @if(! empty($sectionContent['faq_subtitle']))
                        <p class="text-hw-muted mt-3 text-base">{{ $sectionContent['faq_subtitle'] }}</p>
                    @endif
                    <div class="space-y-3 mt-8" x-data="pathwayAccordion(null)">
                        @foreach($faqs as $faq)
                            <div class="rounded-xl border border-hw-border bg-hw-white overflow-hidden">
                                <button type="button" class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left hover:bg-hw-blush-light/30 transition-colors min-h-[44px]" @click="toggle('faq-{{ $faq->id }}')" :aria-expanded="isOpen('faq-{{ $faq->id }}').toString()">
                                    <span class="font-semibold text-hw-heading">{{ $faq->question }}</span>
                                    <svg class="w-5 h-5 text-hw-dusty-blue shrink-0 transition-transform duration-200" :class="{ 'rotate-180': isOpen('faq-{{ $faq->id }}') }" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="isOpen('faq-{{ $faq->id }}')" x-cloak class="px-5 pb-5 border-t border-hw-border pt-4">
                                    <p class="text-sm text-hw-muted leading-relaxed">{{ $faq->answer }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-section-shell>
            @endif
            @break

        @case('cta')
            @php
                $c = $sectionContent;
                $ctaView = section_view('cta', $sectionContent);
            @endphp
            @if($ctaView)
                @include($ctaView, [
                    'heading' => $section->heading,
                    'body' => $section->body ?? ($c['body'] ?? null),
                    'variant' => $c['variant'] ?? 'dual',
                    'primaryLabel' => $c['primary_label'] ?? null,
                    'primaryUrl' => $c['primary_url'] ?? null,
                    'waitlistLabel' => $c['waitlist_label'] ?? null,
                    'waitlistUrl' => $c['waitlist_url'] ?? null,
                    'showConsultationLink' => $c['show_consultation_link'] ?? true,
                    'consultationPrefix' => $c['consultation_prefix'] ?? 'Prefer to talk first?',
                    'consultationLabel' => $c['consultation_label'] ?? null,
                    'consultationUrl' => $c['consultation_url'] ?? null,
                    'ctas' => $ctas,
                    'section' => $section,
                    'themeDefaults' => $themeDefaults,
                ])
            @endif
            @break

        @case('forms')
            @include('pages.partials.contact-forms', [
                'compliance' => $compliance,
                'ctas' => $ctas,
                'formsSection' => $section,
                'themeDefaults' => $themeDefaults,
            ])
            @break

        @default
            <x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="narrow">
                @if($section->heading)
                    <x-layout.section-heading :title="$section->heading" />
                @endif
                @if($section->body ?? ($sectionContent['body'] ?? null))
                    <p class="text-hw-text leading-relaxed whitespace-pre-line">{{ $section->body ?? $sectionContent['body'] }}</p>
                @endif
            </x-section-shell>
    @endswitch
@endforeach
</div>
