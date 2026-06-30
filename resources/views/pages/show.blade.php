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

    @include('pages.partials.sections', [
        'sections' => $page->slug === 'home'
            ? $sections
            : ($hero && $page->slug !== 'home'
                ? $sections->filter(fn ($s) => ! in_array($s->section_type ?? $s->type, ['hero']))
                : $sections),
        'page' => $page,
        'pathways' => $pathways ?? collect(),
        'faqs' => $faqs ?? collect(),
        'testimonials' => $testimonials ?? collect(),
        'testimonialSettings' => $testimonialSettings ?? ($siteSettings['home'] ?? []),
        'avatarCards' => $avatarCards ?? collect(),
        'ctas' => $siteSettings['ctas'] ?? ($ctas ?? config('heartwell.ctas')),
        'compliance' => $siteSettings['compliance'] ?? ($compliance ?? config('heartwell.compliance')),
        'isHome' => $page->slug === 'home',
        'themeDefaults' => $siteSettings['theme'] ?? [],
    ])
@endsection
