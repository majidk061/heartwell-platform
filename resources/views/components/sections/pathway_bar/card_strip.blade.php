@props(['pathways', 'barHeading' => null])

<section class="bg-[#f9f5f2] hw-pathway-bar hw-pathway-bar--cards">
    <div class="hw-container py-5 md:py-6">
        @if($barHeading)
            <p class="text-center text-sm font-semibold uppercase tracking-wide text-hw-heading mb-4">{{ $barHeading }}</p>
        @endif
        <ul class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @foreach($pathways as $pathway)
                <li>
                    <a href="{{ route('support-pathways') }}#{{ $pathway->slug }}"
                       class="block h-full p-4 rounded-lg border border-hw-border/60 bg-hw-white shadow-sm text-center text-xs sm:text-sm font-medium text-hw-text hover:shadow-md hover:border-hw-blush transition-all min-h-[44px]">
                        {{ $pathway->title }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</section>
