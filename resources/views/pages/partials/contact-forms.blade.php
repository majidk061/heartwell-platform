@php
    use App\Domains\Content\Support\SectionLayout;

    $formsContent = ($formsSection ?? null)?->content ?? [];
    $sectionHeading = ($formsSection ?? null)?->heading ?? 'How would you like to connect?';
    $sectionSubtitle = $formsContent['section_subtitle'] ?? 'Choose a path below — we are here when you are ready.';
    $activeForms = $formsContent['forms'] ?? ['waitlist', 'consultation', 'book', 'group_inquiry'];
    $ctas = $ctas ?? ($siteSettings['ctas'] ?? config('heartwell.ctas'));
    $compliance = $compliance ?? ($siteSettings['compliance'] ?? config('heartwell.compliance'));
    $forms = array_merge($siteSettings['contact_forms'] ?? [], [
        'waitlist_title' => $formsContent['waitlist_title'] ?? ($siteSettings['contact_forms']['waitlist_title'] ?? 'Join the Waitlist'),
        'waitlist_subtitle' => $formsContent['waitlist_subtitle'] ?? ($siteSettings['contact_forms']['waitlist_subtitle'] ?? 'Be the first to know when appointments open in your area.'),
        'consultation_title' => $formsContent['consultation_title'] ?? ($siteSettings['contact_forms']['consultation_title'] ?? 'Request a Consultation'),
        'consultation_subtitle' => $formsContent['consultation_subtitle'] ?? ($siteSettings['contact_forms']['consultation_subtitle'] ?? 'Tell us a little about yourself — we will reach out personally.'),
        'group_title' => $formsContent['group_title'] ?? ($siteSettings['contact_forms']['group_title'] ?? 'Group Wellness Gathering'),
        'group_subtitle' => $formsContent['group_subtitle'] ?? ($siteSettings['contact_forms']['group_subtitle'] ?? 'Planning a group experience? Start here.'),
    ]);
    $contactDisclaimer = $formsContent['contact_disclaimer'] ?? ($compliance['contact_disclaimer'] ?? config('heartwell.compliance.contact_disclaimer'));
    $privacySummary = $formsContent['privacy_summary'] ?? ($compliance['privacy_summary'] ?? null);
    $clinicalPortalNote = $formsContent['clinical_portal_note'] ?? ($compliance['clinical_portal_note'] ?? config('heartwell.compliance.clinical_portal_note'));
    $groupIntakeNote = $formsContent['group_intake_note'] ?? ($compliance['group_intake_note'] ?? config('heartwell.compliance.group_intake_note'));
    $acuityEnabled = filled(config('integrations.acuity.embed_url')) && in_array('book', $activeForms, true);
    $defaultTab = $acuityEnabled && in_array('book', $activeForms, true)
        ? 'book'
        : (in_array('waitlist', $activeForms, true) ? 'waitlist' : ($activeForms[0] ?? 'waitlist'));

    $layout = ($formsSection ?? null)
        ? SectionLayout::resolve($formsContent, $themeDefaults ?? ($siteSettings['theme'] ?? []), 'forms')
        : ['container_width' => 'default', 'section_padding' => 'normal', 'background' => 'white', 'text_align' => 'center'];

    $sectionClass = SectionLayout::sectionClasses($layout).' border-t border-hw-border hw-contact-section';
@endphp

