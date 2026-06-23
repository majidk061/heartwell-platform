@extends('layouts.app')

@section('content')
    @foreach($sections as $section)
        @switch($section->section_type)
            @case('hero')
                <x-hero
                    :heading="$section->heading"
                    :body="$section->content['body'] ?? null"
                    :variant="$section->content['variant'] ?? 'centered'"
                    :eyebrow="$section->content['eyebrow'] ?? null"
                    :image="$section->content['image'] ?? null"
                />
                @break

            @case('intro')
                <section class="hw-section bg-hw-dusty-blue-light/40">
                    <div class="hw-container max-w-3xl mx-auto text-center">
                        <h2 class="font-heading text-3xl md:text-4xl text-hw-heading mb-6">{{ $section->heading }}</h2>
                        @if(! empty($section->content['body']))
                            <div class="prose prose-hw text-hw-muted leading-relaxed">
                                <p>{{ $section->content['body'] }}</p>
                            </div>
                        @endif
                    </div>
                </section>
                @break

            @case('journey')
                <section class="hw-section bg-hw-white">
                    <div class="hw-container">
                        <h2 class="font-heading text-3xl md:text-4xl text-hw-heading mb-10 text-center">{{ $section->heading }}</h2>
                        @if(! empty($section->content['steps']))
                            <ol class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                                @foreach($section->content['steps'] as $index => $step)
                                    <li class="relative flex flex-col items-center text-center p-6 rounded-xl bg-hw-taupe-light/50 border border-hw-border">
                                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-hw-heading text-hw-white text-sm font-semibold mb-4">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="font-heading text-lg text-hw-heading">{{ $step }}</span>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                </section>
                @break

            @case('founder_teaser')
                <x-founder-teaser
                    :heading="$section->heading"
                    :body="$section->content['body'] ?? null"
                    :credentials="$section->content['credentials'] ?? []"
                />
                @break

            @case('cta')
                <x-cta-section
                    :heading="$section->heading"
                    :variant="$section->content['variant'] ?? 'dual'"
                    :ctas="$ctas ?? null"
                />
                @break

            @case('forms')
                <section class="hw-section bg-hw-white" id="contact-forms">
                    <div class="hw-container max-w-2xl mx-auto">
                        <h2 class="font-heading text-3xl md:text-4xl text-hw-heading mb-4 text-center">{{ $section->heading }}</h2>

                        @if(! empty($compliance['contact_disclaimer']))
                            <p class="text-sm text-hw-muted text-center mb-10 leading-relaxed">
                                {{ $compliance['contact_disclaimer'] }}
                            </p>
                        @endif

                        <div class="space-y-12">
                            @if(in_array('waitlist', $section->content['forms'] ?? []))
                                <div id="waitlist" class="scroll-mt-header">
                                    <h3 class="font-heading text-xl text-hw-heading mb-4">Join the Waitlist</h3>
                                    <form
                                        x-data="formHandler()"
                                        @submit.prevent="submit($event)"
                                        action="{{ url('/api/waitlist') }}"
                                        method="POST"
                                        class="space-y-4"
                                    >
                                        @csrf
                                        <div>
                                            <label for="waitlist-name" class="block text-sm font-medium text-hw-heading mb-1">Name</label>
                                            <input type="text" id="waitlist-name" name="name" required class="w-full rounded-lg border border-hw-border px-4 py-3 text-hw-text focus:outline-none focus:ring-2 focus:ring-hw-dusty-blue">
                                        </div>
                                        <div>
                                            <label for="waitlist-email" class="block text-sm font-medium text-hw-heading mb-1">Email</label>
                                            <input type="email" id="waitlist-email" name="email" required class="w-full rounded-lg border border-hw-border px-4 py-3 text-hw-text focus:outline-none focus:ring-2 focus:ring-hw-dusty-blue">
                                        </div>
                                        <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="submitting">
                                            <span x-show="!submitting">Join the Waitlist</span>
                                            <span x-show="submitting" x-cloak>Submitting…</span>
                                        </button>
                                        <p x-show="success" x-cloak class="text-sm text-hw-dusty-blue font-medium">Thank you — we will be in touch soon.</p>
                                        <p x-show="error" x-cloak class="text-sm text-hw-blush font-medium">Something went wrong. Please try again.</p>
                                    </form>
                                </div>
                            @endif

                            @if(in_array('consultation', $section->content['forms'] ?? []))
                                <div id="consultation" class="scroll-mt-header pt-8 border-t border-hw-border">
                                    <h3 class="font-heading text-xl text-hw-heading mb-4">Request a Consultation</h3>
                                    <form
                                        x-data="formHandler()"
                                        @submit.prevent="submit($event)"
                                        action="{{ url('/api/consultation') }}"
                                        method="POST"
                                        class="space-y-4"
                                    >
                                        @csrf
                                        <div>
                                            <label for="consult-name" class="block text-sm font-medium text-hw-heading mb-1">Name</label>
                                            <input type="text" id="consult-name" name="name" required class="w-full rounded-lg border border-hw-border px-4 py-3 text-hw-text focus:outline-none focus:ring-2 focus:ring-hw-dusty-blue">
                                        </div>
                                        <div>
                                            <label for="consult-email" class="block text-sm font-medium text-hw-heading mb-1">Email</label>
                                            <input type="email" id="consult-email" name="email" required class="w-full rounded-lg border border-hw-border px-4 py-3 text-hw-text focus:outline-none focus:ring-2 focus:ring-hw-dusty-blue">
                                        </div>
                                        <div>
                                            <label for="consult-message" class="block text-sm font-medium text-hw-heading mb-1">How can we help?</label>
                                            <textarea id="consult-message" name="message" rows="4" class="w-full rounded-lg border border-hw-border px-4 py-3 text-hw-text focus:outline-none focus:ring-2 focus:ring-hw-dusty-blue"></textarea>
                                        </div>
                                        <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="submitting">
                                            <span x-show="!submitting">Request Consultation</span>
                                            <span x-show="submitting" x-cloak>Submitting…</span>
                                        </button>
                                        <p x-show="success" x-cloak class="text-sm text-hw-dusty-blue font-medium">Thank you — we will be in touch soon.</p>
                                        <p x-show="error" x-cloak class="text-sm text-hw-blush font-medium">Something went wrong. Please try again.</p>
                                    </form>
                                </div>
                            @endif

                            @if(in_array('group_inquiry', $section->content['forms'] ?? []))
                                <div id="group-inquiry" class="scroll-mt-header pt-8 border-t border-hw-border">
                                    <h3 class="font-heading text-xl text-hw-heading mb-4">Group Experience Inquiry</h3>
                                    <form
                                        x-data="formHandler()"
                                        @submit.prevent="submit($event)"
                                        action="{{ url('/api/group-inquiry') }}"
                                        method="POST"
                                        class="space-y-4"
                                    >
                                        @csrf
                                        <div>
                                            <label for="group-name" class="block text-sm font-medium text-hw-heading mb-1">Name</label>
                                            <input type="text" id="group-name" name="name" required class="w-full rounded-lg border border-hw-border px-4 py-3 text-hw-text focus:outline-none focus:ring-2 focus:ring-hw-dusty-blue">
                                        </div>
                                        <div>
                                            <label for="group-email" class="block text-sm font-medium text-hw-heading mb-1">Email</label>
                                            <input type="email" id="group-email" name="email" required class="w-full rounded-lg border border-hw-border px-4 py-3 text-hw-text focus:outline-none focus:ring-2 focus:ring-hw-dusty-blue">
                                        </div>
                                        <div>
                                            <label for="group-details" class="block text-sm font-medium text-hw-heading mb-1">Tell us about your group</label>
                                            <textarea id="group-details" name="details" rows="4" class="w-full rounded-lg border border-hw-border px-4 py-3 text-hw-text focus:outline-none focus:ring-2 focus:ring-hw-dusty-blue"></textarea>
                                        </div>
                                        <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="submitting">
                                            <span x-show="!submitting">Submit Inquiry</span>
                                            <span x-show="submitting" x-cloak>Submitting…</span>
                                        </button>
                                        <p x-show="success" x-cloak class="text-sm text-hw-dusty-blue font-medium">Thank you — we will be in touch soon.</p>
                                        <p x-show="error" x-cloak class="text-sm text-hw-blush font-medium">Something went wrong. Please try again.</p>
                                    </form>
                                </div>
                            @endif

                            <div id="book" class="scroll-mt-header pt-8 border-t border-hw-border">
                                <h3 class="font-heading text-xl text-hw-heading mb-4">Book a Visit</h3>
                                <p class="text-hw-muted text-sm leading-relaxed">
                                    Scheduling is coordinated through HeartWell. Contact us or join the waitlist to get started.
                                </p>
                                <a href="{{ url('/'.config('heartwell.ctas.primary.route').config('heartwell.ctas.primary.anchor')) }}" class="btn-primary mt-4 inline-flex">
                                    {{ config('heartwell.ctas.primary.label') }}
                                </a>
                            </div>
                        </div>

                        @if(! empty($compliance['privacy_summary']))
                            <p class="mt-10 text-xs text-hw-muted leading-relaxed text-center">
                                {{ $compliance['privacy_summary'] }}
                            </p>
                        @endif
                    </div>
                </section>
                @break

            @default
                <section class="hw-section bg-hw-white">
                    <div class="hw-container max-w-3xl mx-auto">
                        @if($section->heading)
                            <h2 class="font-heading text-3xl md:text-4xl text-hw-heading mb-6">{{ $section->heading }}</h2>
                        @endif
                        @if(! empty($section->content['body']))
                            <p class="text-hw-muted leading-relaxed">{{ $section->content['body'] }}</p>
                        @endif
                    </div>
                </section>
        @endswitch
    @endforeach

    @if(isset($faqs) && $faqs->isNotEmpty())
        <section class="hw-section bg-hw-taupe-light/30">
            <div class="hw-container max-w-3xl mx-auto">
                <h2 class="font-heading text-3xl text-hw-heading mb-8 text-center">Frequently Asked Questions</h2>
                <div class="space-y-3" x-data="pathwayAccordion(null)">
                    @foreach($faqs as $faq)
                        <div class="rounded-xl border border-hw-border bg-hw-white overflow-hidden">
                            <button
                                type="button"
                                class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left hover:bg-hw-blush-light/30 transition-colors"
                                @click="toggle('faq-{{ $faq->id }}')"
                                :aria-expanded="isOpen('faq-{{ $faq->id }}').toString()"
                            >
                                <span class="font-semibold text-hw-heading">{{ $faq->question }}</span>
                                <svg class="w-5 h-5 text-hw-dusty-blue shrink-0 transition-transform duration-200" :class="{ 'rotate-180': isOpen('faq-{{ $faq->id }}') }" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="isOpen('faq-{{ $faq->id }}')" x-cloak class="px-5 pb-5 border-t border-hw-border pt-4">
                                <p class="text-sm text-hw-muted leading-relaxed">{{ $faq->answer }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if(! $sections->contains('section_type', 'cta'))
        <x-cta-section :ctas="$ctas ?? null" />
    @endif
@endsection
