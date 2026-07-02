@props(['heading' => null, 'body' => null, 'section' => null, 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\SectionLayout;

    $sectionContent = $section?->content ?? [];
    $layout = $section
        ? SectionLayout::resolve($sectionContent, $themeDefaults ?? ($siteSettings['theme'] ?? []), 'intro', ['background' => 'dusty_blue'])
        : ['container_width' => 'default', 'section_padding' => 'normal', 'background' => 'dusty_blue'];
    $sectionClass = SectionLayout::sectionClasses($layout);
    $heading = $heading ?? $section?->heading ?? 'Required Clinical Intake & Clearance';
    $body = $body ?? $section?->body ?? ($sectionContent['body'] ?? '');
@endphp

<section class="{{ $sectionClass }} hw-compliance-section">
    <x-layout.page-container :width="$layout['container_width']">
        <div class="hw-compliance-callout">
            @if($heading)
                <h2 class="hw-compliance-callout__heading">{{ $heading }}</h2>
            @endif
            @if($body)
                <p class="hw-compliance-callout__body">{{ $body }}</p>
            @endif
        </div>
    </x-layout.page-container>
</section>
