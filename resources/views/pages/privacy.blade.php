@extends('layouts.app')

@section('content')
    <section class="hw-section bg-hw-white">
        <x-layout.page-container width="narrow">
            <h1 class="hw-page-title">Privacy Policy</h1>
            <p class="text-hw-muted mt-3 text-base">Last updated: {{ now()->format('F j, Y') }}</p>

            <div class="prose prose-hw max-w-none mt-8 space-y-6 text-hw-text leading-relaxed">
                <p>{{ $compliance['privacy_summary'] ?? config('heartwell.compliance.privacy_summary') }}</p>

                <h2 class="font-heading text-xl text-hw-heading mt-8">Information we collect</h2>
                <p>When you contact HeartWell, join the waitlist, request a consultation, or book a visit, we collect the information you provide — such as your name, email, phone number, and wellness goals — so we can respond and coordinate care appropriately.</p>

                <h2 class="font-heading text-xl text-hw-heading mt-8">How we use your information</h2>
                <p>Your information is used to respond to your request, coordinate nurse-led wellness support, and — when clinically appropriate — guide you through secure intake and provider screening. We do not sell your personal information.</p>

                <h2 class="font-heading text-xl text-hw-heading mt-8">Clinical intake &amp; HIPAA</h2>
                <p>{{ $compliance['clinical_portal_note'] ?? config('heartwell.compliance.clinical_portal_note') }}</p>

                <h2 class="font-heading text-xl text-hw-heading mt-8">Contact</h2>
                <p>Questions about this policy? Please reach out through our <a href="{{ route('contact') }}" class="text-hw-dusty-blue font-medium hover:text-hw-heading transition-colors">Contact page</a>.</p>
            </div>
        </x-layout.page-container>
    </section>
@endsection
