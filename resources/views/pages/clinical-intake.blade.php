@extends('layouts.app')

@section('content')
    <section class="hw-section bg-hw-white">
        <x-layout.page-container narrow class="text-center">
            <h1 class="hw-page-title">Clinical Intake — Next Step</h1>
            <p class="text-base md:text-lg text-hw-text mt-4 md:mt-6 leading-relaxed">
                You're continuing your HeartWell journey. Our secure clinical portal collects your health history,
                consent, and provider screening — required before your first visit.
            </p>
            <p class="text-hw-text mt-4 text-base">{{ $siteSettings['compliance']['clinical_portal_note'] ?? config('heartwell.compliance.clinical_portal_note') }}</p>
            <p class="text-sm text-hw-muted mt-6">HeartWell remains your primary point of contact for scheduling and support.</p>
            @if(config('integrations.hydreight.portal_url'))
                <a href="{{ config('integrations.hydreight.portal_url') }}" class="btn-primary sm:w-auto inline-flex mt-8" target="_blank" rel="noopener">
                    Continue to Secure Clinical Portal
                </a>
            @else
                <p class="mt-8 text-hw-muted text-base">Your HeartWell team will send a secure portal link before your visit.</p>
            @endif
            <a href="{{ route('contact') }}" class="block mt-4 text-hw-dusty-blue hover:text-hw-heading text-base">Return to Contact</a>
        </x-layout.page-container>
    </section>
@endsection
