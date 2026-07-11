@props(['section', 'themeDefaults' => null])

@php
    use App\Domains\Content\Support\CmsImage;

    $sectionContent = $section->content ?? [];
    $expectItems = is_array($sectionContent['expect_items'] ?? null) ? $sectionContent['expect_items'] : [];
    $richImage = CmsImage::url($section->image_url ?? ($sectionContent['image_url'] ?? null));
    $extraClass = trim(($sectionContent['section_class'] ?? '').' hw-wj-expect-split');
@endphp

<x-section-shell :section="$section" :theme-defaults="$themeDefaults" default-align="left" :class="$extraClass">
    <div class="hw-wj-expect-split__grid">
        <div class="hw-wj-expect-split__copy">
            @if(! empty($sectionContent['body']))
                <div class="hw-wj-step__prose prose prose-hw max-w-none">
                    {!! $sectionContent['body'] !!}
                </div>
            @endif
            @if($richImage)
                <img src="{{ $richImage }}" alt="" class="hw-wj-expect-split__photo" loading="lazy" decoding="async">
            @endif
        </div>

        @if($expectItems !== [])
            <aside class="hw-wj-expect-split__panel" aria-label="What you can expect">
                <h3 class="hw-wj-expect-split__panel-title">{{ $sectionContent['expect_heading'] ?? 'What You Can Expect:' }}</h3>
                <ul class="hw-wj-expect-split__list">
                    @foreach($expectItems as $item)
                        <li class="hw-wj-expect-split__item">
                            @if(! empty($item['title']))
                                <p class="hw-wj-expect-split__item-title">{{ $item['title'] }}</p>
                            @endif
                            @if(! empty($item['body']))
                                <p class="hw-wj-expect-split__item-body">{{ $item['body'] }}</p>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </aside>
        @endif
    </div>
</x-section-shell>
