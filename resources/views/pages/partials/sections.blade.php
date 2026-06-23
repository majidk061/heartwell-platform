@props(['sections', 'page' => null, 'pathways' => collect(), 'faqs' => collect(), 'ctas' => null, 'compliance' => null])

@php
    use App\Domains\Content\Support\CmsImage;
    $ctas = $ctas ?? config('heartwell.ctas');
    $compliance = $compliance ?? config('heartwell.compliance');
@endphp

@foreach($sections as $section)
    @switch($section->section_type ?? $section->type)
        @case('hero')
            <x-hero
                :headline="$section->heading"
                :tagline="$section->subheading"
                :body="$section->body ?? ($section->content['body'] ?? null)"
                :image-url="CmsImage::url($section->image_url ?? ($section->content['image_url'] ?? null))"
            />
            @break

        @case('intro')
            <section class="hw-section bg-hw-dusty-blue-light/40">
                <x-layout.page-container narrow class="text-center">
                    @if($section->heading)
                        <x-layout.section-heading :title="$section->heading" />
                    @endif
                    @if($section->body ?? ($section->content['body'] ?? null))
                        <p class="text-base md:text-lg text-hw-text mt-4 leading-relaxed">{{ $section->body ?? $section->content['body'] }}</p>
                    @endif
                    @php $introImage = CmsImage::url($section->image_url ?? ($section->content['image_url'] ?? null)); @endphp
                    @if($introImage)
                        <img src="{{ $introImage }}" alt="" class="mt-8 mx-auto rounded-lg max-w-xl w-full aspect-video object-cover">
                    @endif
                </x-layout.page-container>
            </section>
            @break

        @case('journey')
            <section class="hw-section bg-hw-white">
                <x-layout.page-container>
                    @if($section->heading)
                        <x-layout.section-heading :title="$section->heading" centered />
                    @endif
                    @php $steps = $section->content['steps'] ?? []; @endphp
                    @if(! empty($steps))
                        <ol class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6 mt-8">
                            @foreach($steps as $index => $step)
                                <li class="flex flex-col items-center text-center p-6 rounded-xl bg-hw-taupe-light/50 border border-hw-border">
                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-hw-heading text-hw-white text-sm font-semibold mb-4">{{ $index + 1 }}</span>
                                    <span class="font-heading text-lg text-hw-heading">{{ is_array($step) ? ($step['title'] ?? '') : $step }}</span>
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </x-layout.page-container>
            </section>
            @break

        @case('founder_teaser')
            <x-founder-teaser
                :section="$section"
                :image-url="CmsImage::url($section->image_url ?? ($section->content['image_url'] ?? null))"
            />
            @break

        @case('cta')
            <x-cta-section
                :heading="$section->heading"
                :variant="$section->content['variant'] ?? 'dual'"
                :ctas="$ctas"
            />
            @break

        @case('forms')
            @if(($page->slug ?? null) === 'contact')
                @include('pages.partials.contact-forms', compact('compliance', 'ctas'))
            @endif
            @break

        @default
            <section class="hw-section bg-hw-white">
                <x-layout.page-container narrow>
                    @if($section->heading)
                        <x-layout.section-heading :title="$section->heading" />
                    @endif
                    @if($section->body ?? ($section->content['body'] ?? null))
                        <p class="text-hw-text leading-relaxed whitespace-pre-line">{{ $section->body ?? $section->content['body'] }}</p>
                    @endif
                </x-layout.page-container>
            </section>
    @endswitch
@endforeach

@if($page && $page->slug === 'support-pathways' && $pathways->isNotEmpty())
    <x-pathway-accordion :pathways="$pathways" />
@endif

@if(isset($faqs) && $faqs->isNotEmpty())
    <section class="hw-section bg-hw-taupe-light/30">
        <x-layout.page-container narrow>
            <x-layout.section-heading title="Frequently Asked Questions" centered />
            <div class="space-y-3 mt-8" x-data="pathwayAccordion(null)">
                @foreach($faqs as $faq)
                    <div class="rounded-xl border border-hw-border bg-hw-white overflow-hidden">
                        <button type="button" class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left hover:bg-hw-blush-light/30 transition-colors" @click="toggle('faq-{{ $faq->id }}')" :aria-expanded="isOpen('faq-{{ $faq->id }}').toString()">
                            <span class="font-semibold text-hw-heading">{{ $faq->question }}</span>
                            <svg class="w-5 h-5 text-hw-dusty-blue shrink-0 transition-transform duration-200" :class="{ 'rotate-180': isOpen('faq-{{ $faq->id }}') }" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="isOpen('faq-{{ $faq->id }}')" x-cloak class="px-5 pb-5 border-t border-hw-border pt-4">
                            <p class="text-sm text-hw-muted leading-relaxed">{{ $faq->answer }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </x-layout.page-container>
    </section>
@endif

@if($page && ! $sections->contains(fn ($s) => in_array($s->section_type ?? $s->type, ['cta', 'forms'])))
    <x-cta-section :ctas="$ctas" />
@endif
