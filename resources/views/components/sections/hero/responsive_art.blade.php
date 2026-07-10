@props(['section' => null, 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;
    use App\Domains\Content\Support\SectionLayout;

    $sectionContent = $section->content ?? [];
    $layout = SectionLayout::resolve($sectionContent, $themeDefaults, 'hero', ['background' => 'white']);
    $sectionClass = SectionLayout::sectionClasses($layout).' hw-hero hw-hero--responsive-art';

    $desktop = CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null));
    $mobile = CmsImage::url($sectionContent['image_url_mobile'] ?? null);
    $clean = CmsImage::url($sectionContent['image_url_clean'] ?? null);
    $fallback = $mobile ?: $clean ?: $desktop;
@endphp

<section class="{{ $sectionClass }}">
    <x-layout.page-container :width="$layout['container_width']">
        @if($desktop || $mobile || $clean)
            <picture class="hw-hero-artwork block w-full">
                @if($desktop)
                    <source media="(min-width: 1024px)" srcset="{{ $desktop }}">
                @endif
                @if($mobile)
                    <source media="(max-width: 1023px)" srcset="{{ $mobile }}">
                @endif
                <img
                    src="{{ $fallback }}"
                    alt=""
                    class="hw-hero-artwork__image w-full h-auto"
                    loading="eager"
                    decoding="async"
                >
            </picture>
        @endif
    </x-layout.page-container>
</section>
