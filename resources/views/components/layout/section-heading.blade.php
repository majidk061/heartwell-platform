@props(['title', 'subtitle' => null, 'centered' => false])

<div class="{{ $centered ? 'text-center' : '' }} mb-6 md:mb-8">
    <h2 class="hw-section-title">{{ $title }}</h2>
    @if($subtitle)
        <p class="text-hw-text mt-2 md:mt-3 text-base md:text-lg">{{ $subtitle }}</p>
    @endif
</div>
