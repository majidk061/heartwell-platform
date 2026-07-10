@props(['section', 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;

    $sectionContent = $section->content ?? [];
    $richImage = CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null));
    $extraClass = $sectionContent['section_class'] ?? 'hw-rich-text-section--image-feature';
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="default" default-align="left" :class="$extraClass">
    @if($section->heading)
        <x-layout.section-heading :title="$section->heading" />
    @endif
    @if($richImage)
        <img src="{{ $richImage }}" alt="" class="hw-rich-text-feature__image mt-8 rounded-lg w-full object-cover">
    @endif
    @if(! empty($sectionContent['body']))
        <div class="prose prose-hw max-w-none text-hw-text leading-relaxed hw-prose-narrow mt-8">
            {!! $sectionContent['body'] !!}
        </div>
    @endif
</x-section-shell>
