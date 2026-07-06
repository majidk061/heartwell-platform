@props(['section', 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;

    $sectionContent = $section->content ?? [];
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="narrow" default-background="dusty_blue">
    @if($section->heading)
        <x-layout.section-heading :title="$section->heading" />
    @endif
    @if($section->body ?? ($sectionContent['body'] ?? null))
        <p class="text-base md:text-lg text-hw-text mt-4 leading-relaxed md:leading-loose md:text-[1.0625rem]">{{ $section->body ?? $sectionContent['body'] }}</p>
    @endif
    @php $introImage = CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null)); @endphp
    @if($introImage)
        <img src="{{ $introImage }}" alt="" class="mt-8 mx-auto rounded-lg max-w-xl w-full aspect-video object-cover">
    @endif
</x-section-shell>
