@extends('layouts.app')

@section('content')
    @php
        $title = $compliance['privacy_policy_title'] ?? config('heartwell.compliance.privacy_policy_title', 'Privacy Policy');
        $lastUpdated = $compliance['privacy_policy_last_updated'] ?? null;
        $bodyHtml = $compliance['privacy_policy_body'] ?? config('heartwell.compliance.privacy_policy_body');
        $summary = $compliance['privacy_summary'] ?? config('heartwell.compliance.privacy_summary');
    @endphp
    <section class="hw-section bg-hw-white">
        <x-layout.page-container width="narrow">
            <h1 class="hw-page-title">{{ $title }}</h1>
            <p class="text-hw-muted mt-3 text-base">
                Last updated:
                @if($lastUpdated)
                    {{ \Illuminate\Support\Carbon::parse($lastUpdated)->format('F j, Y') }}
                @else
                    {{ now()->format('F j, Y') }}
                @endif
            </p>

            <div class="prose prose-hw max-w-none mt-8 space-y-6 text-hw-text leading-relaxed">
                @if(filled($summary))
                    <p>{{ $summary }}</p>
                @endif
                @if(filled($bodyHtml))
                    {!! $bodyHtml !!}
                @endif
            </div>
        </x-layout.page-container>
    </section>
@endsection
