@extends('layouts.app')

@section('content')
    <div class="bg-hw-navy text-hw-white py-2 px-4 text-center text-sm" role="status">
        Section preview — {{ $template->name }} ({{ $template->section_type }})
        · <a href="{{ \App\Filament\Resources\Content\SectionTemplateResource::getUrl('edit', ['record' => $template]) }}" class="underline font-medium">Back to editor</a>
    </div>

    @include('pages.partials.sections', [
        'sections' => $sections,
        'pathways' => $pathways,
        'avatarCards' => $avatarCards,
        'testimonials' => $testimonials,
        'testimonialSettings' => $testimonialSettings,
        'faqs' => $faqs,
        'ctas' => $ctas,
        'compliance' => $compliance,
        'isHome' => $isHome,
        'themeDefaults' => $themeDefaults,
    ])
@endsection
