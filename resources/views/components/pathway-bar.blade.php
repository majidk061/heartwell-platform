@props(['pathways'])

<section class="border-y border-hw-border bg-hw-white">
    <div class="hw-container overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
        <ul class="flex min-w-max md:min-w-0 md:w-full md:justify-between divide-x divide-hw-border snap-x snap-mandatory md:snap-none">
            @foreach($pathways as $pathway)
                <li class="flex-1 min-w-[9.5rem] sm:min-w-[10rem] snap-start shrink-0 md:shrink">
                    <a href="{{ route('support-pathways') }}#{{ $pathway->slug }}"
                       class="block py-4 px-3 text-center text-xs sm:text-sm font-medium text-hw-text hover:text-hw-heading hover:bg-hw-dusty-blue-light/30 transition-colors min-h-[44px]">
                        {{ $pathway->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
