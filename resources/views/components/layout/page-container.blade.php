@props(['narrow' => false, 'form' => false, 'width' => null, 'class' => ''])

@php
    $resolvedWidth = $width ?? match (true) {
        $form => 'form',
        $narrow => 'narrow',
        default => 'default',
    };

    $containerClass = match ($resolvedWidth) {
        'full' => 'hw-container-full',
        'near_full' => 'hw-container-near-full',
        'extra_wide' => 'hw-container-extra-wide',
        'expanded' => 'hw-container-expanded',
        'comfortable' => 'hw-container-comfortable',
        'wide' => 'hw-container-wide',
        'narrow' => 'hw-container-narrow',
        'form' => 'hw-container-form',
        'prose' => 'hw-container-prose',
        default => 'hw-container',
    };
@endphp

<div {{ $attributes->merge(['class' => $containerClass.' '.$class]) }}>
    {{ $slot }}
</div>
