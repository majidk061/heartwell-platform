@props(['section', 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;

    $sectionContent = $section->content ?? [];
    $introImage = CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null));
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-background="dusty_blue">
    @if($section->heading)
        <x-layout.section-heading :title="$section->heading" centered />
    @endif
    @if($section->body ?? ($sectionContent['body'] ?? null))
        <p class="text-base md:text-lg text-hw-text mt-4 leading-relaxed">{{ $section->body ?? $sectionContent['body'] }}</p>
    @endif
    @if($introImage)
        <img src="{{ $introImage }}" alt="" class="mt-8 mx-auto rounded-lg max-w-2xl w-full aspect-video object-cover">
    @endif
</x-section-shell>
