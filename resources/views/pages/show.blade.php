@extends('layouts.app')

@section('content')
    @php
        $hero = $sections->firstWhere('type', 'hero') ?? $sections->firstWhere('section_type', 'hero');
    @endphp

    @if($page->slug !== 'home' && $hero)
        <x-layout.page-hero
            :title="$hero->heading ?? $page->title"
            :subheading="$hero->subheading"
            :body="$hero->body"
        />
    @endif

    @if($page->slug === 'contact')
        @include('pages.partials.contact-forms', [
            'compliance' => $siteSettings['compliance'] ?? ($compliance ?? null),
            'ctas' => $siteSettings['ctas'] ?? ($ctas ?? null),
        ])
    @endif

    @if($page->slug !== 'contact')
        @include('pages.partials.sections', [
            'sections' => $hero && $page->slug !== 'home'
                ? $sections->whereNotIn('type', ['hero'])->whereNotIn('section_type', ['hero'])
                : $sections,
            'page' => $page,
            'pathways' => $pathways ?? collect(),
            'faqs' => $faqs ?? collect(),
            'ctas' => $siteSettings['ctas'] ?? ($ctas ?? null),
            'compliance' => $siteSettings['compliance'] ?? ($compliance ?? null),
        ])
    @else
        @include('pages.partials.sections', [
            'sections' => $sections->whereNotIn('type', ['hero', 'forms'])->whereNotIn('section_type', ['hero', 'forms']),
            'page' => $page,
            'pathways' => $pathways ?? collect(),
            'faqs' => $faqs ?? collect(),
            'ctas' => $siteSettings['ctas'] ?? ($ctas ?? null),
            'compliance' => $siteSettings['compliance'] ?? ($compliance ?? null),
        ])
    @endif
@endsection
