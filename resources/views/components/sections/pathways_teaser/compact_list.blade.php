@props(['pathways', 'title' => null, 'section' => null, 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\SectionLayout;

    $sectionContent = $section?->content ?? [];
    $layout = $section
        ? SectionLayout::resolve($sectionContent, $themeDefaults ?? ($siteSettings['theme'] ?? []), 'pathways_teaser')
        : ['container_width' => 'default', 'section_padding' => 'normal', 'background' => 'white'];
    $sectionClass = SectionLayout::sectionClasses($layout);
@endphp

<section class="{{ $sectionClass }}">
    <x-layout.page-container :width="$layout['container_width']">
        @if($title ?? $section?->heading)
            <h2 class="hw-section-title text-center mb-8">{{ $title ?? $section?->heading }}</h2>
        @endif
        <ul class="space-y-2 max-w-2xl mx-auto">
            @foreach($pathways as $pathway)
                <li>
                    <a href="{{ route('support-pathways') }}#{{ $pathway->slug }}" class="block py-3 px-4 rounded-lg border border-hw-border hover:bg-hw-dusty-blue-light/20 text-hw-heading font-medium transition-colors">
                        {{ $pathway->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </x-layout.page-container>
</section>
