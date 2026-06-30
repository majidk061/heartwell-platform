@props(['section', 'imageUrl' => null, 'credentials' => [], 'imageFirst' => true])

@php
    use App\Domains\Content\Support\CmsImage;

    $content = $section?->content ?? [];
    $eyebrow = $section?->heading ?? 'Meet the Founder';
    $name = $content['name'] ?? $section?->subheading ?? 'Jacquie Wilson';
    $creds = ! empty($credentials) ? $credentials : ($content['credentials'] ?? []);
    $credString = is_array($creds) ? implode(', ', array_filter($creds)) : (string) $creds;
    $displayName = $credString ? "{$name}, {$credString}" : $name;
    $role = $content['role'] ?? 'Founder & Registered Nurse, HeartWell Aesthetics & Wellness';
    $pronunciation = $content['pronunciation'] ?? null;
    $bio = $section?->body ?? ($content['body'] ?? 'Jacquie Wilson brings nurse-led, clinically credentialed care to every HeartWell visit.');
    $src = CmsImage::url($imageUrl ?? $section?->image_url ?? ($content['image_url'] ?? null));
    $photoCol = $imageFirst ? 'lg:order-1' : 'lg:order-2';
    $textCol = $imageFirst ? 'lg:order-2' : 'lg:order-1';
@endphp

<section class="bg-hw-white hw-section">
    <x-layout.page-container>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10 items-center">
            <div class="aspect-square w-full max-w-md mx-auto lg:mx-0 rounded-lg overflow-hidden bg-hw-blush-light flex items-center justify-center {{ $photoCol }}">
                @if($src)
                    <img src="{{ $src }}" alt="{{ $name }}" class="w-full h-full object-cover">
                @else
                    <span class="text-hw-muted text-sm px-4 text-center">Founder photo placeholder</span>
                @endif
            </div>
            <div class="text-center lg:text-left {{ $textCol }}">
                <p class="hw-founder-eyebrow text-hw-blush uppercase tracking-wider text-sm font-semibold">{{ $eyebrow }}</p>
                <h2 class="hw-section-title mt-2">{{ $displayName }}</h2>
                @if($pronunciation)
                    <p class="text-hw-muted mt-1 text-sm italic">{{ $pronunciation }}</p>
                @endif
                <p class="text-hw-muted mt-1 text-sm md:text-base">{{ $role }}</p>
                <p class="text-hw-text mt-4 leading-relaxed text-base">{{ $bio }}</p>
                <a href="{{ route('meet-the-founder') }}" class="btn-primary sm:w-auto inline-flex mt-6">Learn More About My Story</a>
            </div>
        </div>
    </x-layout.page-container>
</section>
