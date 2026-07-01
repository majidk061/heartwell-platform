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

@foreach($sections as $section)
    @php
        $sectionContent = $section->content ?? [];
        $sectionEnabled = $sectionContent['enabled'] ?? true;
    @endphp

    @if(! $sectionEnabled)
        @continue
    @endif

    @switch($section->section_type ?? $section->type)
        @case('hero')
            @php
                $heroView = section_view('hero', $sectionContent);
                $showHeroConsultation = $sectionContent['show_consultation_link'] ?? true;
            @endphp
            @if($heroView)
                @include($heroView, [
                    'headline' => $section->heading,
                    'tagline' => $section->subheading,
                    'body' => $section->body ?? ($sectionContent['body'] ?? null),
                    'introQuestion' => $sectionContent['intro_question'] ?? null,
                    'imageUrl' => CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null)),
                    'section' => $section,
                    'themeDefaults' => $themeDefaults,
                    'showConsultation' => $showHeroConsultation,
                ])
            @endif
            @if($isHome && $pathways->isNotEmpty())
                @php
                    $barContent = [
                        'design_variant' => $sectionContent['pathway_bar_variant'] ?? 'labeled_inline_dividers',
                    ];
                    $barView = section_view('pathway_bar', $barContent);
                @endphp
                @if($barView)
                    @include($barView, [
                        'pathways' => $pathways,
                        'barHeading' => $sectionContent['pathway_bar_heading'] ?? 'Support Options Include:',
                    ])
                @endif
            @endif
            @break

        @case('intro')
            <x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="narrow" default-background="dusty_blue">
                @if($section->heading)
                    <x-layout.section-heading :title="$section->heading" />
                @endif
                @if($section->body ?? ($sectionContent['body'] ?? null))
                    <p class="text-base md:text-lg text-hw-text mt-4 leading-relaxed">{{ $section->body ?? $sectionContent['body'] }}</p>
                @endif
                @php $introImage = CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null)); @endphp
                @if($introImage)
                    <img src="{{ $introImage }}" alt="" class="mt-8 mx-auto rounded-lg max-w-xl w-full aspect-video object-cover">
                @endif
            </x-section-shell>
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
            <x-section-shell :section="$section" :theme-defaults="$themeDefaults">
                @if($section->heading)
                    <x-layout.section-heading :title="$section->heading" centered />
                @endif
                @php $steps = $sectionContent['steps'] ?? []; @endphp
                @if(! empty($steps))
                    <ol class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mt-8">
                        @foreach($steps as $index => $step)
                            <li class="flex flex-col items-center text-center p-6 rounded-xl bg-hw-taupe-light/50 border border-hw-border">
                                <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-hw-heading text-hw-white text-sm font-semibold mb-4">{{ $index + 1 }}</span>
                                <span class="font-heading text-lg text-hw-heading">{{ is_array($step) ? ($step['title'] ?? '') : $step }}</span>
                                @if(is_array($step) && ! empty($step['description']))
                                    <p class="text-sm text-hw-muted mt-2 leading-relaxed">{{ $step['description'] }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
            </x-section-shell>
            @break

        @case('features')
            <x-section-shell :section="$section" :theme-defaults="$themeDefaults">
                @if($section->heading)
                    <x-layout.section-heading :title="$section->heading" centered />
                @endif
                @php $features = $sectionContent['features'] ?? []; @endphp
                @if(! empty($features))
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-8 md:mt-10">
                        @foreach($features as $feature)
                            <div class="p-6 rounded-xl border border-hw-border bg-hw-taupe-light/30">
                                <h3 class="font-heading text-lg text-hw-heading">{{ $feature['title'] ?? '' }}</h3>
                                @if(! empty($feature['body']))
                                    <p class="text-hw-text mt-3 text-sm leading-relaxed">{{ $feature['body'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </x-section-shell>
            @break

        @case('group_individual')
            <x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-background="dusty_blue">
                @if($section->heading)
                    <x-layout.section-heading :title="$section->heading" centered />
                @endif
                @php $columns = $sectionContent['columns'] ?? []; @endphp
                @if(! empty($columns))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                        @foreach($columns as $column)
                            <div class="p-6 md:p-8 rounded-xl border border-hw-border bg-hw-white">
                                <h3 class="font-heading text-xl text-hw-heading">{{ $column['title'] ?? '' }}</h3>
                                @if(! empty($column['body']))
                                    <p class="text-hw-text mt-4 leading-relaxed whitespace-pre-line">{{ $column['body'] }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
                @if(! empty($sectionContent['body']))
                    <div class="prose prose-hw max-w-none mt-8 text-hw-text leading-relaxed text-left">
                        {!! $sectionContent['body'] !!}
                    </div>
                @endif
            </x-section-shell>
            @break

        @case('rich_text')
            <x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="narrow" default-align="left">
                @if($section->heading)
                    <x-layout.section-heading :title="$section->heading" />
                @endif
                @php $richImage = CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null)); @endphp
                @if($richImage)
                    <img src="{{ $richImage }}" alt="" class="mb-8 rounded-lg w-full max-h-96 object-cover">
                @endif
                @if(! empty($sectionContent['body']))
                    <div class="prose prose-hw max-w-none text-hw-text leading-relaxed">
                        {!! $sectionContent['body'] !!}
                    </div>
                @endif
            </x-section-shell>
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
            @endphp
            @if(($tSettings['enabled'] ?? true) && $testimonials->isNotEmpty())
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
            @if($pathways->isNotEmpty())
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

@if($page && $page->slug === 'support-pathways' && $pathways->isNotEmpty() && ! $sections->contains(fn ($s) => ($s->section_type ?? $s->type) === 'pathways_teaser'))
    <x-pathway-accordion :pathways="$pathways" title="Support Pathways" />
@endif
