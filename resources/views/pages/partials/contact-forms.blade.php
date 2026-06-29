@php
    $forms = $siteSettings['contact_forms'] ?? [];
    $ctas = $ctas ?? ($siteSettings['ctas'] ?? config('heartwell.ctas'));
    $compliance = $compliance ?? ($siteSettings['compliance'] ?? config('heartwell.compliance'));
    $acuityEnabled = filled(config('integrations.acuity.embed_url'));
    $defaultTab = $acuityEnabled ? 'book' : 'waitlist';
@endphp

<section
    class="hw-section bg-hw-white border-t border-hw-border hw-contact-section"
    x-data="{
        activeTab: '{{ $defaultTab }}',
        acuityEnabled: @js($acuityEnabled),
        init() {
            const hash = window.location.hash.replace('#', '');
            const tabs = ['waitlist', 'consultation', 'book', 'group-inquiry'];
            if (tabs.includes(hash)) {
                this.activeTab = hash;
            } else if (! this.acuityEnabled) {
                this.activeTab = 'waitlist';
            }
            this.$watch('activeTab', (tab) => {
                history.replaceState(null, '', `#${tab}`);
            });
        },
        setTab(tab) {
            this.activeTab = tab;
            this.$nextTick(() => {
                document.getElementById('contact-panel')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }
    }"
>
    <x-layout.page-container narrow>
        <div class="text-center mb-6 md:mb-8">
            <h2 class="font-heading text-xl md:text-2xl text-hw-heading mb-1">How would you like to connect?</h2>
            <p class="text-hw-muted text-sm md:text-base max-w-xl mx-auto">Choose a path below — forms open right here, no long scrolling.</p>
        </div>

        <nav class="hw-contact-nav md:hidden mb-4" aria-label="Contact sections">
            <a href="#waitlist" @click.prevent="setTab('waitlist')" :class="activeTab === 'waitlist' ? 'hw-contact-tab--active' : ''">{{ $forms['waitlist_title'] ?? ($ctas['secondary']['waitlist']['label'] ?? 'Join Waitlist') }}</a>
            <a href="#consultation" @click.prevent="setTab('consultation')" :class="activeTab === 'consultation' ? 'hw-contact-tab--active' : ''">{{ $forms['consultation_title'] ?? ($ctas['secondary']['consultation']['label'] ?? 'Request Consultation') }}</a>
            @if($acuityEnabled)
                <a href="#book" @click.prevent="setTab('book')" :class="activeTab === 'book' ? 'hw-contact-tab--active' : ''">{{ $ctas['primary']['label'] ?? 'Book a Visit' }}</a>
            @endif
            <a href="#group-inquiry" @click.prevent="setTab('group-inquiry')" :class="activeTab === 'group-inquiry' ? 'hw-contact-tab--active' : ''">{{ $forms['group_title'] ?? 'Group Gathering' }}</a>
        </nav>

        <div class="grid lg:grid-cols-12 gap-6 lg:gap-8">
            <div class="hidden lg:block lg:col-span-4 lg:sticky lg:top-24 lg:self-start space-y-3">
                <x-contact-option-card
                    id="waitlist"
                    :title="$forms['waitlist_title'] ?? ($ctas['secondary']['waitlist']['label'] ?? 'Join the Waitlist')"
                    description="Be first to know when appointments open."
                    icon="bell"
                    :featured="! $acuityEnabled"
                />
                <x-contact-option-card
                    id="consultation"
                    :title="$forms['consultation_title'] ?? ($ctas['secondary']['consultation']['label'] ?? 'Request Consultation')"
                    description="Tell us about yourself — we will be in touch."
                    icon="chat"
                />
                @if($acuityEnabled)
                    <x-contact-option-card
                        id="book"
                        :title="$ctas['primary']['label'] ?? 'Book a Visit'"
                        description="Schedule your individual wellness visit."
                        icon="calendar"
                        :featured="true"
                    />
                @endif
                <x-contact-option-card
                    id="group-inquiry"
                    :title="$forms['group_title'] ?? 'Group Wellness Gathering'"
                    description="Host a private wellness experience."
                    icon="users"
                />
            </div>

            <div id="contact-panel" class="lg:col-span-8 scroll-mt-24">
                <div x-show="activeTab === 'waitlist'" x-cloak class="hw-contact-panel {{ ! $acuityEnabled ? 'hw-contact-panel--featured' : '' }}">
                    <x-layout.section-heading
                        :title="$forms['waitlist_title'] ?? ($ctas['secondary']['waitlist']['label'] ?? 'Join the Waitlist')"
                        :subtitle="$forms['waitlist_subtitle'] ?? 'Be the first to know when appointments open in your area.'"
                    />
                    <form method="POST" action="{{ route('contact.waitlist') }}" class="hw-form-group md:grid md:grid-cols-2 md:gap-x-4" x-data="{ loading: false }" @submit="loading = true">
                        @csrf
                        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
                        <div>
                            <label for="waitlist_name" class="hw-form-label">Name</label>
                            <input type="text" name="name" id="waitlist_name" required value="{{ old('name') }}" class="hw-form-field">
                        </div>
                        <div>
                            <label for="waitlist_email" class="hw-form-label">Email</label>
                            <input type="email" name="email" id="waitlist_email" required value="{{ old('email') }}" class="hw-form-field">
                        </div>
                        <div class="md:col-span-2">
                            <label for="waitlist_phone" class="hw-form-label">Phone (optional)</label>
                            <input type="tel" name="phone" id="waitlist_phone" value="{{ old('phone') }}" class="hw-form-field">
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="loading">
                                <span x-show="!loading">{{ $ctas['secondary']['waitlist']['label'] ?? 'Join the Waitlist' }}</span>
                                <span x-show="loading" x-cloak>Submitting…</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div x-show="activeTab === 'consultation'" x-cloak class="hw-contact-panel">
                    <x-layout.section-heading
                        :title="$forms['consultation_title'] ?? ($ctas['secondary']['consultation']['label'] ?? 'Request Consultation')"
                        :subtitle="$forms['consultation_subtitle'] ?? 'Tell us a little about yourself — we\'ll be in touch soon.'"
                    />
                    <form method="POST" action="{{ route('contact.consultation') }}" class="hw-form-group md:grid md:grid-cols-2 md:gap-x-4" x-data="{ loading: false }" @submit="loading = true">
                        @csrf
                        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
                        <div>
                            <label for="consult_name" class="hw-form-label">Name</label>
                            <input type="text" name="name" id="consult_name" required value="{{ old('name') }}" class="hw-form-field">
                        </div>
                        <div>
                            <label for="consult_email" class="hw-form-label">Email</label>
                            <input type="email" name="email" id="consult_email" required value="{{ old('email') }}" class="hw-form-field">
                        </div>
                        <div class="md:col-span-2">
                            <label for="consult_phone" class="hw-form-label">Phone</label>
                            <input type="tel" name="phone" id="consult_phone" value="{{ old('phone') }}" class="hw-form-field">
                        </div>
                        <div class="md:col-span-2">
                            <label for="consult_message" class="hw-form-label">How can we support you?</label>
                            <textarea name="message" id="consult_message" rows="4" class="hw-form-field min-h-[120px]">{{ old('message') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="loading">
                                <span x-show="!loading">{{ $ctas['secondary']['consultation']['label'] ?? 'Request Consultation' }}</span>
                                <span x-show="loading" x-cloak>Submitting…</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div x-show="activeTab === 'book'" class="hw-contact-panel hw-contact-panel--featured">
                    <x-layout.section-heading
                        :title="$ctas['primary']['label'] ?? 'Book a Visit'"
                        subtitle="Schedule your individual wellness visit."
                    />
                    @if(config('integrations.acuity.embed_url'))
                        <div class="mt-4 w-full overflow-hidden rounded-xl shadow-md border border-hw-border">
                            <iframe src="{{ config('integrations.acuity.embed_url') }}" class="w-full min-h-[400px] md:min-h-[600px] border-0" title="Book a Visit"></iframe>
                        </div>
                        <p class="mt-4 text-sm text-hw-muted leading-relaxed">
                            {{ $compliance['clinical_portal_note'] ?? ($compliance['hydreight_note'] ?? config('heartwell.compliance.clinical_portal_note')) }}
                            <a href="{{ route('clinical-intake') }}" class="text-hw-dusty-blue font-medium hover:text-hw-heading">Continue to clinical intake →</a>
                        </p>
                    @else
                        <div class="rounded-lg border border-hw-border bg-hw-dusty-blue-light px-5 py-6 text-center space-y-3">
                            <p class="font-heading text-lg text-hw-heading">Online scheduling is coming soon</p>
                            <p class="text-hw-muted text-base">Join the waitlist or request a consultation — we will reach out to help you book your visit.</p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
                                <button type="button" @click="setTab('waitlist')" class="btn-primary sm:w-auto">Join the Waitlist</button>
                                <button type="button" @click="setTab('consultation')" class="btn-secondary sm:w-auto">Request Consultation</button>
                            </div>
                        </div>
                    @endif
                </div>

                <div x-show="activeTab === 'group-inquiry'" x-cloak class="hw-contact-panel">
                    <x-layout.section-heading
                        :title="$forms['group_title'] ?? 'Group Wellness Gathering'"
                        :subtitle="$forms['group_subtitle'] ?? 'Tell us about your gathering.'"
                    />
                    @php
                        $groupNote = $compliance['group_intake_note'] ?? config('heartwell.compliance.group_intake_note');
                    @endphp
                    @if(! empty($groupNote))
                        <div class="rounded-lg border border-hw-border bg-hw-blush/30 px-4 py-3 text-sm text-hw-muted mb-6">
                            {{ $groupNote }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('contact.group-inquiry') }}" class="hw-form-group md:grid md:grid-cols-2 md:gap-x-4" x-data="{ loading: false }" @submit="loading = true">
                        @csrf
                        <input type="text" name="website" class="hidden" tabindex="-1" autocomplete="off">
                        <div>
                            <label for="group_host" class="hw-form-label">Host Name</label>
                            <input type="text" name="host_name" id="group_host" required value="{{ old('host_name') }}" class="hw-form-field">
                        </div>
                        <div>
                            <label for="group_email" class="hw-form-label">Email</label>
                            <input type="email" name="email" id="group_email" required value="{{ old('email') }}" class="hw-form-field">
                        </div>
                        <div>
                            <label for="group_phone" class="hw-form-label">Phone</label>
                            <input type="tel" name="phone" id="group_phone" value="{{ old('phone') }}" class="hw-form-field">
                        </div>
                        <div>
                            <label for="group_count" class="hw-form-label">Expected Guests</label>
                            <input type="number" name="guest_count" id="group_count" min="2" value="{{ old('guest_count', 5) }}" class="hw-form-field">
                        </div>
                        <div class="md:col-span-2">
                            <label for="group_details" class="hw-form-label">Event Details</label>
                            <textarea name="event_details" id="group_details" rows="3" required class="hw-form-field min-h-[100px]">{{ old('event_details') }}</textarea>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" class="btn-primary w-full sm:w-auto" :disabled="loading">Submit Group Inquiry</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="mt-10 pt-8 border-t border-hw-border space-y-3">
            <p class="text-sm text-hw-muted leading-relaxed">
                {{ $compliance['contact_disclaimer'] ?? config('heartwell.compliance.contact_disclaimer') }}
            </p>
            @if(! empty($compliance['privacy_summary']))
                <p class="text-xs text-hw-muted leading-relaxed">
                    {{ $compliance['privacy_summary'] }}
                </p>
            @endif
        </div>
    </x-layout.page-container>
</section>
