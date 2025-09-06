@props(['href' => '#', 'pattern' => null])

@php
    $patterns = (array) ($pattern ?? []);
    $active = false;

    foreach ($patterns as $p) {
        if (request()->routeIs($p) || request()->is($p)) {
            $active = true;
            break;
        }
    }

    $base = 'block w-full px-6 py-3 rounded-md pl-12';
    // Sama persis seperti item utama: bg-green-200 + text-green-700
    $classes = $active ? "$base bg-green-200 text-green-700" : "$base text-gray-600 hover:bg-gray-100";
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
