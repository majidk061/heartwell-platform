@props(['testimonials', 'heading' => null])

@php
    use App\Domains\Content\Support\CmsImage;
@endphp

@if($testimonials->isNotEmpty())
    <section class="hw-section bg-hw-taupe-light/30">
        <x-layout.page-container>
            @if($heading)
                <x-layout.section-heading :title="$heading" centered />
            @endif
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 {{ $heading ? 'mt-8 md:mt-10' : '' }}">
                @foreach($testimonials as $testimonial)
                    <blockquote class="flex flex-col bg-hw-white border border-hw-border rounded-xl p-6 shadow-sm">
                        @php $photo = CmsImage::url($testimonial->image_path); @endphp
                        @if($photo)
                            <img
                                src="{{ $photo }}"
                                alt=""
                                class="w-14 h-14 rounded-full object-cover mb-4"
                            >
                        @endif
                        <p class="text-hw-text leading-relaxed flex-1">&ldquo;{{ $testimonial->quote }}&rdquo;</p>
                        <footer class="mt-4 pt-4 border-t border-hw-border">
                            <cite class="not-italic font-semibold text-hw-heading text-sm">
                                {{ $testimonial->author_name }}
                            </cite>
                            @if($testimonial->attribution)
                                <span class="block text-xs text-hw-muted mt-1">{{ $testimonial->attribution }}</span>
                            @endif
                        </footer>
                    </blockquote>
                @endforeach
            </div>
        </x-layout.page-container>
    </section>
@endif
