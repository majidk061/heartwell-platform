@props(['pathways', 'barHeading' => null])

<section class="border-y border-hw-border bg-hw-blush-light/30 hw-pathway-bar">
    <div class="hw-container py-4 overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
        @if($barHeading)
            <p class="text-center text-sm font-semibold text-hw-heading mb-3">{{ $barHeading }}</p>
        @endif
        <ul class="flex gap-2 min-w-max md:min-w-0 md:flex-wrap md:justify-center snap-x snap-mandatory">
            @foreach($pathways as $pathway)
                <li class="snap-start shrink-0">
                    <a href="{{ route('support-pathways') }}#{{ $pathway->slug }}"
                       class="inline-flex items-center px-4 py-2 rounded-full border border-hw-border bg-hw-white text-xs sm:text-sm font-medium text-hw-text hover:border-hw-blush hover:bg-hw-blush-light/40 transition-colors min-h-[44px]">
                        {{ $pathway->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
