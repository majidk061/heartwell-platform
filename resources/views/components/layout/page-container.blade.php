@props(['narrow' => false, 'form' => false, 'class' => ''])

@php
    $containerClass = match (true) {
        $form => 'hw-container-form',
        $narrow => 'hw-container-narrow',
        default => 'hw-container',
    };
@endphp

<div {{ $attributes->merge(['class' => $containerClass.' '.$class]) }}>
    {{ $slot }}
</div>
