@props(['active'])

@php
$classes = ($active ?? false)
            ? 'menu-link menu-link-active w-full text-start'
            : 'menu-link w-full text-start';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
