@extends('layouts.app')

@section('content')
    @php
        $hero = $sections->firstWhere('type', 'hero') ?? $sections->firstWhere('section_type', 'hero');
    @endphp

    @if($page->slug !== 'home' && $hero && ! in_array($page->slug, ['contact']))
        <x-layout.page-hero
            :title="$hero->heading ?? $page->title"
            :subheading="$hero->subheading"
            :body="$hero->body"
        />
    @endif

    @include('pages.partials.sections', [
        'sections' => $page->slug === 'contact'
            ? $sections->whereNotIn('type', ['hero', 'forms'])->whereNotIn('section_type', ['hero', 'forms'])
            : ($hero && $page->slug !== 'home' ? $sections->whereNotIn('type', ['hero'])->whereNotIn('section_type', ['hero']) : $sections),
        'page' => $page,
        'pathways' => $pathways ?? collect(),
        'faqs' => $faqs ?? collect(),
        'ctas' => $siteSettings['ctas'] ?? ($ctas ?? null),
        'compliance' => $siteSettings['compliance'] ?? ($compliance ?? null),
    ])

    @if($page->slug === 'contact')
        @include('pages.partials.contact-forms', [
            'compliance' => $siteSettings['compliance'] ?? ($compliance ?? null),
            'ctas' => $siteSettings['ctas'] ?? ($ctas ?? null),
        ])
    @endif
@endsection
