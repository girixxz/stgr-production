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
    // fallback: kalau pattern kosong, cocokkan URL persis
    if (!$patterns && url()->current() === url($href)) {
        $active = true;
    }

    $classes = $active
        ? 'flex items-center px-6 py-3 rounded-md bg-primary-light font-semibold text-font-base'
        : 'flex items-center px-6 py-3 rounded-md hover:bg-gray-light';
@endphp

<a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
