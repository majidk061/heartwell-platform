@extends('layouts.app')

@section('content')
    <section class="hw-section bg-hw-white">
        <x-layout.page-container narrow>
            <div class="text-center mb-10">
                <h1 class="hw-page-title">Your HeartWell Visit</h1>
                <p class="text-hw-text mt-4 text-base md:text-lg leading-relaxed">
                    Everything you need before and after your wellness appointment — all in one place.
                </p>
            </div>

            <ol class="space-y-6">
                @foreach($steps as $index => $step)
                    <li class="rounded-xl border border-hw-border bg-hw-white p-6 shadow-sm">
                        <div class="flex items-start gap-4">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-hw-dusty-blue-light font-heading text-hw-heading">
                                {{ $index + 1 }}
                            </span>
                            <div class="flex-1">
                                <h2 class="font-heading text-xl text-hw-heading">{{ $step['title'] }}</h2>
                                <p class="text-hw-text mt-2 leading-relaxed">{{ $step['body'] }}</p>
                                @if(! empty($step['cta_route']))
                                    <a href="{{ route($step['cta_route']) }}" class="btn-secondary sm:w-auto inline-flex mt-4">
                                        {{ $step['cta_label'] }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ol>

            @if($portalEnabled ?? false)
                <div class="mt-10 rounded-xl border border-hw-dusty-blue/30 bg-hw-dusty-blue-light/40 p-6 text-center">
                    <p class="text-hw-heading font-medium">Ready to complete your clinical intake?</p>
                    <a href="{{ route('clinical-intake') }}" class="btn-primary sm:w-auto inline-flex mt-4">Continue to secure portal</a>
                </div>
            @endif
        </x-layout.page-container>
    </section>
@endsection
