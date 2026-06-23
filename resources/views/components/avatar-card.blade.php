@props(['card'])

<article class="bg-hw-white border border-hw-border rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow flex flex-col">
    <div class="aspect-[4/3] bg-hw-taupe-light flex items-center justify-center">
        <span class="text-hw-muted text-sm px-4 text-center">{{ $card['headline'] }}</span>
    </div>
    <div class="p-6 flex flex-col flex-1">
        <h3 class="font-heading text-xl text-hw-heading">{{ $card['headline'] }}</h3>
        <p class="text-hw-text mt-3 flex-1">{{ $card['subtext'] }}</p>
        <a href="{{ route('support-pathways') }}#{{ $card['pathway_slug'] ?? '' }}"
           class="mt-4 inline-flex items-center text-hw-blush font-semibold hover:text-hw-heading transition-colors min-h-[44px]">
            {{ $card['cta_label'] }} &rarr;
        </a>
    </div>
</article>
