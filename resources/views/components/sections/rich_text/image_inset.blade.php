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
    <div class="hw-rich-text-inset grid grid-cols-1 md:grid-cols-12 gap-8 items-start">
        @if($richImage)
            <img src="{{ $richImage }}" alt="" class="md:col-span-4 rounded-lg w-full max-h-80 object-cover">
        @endif
        @if(! empty($sectionContent['body']))
            <div @class([
                'prose prose-hw max-w-none text-hw-text leading-relaxed',
                'md:col-span-8' => $richImage,
                'md:col-span-12' => ! $richImage,
            ])>
                {!! $sectionContent['body'] !!}
            </div>
        @endif
    </div>
</x-section-shell>
