@props(['pathways'])

<section class="bg-hw-white hw-section">
    <x-layout.page-container>
        <h2 class="hw-section-title text-center mb-8 md:mb-10">Support Pathways</h2>
        <div class="space-y-4" x-data="{ open: null }">
            @foreach($pathways as $index => $pathway)
                <div class="border border-hw-border rounded-lg overflow-hidden" id="{{ $pathway->slug }}">
                    <button type="button"
                            class="w-full flex items-center justify-between gap-4 p-4 md:p-5 text-left min-h-[44px] bg-hw-white hover:bg-hw-dusty-blue-light/20 transition-colors"
                            @click="open = open === {{ $index }} ? null : {{ $index }}"
                            :aria-expanded="open === {{ $index }}">
                        <span class="font-heading text-base md:text-lg text-hw-heading">{{ $pathway->title }}</span>
                        <svg class="w-5 h-5 shrink-0 text-hw-heading transition-transform" :class="open === {{ $index }} && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open === {{ $index }}" x-cloak class="px-4 md:px-5 pb-4 md:pb-5">
                        <p class="text-hw-text text-base">{{ $pathway->intro }}</p>
                        @if($pathway->body)
                            <div class="mt-3 text-hw-text text-base">{!! nl2br(e($pathway->body)) !!}</div>
                        @endif
                        <a href="{{ route('contact') }}#book" class="btn-primary sm:w-auto inline-flex mt-4">{{ config('heartwell.ctas.primary.label') }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    </x-layout.page-container>
</section>
