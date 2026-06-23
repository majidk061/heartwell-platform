@extends('layouts.app')

@section('content')
    @php
        $hero = $sections->firstWhere('type', 'hero');
        $intro = $sections->firstWhere('type', 'intro');
        $founder = $sections->firstWhere('type', 'founder_teaser');
    @endphp

    <x-hero
        :headline="$hero?->heading ?? config('heartwell.brand.promise')"
        :tagline="$hero?->subheading ?? config('heartwell.brand.tagline')"
        :body="$hero?->body"
        :image-url="$hero?->image_url"
    />

    @if($pathways->isNotEmpty())
        <x-pathway-bar :pathways="$pathways" />
    @endif

    <section class="hw-section bg-hw-white">
        <x-layout.page-container>
            <h2 class="hw-section-title text-center">You're Not Alone. You Deserve Support.</h2>
            <p class="text-center text-hw-muted mt-3 text-base md:text-lg">Which of these feels most like you?</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8 md:mt-10">
                @foreach(config('heartwell.avatar_cards') as $card)
                    <x-avatar-card :card="$card" />
                @endforeach
            </div>
        </x-layout.page-container>
    </section>

    @if($intro)
        <section class="hw-section bg-hw-white border-t border-hw-border">
            <x-layout.page-container narrow class="text-center">
                @if($intro->heading)
                    <h2 class="hw-section-title">{{ $intro->heading }}</h2>
                @endif
                @if($intro->body)
                    <p class="text-base md:text-lg text-hw-text mt-4 leading-relaxed">{{ $intro->body }}</p>
                @endif
            </x-layout.page-container>
        </section>
    @endif

    @if($pathways->isNotEmpty())
        <x-pathway-accordion :pathways="$pathways->take(3)" />
    @endif

    <x-founder-teaser :section="$founder" />

    <x-cta-section />
@endsection
