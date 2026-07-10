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
        <div class="hw-group-individual-grid grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mt-8 md:mt-10 items-stretch">
            @foreach($columns as $column)
                @php $columnImage = CmsImage::url($column['image_url'] ?? null); @endphp
                <article class="hw-group-individual-card flex h-full flex-col overflow-hidden rounded-xl border border-hw-border bg-hw-white">
                    @if($columnImage)
                        <div class="hw-group-individual-card__media">
                            <img
                                src="{{ $columnImage }}"
                                alt=""
                                class="hw-group-individual-card__image"
                                loading="lazy"
                                decoding="async"
                            >
                        </div>
                    @endif
                    <div class="flex flex-1 flex-col p-6 md:p-8">
                        @if(! empty($column['subtitle']))
                            <p class="text-sm font-semibold uppercase tracking-wide text-hw-dusty-blue">{{ $column['subtitle'] }}</p>
                        @endif
                        @if(! empty($column['title']))
                            <h3 class="font-heading text-xl text-hw-heading {{ filled($column['subtitle'] ?? null) ? 'mt-2' : '' }}">{{ $column['title'] }}</h3>
                        @endif
                        @if(! empty($column['body']))
                            <p class="mt-4 flex-1 text-hw-text leading-relaxed whitespace-pre-line">{{ $column['body'] }}</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
    @if(! empty($sectionContent['body']))
        <div class="prose prose-hw max-w-none mt-8 text-hw-text leading-relaxed text-left hw-prose-narrow">
            {!! $sectionContent['body'] !!}
        </div>
    @endif
</x-section-shell>
