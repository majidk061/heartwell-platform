@props(['pathways', 'barHeading' => 'Support Options Include:'])

<section class="border-y border-hw-border hw-pathway-bar hw-pathway-bar--client bg-[#f9f5f2]">
    <div class="hw-container py-4 md:py-5">
        @if($barHeading)
            <p class="text-sm font-semibold text-hw-heading text-center mb-3">{{ $barHeading }}</p>
        @endif
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <ul class="flex min-w-max md:min-w-0 md:w-full md:justify-between items-stretch snap-x snap-mandatory md:snap-none">
                @foreach($pathways as $index => $pathway)
                    <li class="flex-1 min-w-[9.5rem] sm:min-w-[10rem] snap-start shrink-0 md:shrink {{ $index > 0 ? 'border-l border-hw-blush/70' : '' }}">
                        <a href="{{ route('support-pathways') }}#{{ $pathway->slug }}"
                           class="block py-3 px-3 text-center text-xs sm:text-sm font-medium text-hw-text hover:text-hw-heading hover:bg-hw-blush-light/20 transition-colors min-h-[44px]">
                            {{ $pathway->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
