@props(['active'])

@php
$classes = ($active ?? false)
            ? 'top-link top-link-active'
            : 'top-link';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
