@props(['section' => null, 'imageUrl' => null])

@php
    $src = $imageUrl;
    if ($src && ! str_starts_with($src, 'http')) {
        $src = \App\Domains\Content\Support\CmsImage::url($src);
    }
@endphp

<section class="bg-hw-white hw-section">
    <x-layout.page-container>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 items-center">
            <div class="aspect-square w-full max-w-md mx-auto lg:mx-0 rounded-lg overflow-hidden bg-hw-blush-light flex items-center justify-center">
                @if($src)
                    <img src="{{ $src }}" alt="Founder" class="w-full h-full object-cover">
                @else
                    <span class="text-hw-muted text-sm px-4 text-center">Founder photo placeholder</span>
                @endif
            </div>
            <div class="text-center lg:text-left">
                <p class="text-hw-blush uppercase tracking-wider text-sm font-semibold">Meet the Founder</p>
                <h2 class="hw-section-title mt-2">Jacquie Wilson, BSN, RN, MBA</h2>
                <p class="text-hw-muted mt-1 text-sm md:text-base">Founder & Registered Nurse</p>
                <p class="text-hw-text mt-4 leading-relaxed text-base">
                    {{ $section?->body ?? 'Jacquie Wilson brings nurse-led, clinically credentialed care to every HeartWell visit — thoughtful support for every stage of life.' }}
                </p>
                <a href="{{ route('meet-the-founder') }}" class="btn-primary sm:w-auto inline-flex mt-6">Learn More About My Story</a>
            </div>
        </div>
    </x-layout.page-container>
</section>
