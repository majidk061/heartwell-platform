@props([
    'desktopUrl' => null,
    'mobileUrl' => null,
    'class' => '',
    'loading' => 'eager',
])

@php
    $desktop = $desktopUrl;
    $mobile = $mobileUrl ?: $desktopUrl;
    $fallback = $desktop ?: $mobile;
@endphp

@if($fallback)
    <picture class="hw-responsive-hero-picture">
        @if($desktop)
            <source media="(min-width: 1024px)" srcset="{{ $desktop }}">
        @endif
        @if($mobile)
            <source media="(max-width: 1023px)" srcset="{{ $mobile }}">
        @endif
        <img
            src="{{ $fallback }}"
            alt=""
            class="{{ $class }}"
            loading="{{ $loading }}"
            decoding="async"
        >
    </picture>
@endif
