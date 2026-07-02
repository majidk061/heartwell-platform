@extends('layouts.app')

@section('content')
    @include('pages.partials.sections', [
        'sections' => $sections,
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
