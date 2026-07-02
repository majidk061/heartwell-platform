@props(['section', 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;

    $sectionContent = $section->content ?? [];
    $richImage = CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null));
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="narrow" default-align="left">
    @if($section->heading)
        <x-layout.section-heading :title="$section->heading" />
    @endif
    @if($richImage)
        <img src="{{ $richImage }}" alt="" class="mb-8 rounded-lg w-full max-h-96 object-cover">
    @endif
    @if(! empty($sectionContent['body']))
        <div class="prose prose-hw max-w-none text-hw-text leading-relaxed">
            {!! $sectionContent['body'] !!}
        </div>
    @endif
</x-section-shell>
