@props(['section', 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;

    $sectionContent = $section->content ?? [];
    $columns = $sectionContent['columns'] ?? [];
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-background="dusty_blue">
    @if($section->heading)
        <x-layout.section-heading :title="$section->heading" centered />
    @endif
    @if(! empty($columns))
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mt-8">
            @foreach($columns as $column)
                @php $columnImage = CmsImage::url($column['image_url'] ?? null); @endphp
                <div class="p-6 md:p-8 rounded-xl border border-hw-border bg-hw-white">
                    @if($columnImage)
                        <img src="{{ $columnImage }}" alt="" class="w-full h-48 object-cover rounded-lg mb-5" loading="lazy" decoding="async">
                    @endif
                    @if(! empty($column['subtitle']))
                        <p class="text-sm font-semibold uppercase tracking-wide text-hw-dusty-blue">{{ $column['subtitle'] }}</p>
                    @endif
                    @if(! empty($column['title']))
                        <h3 class="font-heading text-xl text-hw-heading {{ filled($column['subtitle'] ?? null) ? 'mt-2' : '' }}">{{ $column['title'] }}</h3>
                    @endif
                    @if(! empty($column['body']))
                        <p class="text-hw-text mt-4 leading-relaxed whitespace-pre-line">{{ $column['body'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
    @if(! empty($sectionContent['body']))
        <div class="prose prose-hw max-w-none mt-8 text-hw-text leading-relaxed text-left hw-prose-narrow">
            {!! $sectionContent['body'] !!}
        </div>
    @endif
</x-section-shell>
