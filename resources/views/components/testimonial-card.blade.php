@props(['testimonial'])

@php
    use App\Domains\Content\Support\CmsImage;

    $photo = CmsImage::url($testimonial->image_path);
@endphp

<blockquote class="flex flex-col h-full bg-hw-white border border-hw-border rounded-xl p-6 shadow-sm">
    @if($photo)
        <img src="{{ $photo }}" alt="" class="w-14 h-14 rounded-full object-cover mb-4">
    @endif
    <p class="text-hw-text leading-relaxed flex-1">&ldquo;{{ $testimonial->quote }}&rdquo;</p>
    <footer class="mt-4 pt-4 border-t border-hw-border">
        <cite class="not-italic font-semibold text-hw-heading text-sm">{{ $testimonial->author_name }}</cite>
        @if($testimonial->attribution)
            <span class="block text-xs text-hw-muted mt-1">{{ $testimonial->attribution }}</span>
        @endif
    </footer>
</blockquote>
