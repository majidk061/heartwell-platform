@props(['title', 'subheading' => null, 'body' => null, 'centered' => false])

<section class="bg-hw-white hw-section border-b border-hw-border">
    <div class="hw-container">
        <div class="{{ $centered ? 'text-center mx-auto max-w-3xl' : 'max-w-3xl' }}">
            <h1 class="hw-page-title">{{ $title }}</h1>
            @if($subheading)
                <p class="font-heading text-xl md:text-2xl text-hw-blush italic mt-3">{{ $subheading }}</p>
            @endif
            @if($body)
                <p class="text-base md:text-lg text-hw-text mt-4 md:mt-6 leading-relaxed">{{ $body }}</p>
            @endif
        </div>
    </div>
</section>
