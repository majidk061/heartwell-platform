@extends('layouts.app')

@section('content')
    <section class="hw-section bg-hw-white">
        <x-layout.page-container narrow>
            <div class="text-center max-w-2xl mx-auto">
                <p class="text-sm uppercase tracking-wide text-hw-dusty-blue font-semibold">You're still with HeartWell</p>
                <h1 class="hw-page-title mt-2">Clinical Intake — Your Next Step</h1>
                <p class="text-base md:text-lg text-hw-text mt-4 md:mt-6 leading-relaxed">
                    This secure step collects your health history, consent, and provider screening — required before your first visit.
                    HeartWell coordinates everything; you are not navigating our care alone.
                </p>
            </div>

            <ol class="mt-10 space-y-4 max-w-xl mx-auto" aria-label="Clinical intake steps">
                <li class="flex gap-3 rounded-lg border border-hw-border p-4">
                    <span class="font-heading text-hw-dusty-blue">1</span>
                    <span class="text-hw-text">Review what to expect and how your information is protected.</span>
                </li>
                <li class="flex gap-3 rounded-lg border border-hw-border p-4">
                    <span class="font-heading text-hw-dusty-blue">2</span>
                    <span class="text-hw-text">Continue to our HIPAA-compliant secure clinical portal.</span>
                </li>
                <li class="flex gap-3 rounded-lg border border-hw-border p-4">
                    <span class="font-heading text-hw-dusty-blue">3</span>
                    <span class="text-hw-text">Return here anytime via your visit hub for scheduling support.</span>
                </li>
            </ol>

            <p class="text-hw-text mt-8 text-base text-center">{{ $siteSettings['compliance']['clinical_portal_note'] ?? config('heartwell.compliance.clinical_portal_note') }}</p>
            <p class="text-sm text-hw-muted mt-4 text-center">HeartWell remains your primary point of contact for scheduling and support.</p>

            <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center items-center">
                @if($portalEnabled ?? false)
                    <a href="{{ $portalUrl }}" class="btn-primary sm:w-auto inline-flex" target="_blank" rel="noopener noreferrer">
                        Continue to Secure Clinical Portal
                    </a>
                @else
                    <p class="text-hw-muted text-base text-center">Your HeartWell team will send a secure portal link before your visit.</p>
                @endif
                <a href="{{ route('my-visit') }}" class="btn-secondary sm:w-auto inline-flex">Your visit hub</a>
            </div>

            <a href="{{ route('contact') }}" class="block mt-6 text-center text-hw-dusty-blue hover:text-hw-heading text-base">Return to Contact</a>
        </x-layout.page-container>
    </section>
@endsection
