@extends('layouts.app')

@section('content')
    @php
        $hero = $sections->firstWhere('type', 'hero');
        $skipTypes = $page->slug === 'contact' ? ['hero', 'forms', 'cta'] : ['hero'];
    @endphp

    <x-layout.page-hero
        :title="$hero?->heading ?? $page->title"
        :subheading="$hero?->subheading"
        :body="$hero?->body"
    />

    @foreach($sections->whereNotIn('type', $skipTypes) as $section)
        <section class="hw-section bg-hw-white {{ !$loop->last && $page->slug !== 'contact' ? 'border-b border-hw-border' : '' }}">
            <x-layout.page-container narrow>
                @if($section->heading)
                    <x-layout.section-heading :title="$section->heading" />
                @endif
                @if($section->body)
                    <div class="text-hw-text leading-relaxed whitespace-pre-line text-base">{{ $section->body }}</div>
                @endif
            </x-layout.page-container>
        </section>
    @endforeach

    @if($page->slug === 'support-pathways' && !empty($pathways))
        <x-pathway-accordion :pathways="$pathways" />
    @endif

    @if($page->slug === 'contact')
        @include('pages.partials.contact-forms')
    @endif

    @if(in_array($page->slug, ['meet-the-founder', 'support-pathways', 'your-experience', 'why-heartwell', 'wellness-journey']))
        <x-cta-section />
    @endif
@endsection
