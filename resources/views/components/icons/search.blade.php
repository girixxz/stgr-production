@props([
    'class' => '', // biar bisa override ukuran atau tambahan class
])

<svg {{ $attributes->merge(['class' => "absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 h-4 w-4 $class"]) }}
    fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <circle cx="11" cy="11" r="7"></circle>
    <path d="m21 21-3.6-3.6"></path>
</svg>
