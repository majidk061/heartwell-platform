<section class="hw-section bg-hw-white border-t border-hw-border">
    <x-layout.page-container narrow>
        {{-- Anchor mini-nav --}}
        <nav class="hw-contact-nav md:flex-wrap md:overflow-visible mb-8 md:mb-10" aria-label="Contact sections">
            <a href="#waitlist">Waitlist</a>
            <a href="#consultation">Consultation</a>
            <a href="#book">Book a Visit</a>
            <a href="#group-inquiry">Group Inquiry</a>
        </nav>

        <div class="space-y-12 md:space-y-16">
            {{-- Waitlist --}}
            <div id="waitlist" class="scroll-mt-24">
                <x-layout.section-heading
                    :title="config('heartwell.ctas.secondary.waitlist.label')"
                    subtitle="Be the first to know when appointments open in your area."
                />
                <form method="POST" action="{{ route('contact.waitlist') }}" class="hw-form-group" x-data="{ loading: false }" @submit="loading = true">
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
                    <div>
                        <label for="waitlist_phone" class="hw-form-label">Phone (optional)</label>
                        <input type="tel" name="phone" id="waitlist_phone" value="{{ old('phone') }}" class="hw-form-field">
                    </div>
                    <button type="submit" class="btn-primary sm:w-auto" :disabled="loading">
                        <span x-show="!loading">Join the Waitlist</span>
                        <span x-show="loading" x-cloak>Submitting…</span>
                    </button>
                </form>
            </div>

            {{-- Consultation --}}
            <div id="consultation" class="scroll-mt-24 pt-8 md:pt-10 border-t border-hw-border">
                <x-layout.section-heading
                    :title="config('heartwell.ctas.secondary.consultation.label')"
                    subtitle="Tell us a little about yourself — we'll be in touch soon."
                />
                <form method="POST" action="{{ route('contact.consultation') }}" class="hw-form-group" x-data="{ loading: false }" @submit="loading = true">
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
                    <div>
                        <label for="consult_phone" class="hw-form-label">Phone</label>
                        <input type="tel" name="phone" id="consult_phone" value="{{ old('phone') }}" class="hw-form-field">
                    </div>
                    <div>
                        <label for="consult_message" class="hw-form-label">How can we support you?</label>
                        <textarea name="message" id="consult_message" rows="4" class="hw-form-field min-h-[120px]">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary sm:w-auto" :disabled="loading">
                        <span x-show="!loading">Request Consultation</span>
                        <span x-show="loading" x-cloak>Submitting…</span>
                    </button>
                </form>
            </div>

            {{-- Book a Visit --}}
            <div id="book" class="scroll-mt-24 pt-8 md:pt-10 border-t border-hw-border">
                <x-layout.section-heading
                    :title="config('heartwell.ctas.primary.label')"
                    subtitle="Schedule your individual wellness visit."
                />
                @if(config('integrations.acuity.embed_url'))
                    <div class="mt-4 w-full overflow-hidden rounded-lg">
                        <iframe src="{{ config('integrations.acuity.embed_url') }}" class="w-full min-h-[400px] md:min-h-[600px] border-0" title="Book a Visit"></iframe>
                    </div>
                @else
                    <p class="text-hw-muted text-base">Online booking will appear here once Acuity is configured. Use the consultation form above in the meantime.</p>
                @endif
            </div>

            {{-- Group inquiry --}}
            <div id="group-inquiry" class="scroll-mt-24 pt-8 md:pt-10 border-t border-hw-border">
                <x-layout.section-heading
                    title="Group Wellness Gathering"
                    :subtitle="config('heartwell.compliance.hydreight_note')"
                />
                <form method="POST" action="{{ route('contact.group-inquiry') }}" class="hw-form-group" x-data="{ loading: false }" @submit="loading = true">
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
                        <label for="group_details" class="hw-form-label">Event Details</label>
                        <textarea name="event_details" id="group_details" rows="3" required class="hw-form-field min-h-[100px]">{{ old('event_details') }}</textarea>
                    </div>
                    <div>
                        <label for="group_count" class="hw-form-label">Expected Guests</label>
                        <input type="number" name="guest_count" id="group_count" min="2" value="{{ old('guest_count', 5) }}" class="hw-form-field">
                    </div>
                    <button type="submit" class="btn-primary sm:w-auto" :disabled="loading">Submit Group Inquiry</button>
                </form>
            </div>

            {{-- Disclaimer --}}
            <p class="text-sm text-hw-muted pt-8 border-t border-hw-border leading-relaxed">
                {{ config('heartwell.compliance.contact_disclaimer') }}
            </p>
        </div>
    </x-layout.page-container>
</section>