<section
    class="{{ $sectionClass }}"
    x-data="{
        activeTab: '{{ $defaultTab }}',
        acuityEnabled: @js($acuityEnabled),
        init() {
            this.syncFromHash();
            window.addEventListener('hashchange', () => this.syncFromHash());
        },
        syncFromHash() {
            const hash = window.location.hash.replace('#', '');
            const tabs = ['waitlist', 'consultation', 'book', 'group-inquiry'];
            if (tabs.includes(hash)) {
                this.activeTab = hash;
            } else if (! this.acuityEnabled && ! tabs.includes(this.activeTab)) {
                this.activeTab = 'waitlist';
            }
            this.$nextTick(() => {
                if (tabs.includes(this.activeTab)) {
                    document.getElementById('contact-panel')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        },
        setTab(tab) {
            this.activeTab = tab;
            history.replaceState(null, '', `#${tab}`);
            this.$nextTick(() => {
                document.getElementById('contact-panel')?.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        }
    }"
>
    <x-layout.page-container :width="$layout['container_width']">
        <div class="text-center mb-8 md:mb-10">
            <h2 class="hw-section-title">{{ $sectionHeading }}</h2>
            @if($sectionSubtitle)
                <p class="text-hw-muted text-base md:text-lg max-w-2xl mx-auto mt-3">{{ $sectionSubtitle }}</p>
            @endif
        </div>

        <nav class="hw-contact-nav mb-6 lg:hidden" aria-label="Contact sections">
            @if(in_array('waitlist', $activeForms, true))
                <a href="#waitlist" @click.prevent="setTab('waitlist')" :class="activeTab === 'waitlist' ? 'hw-contact-tab--active' : ''">{{ $forms['waitlist_title'] }}</a>
            @endif
            @if(in_array('consultation', $activeForms, true))
                <a href="#consultation" @click.prevent="setTab('consultation')" :class="activeTab === 'consultation' ? 'hw-contact-tab--active' : ''">{{ $forms['consultation_title'] }}</a>
            @endif
            @if($acuityEnabled)
                <a href="#book" @click.prevent="setTab('book')" :class="activeTab === 'book' ? 'hw-contact-tab--active' : ''">{{ $ctas['primary']['label'] ?? 'Book a Visit' }}</a>
            @endif
            @if(in_array('group_inquiry', $activeForms, true))
                <a href="#group-inquiry" @click.prevent="setTab('group-inquiry')" :class="activeTab === 'group-inquiry' ? 'hw-contact-tab--active' : ''">{{ $forms['group_title'] }}</a>
            @endif
        </nav>

        <div class="grid lg:grid-cols-12 gap-6 lg:gap-10">
            <div class="hidden lg:block lg:col-span-5 lg:sticky lg:top-24 lg:self-start space-y-4">
                @if(in_array('waitlist', $activeForms, true))
                    <x-contact-option-card id="waitlist" :title="$forms['waitlist_title']" description="Be first to know when appointments open." icon="bell" :featured="! $acuityEnabled" />
                @endif
                @if(in_array('consultation', $activeForms, true))
                    <x-contact-option-card id="consultation" :title="$forms['consultation_title']" description="Tell us about yourself — we will be in touch." icon="chat" />
                @endif
                @if($acuityEnabled)
                    <x-contact-option-card id="book" :title="$ctas['primary']['label'] ?? 'Book a Visit'" description="Schedule your individual wellness visit." icon="calendar" :featured="true" />
                @endif
                @if(in_array('group_inquiry', $activeForms, true))
                    <x-contact-option-card id="group-inquiry" :title="$forms['group_title']" description="Host a private wellness experience." icon="users" />
                @endif
            </div>

            <div id="contact-panel" class="lg:col-span-7 scroll-mt-24">
                @if(in_array('waitlist', $activeForms, true))
                <div x-show="activeTab === 'waitlist'" x-cloak class="hw-contact-panel" :class="activeTab === 'waitlist' ? 'hw-contact-panel--active' : ''">
                    <x-layout.section-heading :title="$forms['waitlist_title']" :subtitle="$forms['waitlist_subtitle']" />
                    <form method="POST" action="{{ route('contact.waitlist') }}" class="hw-form-group md:grid md:grid-cols-2 md:gap-x-4 mt-6" x-data="{ loading: false }" @submit="loading = true">
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
                @endif

                @if(in_array('consultation', $activeForms, true))
                <div x-show="activeTab === 'consultation'" x-cloak class="hw-contact-panel" :class="activeTab === 'consultation' ? 'hw-contact-panel--active' : ''">
                    <x-layout.section-heading :title="$forms['consultation_title']" :subtitle="$forms['consultation_subtitle']" />
                    <form method="POST" action="{{ route('contact.consultation') }}" class="hw-form-group md:grid md:grid-cols-2 md:gap-x-4 mt-6" x-data="{ loading: false }" @submit="loading = true">
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
                @endif

                @if($acuityEnabled)
                <div x-show="activeTab === 'book'" class="hw-contact-panel hw-contact-panel--featured" :class="activeTab === 'book' ? 'hw-contact-panel--active' : ''">
                    <x-layout.section-heading :title="$ctas['primary']['label'] ?? 'Book a Visit'" subtitle="Schedule your individual wellness visit." />
                    @if(config('integrations.acuity.embed_url'))
                        <div class="mt-6 w-full overflow-hidden rounded-xl shadow-md border border-hw-border">
                            <iframe src="{{ config('integrations.acuity.embed_url') }}" class="w-full min-h-[400px] md:min-h-[600px] border-0" title="Book a Visit"></iframe>
                        </div>
                        <p class="mt-4 text-sm text-hw-muted leading-relaxed">
                            {{ $clinicalPortalNote }}
                            <a href="{{ route('clinical-intake') }}" class="text-hw-dusty-blue font-medium hover:text-hw-heading">Continue to clinical intake →</a>
                        </p>
                    @else
                        <div class="rounded-lg border border-hw-border bg-hw-dusty-blue-light px-5 py-6 text-center space-y-3 mt-6">
                            <p class="font-heading text-lg text-hw-heading">Online scheduling is coming soon</p>
                            <p class="text-hw-muted text-base">Join the waitlist or request a consultation — we will reach out to help you book your visit.</p>
                            <div class="flex flex-col sm:flex-row gap-3 justify-center pt-2">
                                <button type="button" @click="setTab('waitlist')" class="btn-primary sm:w-auto">Join the Waitlist</button>
                                <button type="button" @click="setTab('consultation')" class="btn-secondary sm:w-auto">Request Consultation</button>
                            </div>
                        </div>
                    @endif
                </div>
                @endif

                @if(in_array('group_inquiry', $activeForms, true))
                <div x-show="activeTab === 'group-inquiry'" x-cloak class="hw-contact-panel" :class="activeTab === 'group-inquiry' ? 'hw-contact-panel--active' : ''">
                    <x-layout.section-heading :title="$forms['group_title']" :subtitle="$forms['group_subtitle']" />
                    @if(! empty($groupIntakeNote))
                        <div class="rounded-lg border border-hw-border bg-hw-blush/30 px-4 py-3 text-sm text-hw-muted mb-6 mt-4">
                            {{ $groupIntakeNote }}
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
                @endif
            </div>
        </div>

        <div class="mt-10 pt-8 border-t border-hw-border space-y-3 max-w-3xl">
            @if(! empty($contactDisclaimer))
            <p class="text-sm text-hw-muted leading-relaxed">
                {{ $contactDisclaimer }}
            </p>
            @endif
            @if(! empty($privacySummary))
                <p class="text-xs text-hw-muted leading-relaxed">{{ $privacySummary }}</p>
            @endif
        </div>
    </x-layout.page-container>
</section>
