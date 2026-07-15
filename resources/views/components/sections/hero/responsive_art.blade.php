@props(['section' => null, 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;
    use App\Domains\Content\Support\SectionLayout;

    $sectionContent = $section->content ?? [];
    $layout = SectionLayout::resolve($sectionContent, $themeDefaults, 'hero', ['background' => 'white']);
    $sectionClass = SectionLayout::sectionClasses($layout).' hw-hero hw-hero--responsive-art';

    $desktopPath = $section->image_url ?? ($sectionContent['image_url'] ?? null);
    $mobilePath = $sectionContent['image_url_mobile'] ?? null;
    if (blank($mobilePath)) {
        $mobilePath = $desktopPath;
    }
    $desktop = CmsImage::url($desktopPath);
    $mobile = CmsImage::url($mobilePath);
@endphp

<section class="{{ $sectionClass }}">
    <x-layout.page-container :width="$layout['container_width']">
        @if($desktop || $mobile)
            <div class="hw-hero-artwork block w-full">
                <x-cms.responsive-hero-image
                    :desktop-url="$desktop"
                    :mobile-url="$mobile"
                    class="hw-hero-artwork__image w-full h-auto"
                />
            </div>
        @endif
    </x-layout.page-container>
</section>
