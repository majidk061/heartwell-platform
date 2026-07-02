@props(['section', 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;

    $sectionContent = $section->content ?? [];
    $introImage = CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null));
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-width="narrow" default-background="dusty_blue">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10 items-center">
        <div>
            @if($section->heading)
                <x-layout.section-heading :title="$section->heading" />
            @endif
            @if($section->body ?? ($sectionContent['body'] ?? null))
                <p class="text-base md:text-lg text-hw-text mt-4 leading-relaxed">{{ $section->body ?? $sectionContent['body'] }}</p>
            @endif
        </div>
        @if($introImage)
            <img src="{{ $introImage }}" alt="" class="rounded-lg w-full aspect-video object-cover">
        @endif
    </div>
</x-section-shell>
