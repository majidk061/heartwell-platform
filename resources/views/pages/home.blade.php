@extends('layouts.app')

@section('content')
    @include('pages.partials.sections', [
        'sections' => $sections,
        'page' => $page,
        'pathways' => $pathways ?? collect(),
        'testimonials' => $testimonials ?? collect(),
        'avatarCards' => $avatarCards ?? collect(),
        'ctas' => $siteSettings['ctas'] ?? ($ctas ?? config('heartwell.ctas')),
        'compliance' => $siteSettings['compliance'] ?? config('heartwell.compliance'),
        'isHome' => true,
    ])
@endsection
