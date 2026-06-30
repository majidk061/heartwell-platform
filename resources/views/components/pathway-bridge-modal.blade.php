@props(['pathwayTitle', 'pathwayIntro', 'ctaUrl', 'ctaLabel'])

<div
    x-data="{ open: false }"
    class="inline-flex"
>
    <button type="button" class="btn-primary sm:w-auto" @click="open = true">
        {{ $ctaLabel }}
    </button>

    <div
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="pathway-bridge-title"
    >
        <div class="absolute inset-0 bg-hw-heading/40" @click="open = false" aria-hidden="true"></div>
        <div class="relative w-full max-w-lg rounded-xl bg-hw-white p-6 shadow-xl">
            <h3 id="pathway-bridge-title" class="font-heading text-xl text-hw-heading">{{ $pathwayTitle }}</h3>
            <p class="text-hw-text mt-3 leading-relaxed">{{ $pathwayIntro }}</p>
            <p class="text-sm text-hw-muted mt-4">
                When you are ready, we will guide you through booking and clinical intake — all under the HeartWell brand.
            </p>
            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <a href="{{ $ctaUrl }}" class="btn-primary text-center">Continue to booking</a>
                <button type="button" class="btn-secondary" @click="open = false">Keep exploring</button>
            </div>
        </div>
    </div>
</div>
